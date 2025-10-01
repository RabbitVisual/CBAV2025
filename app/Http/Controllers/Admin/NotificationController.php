<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Models\Membro;
use App\Models\Ministerio;
use App\Services\NotificationService;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-notifications');
    }

    /**
     * Exibir lista de notificações
     */
    public function index(Request $request)
    {
        $query = Notification::with(['sender', 'reads'])
                            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        $this->applyFilters($query, $request);

        $notifications = $query->paginate(20);

        // Estatísticas
        $stats = $this->getStatistics();

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create()
    {
        $templates = NotificationTemplate::active()->get();
        $users = User::select('id', 'name', 'email')->get();
        $members = Membro::where('ativo', true)->select('id', 'nome', 'email')->get();
        $ministries = Ministerio::where('ativo', true)->select('id', 'nome')->get();

        return view('admin.notifications.create', compact('templates', 'users', 'members', 'ministries'));
    }

    /**
     * Salvar nova notificação
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error,urgent',
            'category' => 'required|string|max:50',
            'priority' => 'required|in:low,normal,high,urgent',
            'recipient_type' => 'required|in:all,user,ministry,role',
            'recipient_id' => 'nullable|integer',
            'scheduled_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:scheduled_at',
            'channels' => 'array',
            'channels.*' => 'in:database,email,push,sms',
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:100',
            'template_id' => 'nullable|exists:notification_templates,id',
            'template_variables' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $this->prepareNotificationData($request);

            // Criar usando template se especificado
            if ($request->filled('template_id')) {
                $notification = $this->createFromTemplate($request);
            } else {
                $notification = $this->createDirectNotification($data);
            }

            DB::commit();

            return redirect()->route('admin.notifications.index')
                           ->with('success', 'Notificação criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating notification: ' . $e->getMessage());
            return back()->with('error', 'Erro ao criar notificação: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Exibir notificação específica
     */
    public function show(Notification $notification)
    {
        $notification->load(['sender', 'reads.user', 'deliveryLogs']);
        
        $stats = [
            'total_recipients' => $this->calculateTotalRecipients($notification),
            'read_count' => $notification->reads()->where('is_read', true)->count(),
            'delivery_stats' => $this->getDeliveryStats($notification)
        ];

        return view('admin.notifications.show', compact('notification', 'stats'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(Notification $notification)
    {
        if ($notification->status === 'sent') {
            return back()->with('error', 'Não é possível editar notificações já enviadas.');
        }

        $templates = NotificationTemplate::active()->get();
        $users = User::select('id', 'name', 'email')->get();
        $members = Membro::where('ativo', true)->select('id', 'nome', 'email')->get();
        $ministries = Ministerio::where('ativo', true)->select('id', 'nome')->get();

        return view('admin.notifications.edit', compact('notification', 'templates', 'users', 'members', 'ministries'));
    }

    /**
     * Atualizar notificação
     */
    public function update(Request $request, Notification $notification)
    {
        if ($notification->status === 'sent') {
            return back()->with('error', 'Não é possível editar notificações já enviadas.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error,urgent',
            'category' => 'required|string|max:50',
            'priority' => 'required|in:low,normal,high,urgent',
            'scheduled_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:scheduled_at',
            'channels' => 'array',
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $this->prepareNotificationData($request);
            $notification->update($data);

            return redirect()->route('admin.notifications.index')
                           ->with('success', 'Notificação atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error updating notification: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar notificação.')->withInput();
        }
    }

    /**
     * Excluir notificação
     */
    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();
            return redirect()->route('admin.notifications.index')
                           ->with('success', 'Notificação excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir notificação.');
        }
    }

    /**
     * Enviar notificação imediatamente
     */
    public function send(Notification $notification)
    {
        try {
            if ($notification->status === 'sent') {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação já foi enviada.'
                ]);
            }

            // Processar envio
            $this->processNotificationSending($notification);
            $notification->markAsSent();

            return response()->json([
                'success' => true,
                'message' => 'Notificação enviada com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar notificação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar notificação de teste
     */
    public function sendTest(Request $request)
    {
        try {
            $testNotification = NotificationService::createForUser(Auth::id(), [
                'title' => 'Notificação de Teste 🧪',
                'message' => 'Esta é uma notificação de teste enviada pelo administrador para verificar o funcionamento do sistema.',
                'type' => 'info',
                'category' => 'system',
                'priority' => 'normal',
                'icon' => 'fas fa-flask',
                'channels' => ['database', 'push']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notificação de teste enviada com sucesso!',
                'notification_id' => $testNotification->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending test notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar notificação de teste.'
            ], 500);
        }
    }

    /**
     * Ações em massa
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:send,delete,cancel',
            'notification_ids' => 'required|array|min:1',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.'
            ], 422);
        }

        try {
            $notifications = Notification::whereIn('id', $request->notification_ids)->get();
            $count = 0;

            foreach ($notifications as $notification) {
                switch ($request->action) {
                    case 'send':
                        if ($notification->status !== 'sent') {
                            $this->processNotificationSending($notification);
                            $notification->markAsSent();
                            $count++;
                        }
                        break;
                    case 'delete':
                        $notification->delete();
                        $count++;
                        break;
                    case 'cancel':
                        if ($notification->status === 'scheduled') {
                            $notification->update(['status' => 'cancelled']);
                            $count++;
                        }
                        break;
                }
            }

            $actionText = match($request->action) {
                'send' => 'enviada(s)',
                'delete' => 'excluída(s)',
                'cancel' => 'cancelada(s)'
            };

            return response()->json([
                'success' => true,
                'message' => "{$count} notificação(ões) {$actionText} com sucesso!",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao executar ação em massa.'
            ], 500);
        }
    }

    /**
     * Processar notificações agendadas
     */
    public function processScheduled()
    {
        try {
            $processed = NotificationService::processScheduledNotifications();
            
            return response()->json([
                'success' => true,
                'message' => "{$processed} notificação(ões) processada(s) com sucesso!",
                'count' => $processed
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing scheduled notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar notificações agendadas.'
            ], 500);
        }
    }

    /**
     * Exportar notificações
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        try {
            $query = Notification::with(['sender'])->orderBy('created_at', 'desc');
            $this->applyFilters($query, $request);
            $notifications = $query->get();

            if ($format === 'csv') {
                return $this->exportToCsv($notifications);
            } else {
                return $this->exportToExcel($notifications);
            }
        } catch (\Exception $e) {
            Log::error('Error exporting notifications: ' . $e->getMessage());
            return back()->with('error', 'Erro ao exportar notificações.');
        }
    }

    /**
     * Obter estatísticas do dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total' => Notification::count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'scheduled' => Notification::where('status', 'scheduled')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
            'recent' => Notification::where('created_at', '>=', now()->subDays(7))->count(),
            'by_type' => Notification::select('type', DB::raw('count(*) as count'))
                                   ->groupBy('type')
                                   ->pluck('count', 'type'),
            'by_category' => Notification::select('category', DB::raw('count(*) as count'))
                                       ->groupBy('category')
                                       ->pluck('count', 'category'),
            'delivery_rate' => $this->calculateDeliveryRate(),
            'engagement_rate' => $this->calculateEngagementRate()
        ];

        return view('admin.notifications.dashboard', compact('stats'));
    }

    // Métodos auxiliares privados
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    }

    private function prepareNotificationData(Request $request): array
    {
        return [
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'category' => $request->category,
            'priority' => $request->priority,
            'recipient_type' => $request->recipient_type,
            'recipient_id' => $request->recipient_id,
            'scheduled_at' => $request->scheduled_at ? Carbon::parse($request->scheduled_at) : null,
            'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
            'channels' => $request->channels ?? ['database'],
            'action_url' => $request->action_url,
            'action_text' => $request->action_text,
            'sender_id' => Auth::id(),
            'status' => $request->scheduled_at ? 'scheduled' : 'draft'
        ];
    }

    private function createFromTemplate(Request $request): Notification
    {
        $template = NotificationTemplate::findOrFail($request->template_id);
        $variables = $request->template_variables ?? [];
        
        return $template->createNotification($variables, [
            'recipient_type' => $request->recipient_type,
            'recipient_id' => $request->recipient_id,
            'scheduled_at' => $request->scheduled_at ? Carbon::parse($request->scheduled_at) : null,
            'sender_id' => Auth::id()
        ]);
    }

    private function createDirectNotification(array $data): Notification
    {
        return Notification::create($data);
    }

    private function processNotificationSending(Notification $notification): void
    {
        // Implementar lógica de envio baseada no recipient_type
        switch ($notification->recipient_type) {
            case 'all':
                $this->sendToAllUsers($notification);
                break;
            case 'user':
                $this->sendToSpecificUser($notification);
                break;
            case 'ministry':
                $this->sendToMinistry($notification);
                break;
            case 'role':
                $this->sendToRole($notification);
                break;
        }
    }

    private function sendToAllUsers(Notification $notification): void
    {
        $users = User::where('active', true)->get();
        foreach ($users as $user) {
            NotificationService::createForUser($user->id, $notification->toArray());
        }
    }

    private function sendToSpecificUser(Notification $notification): void
    {
        if ($notification->recipient_id) {
            NotificationService::createForUser($notification->recipient_id, $notification->toArray());
        }
    }

    private function sendToMinistry(Notification $notification): void
    {
        if ($notification->recipient_id) {
            $ministry = Ministerio::find($notification->recipient_id);
            if ($ministry) {
                $members = $ministry->membros()->where('ativo', true)->get();
                foreach ($members as $member) {
                    if ($member->user) {
                        NotificationService::createForUser($member->user->id, $notification->toArray());
                    }
                }
            }
        }
    }

    private function sendToRole(Notification $notification): void
    {
        // Implementar envio por role/cargo
    }

    private function getStatistics(): array
    {
        return [
            'total' => Notification::count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'scheduled' => Notification::where('status', 'scheduled')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
            'draft' => Notification::where('status', 'draft')->count()
        ];
    }

    private function calculateTotalRecipients(Notification $notification): int
    {
        return match($notification->recipient_type) {
            'all' => User::count(),
            'user' => 1,
            'ministry' => $notification->recipient_id ? 
                Ministerio::find($notification->recipient_id)?->membros()->count() ?? 0 : 0,
            default => 0
        };
    }

    private function getDeliveryStats(Notification $notification): array
    {
        return $notification->deliveryLogs()
                          ->select('channel', 'status', DB::raw('count(*) as count'))
                          ->groupBy('channel', 'status')
                          ->get()
                          ->groupBy('channel')
                          ->map(function ($logs) {
                              return $logs->pluck('count', 'status');
                          })
                          ->toArray();
    }

    private function calculateDeliveryRate(): float
    {
        $total = Notification::where('status', 'sent')->count();
        if ($total === 0) return 0;
        
        $successful = Notification::where('status', 'sent')
                                 ->whereHas('deliveryLogs', function ($q) {
                                     $q->where('status', 'delivered');
                                 })
                                 ->count();
        
        return round(($successful / $total) * 100, 2);
    }

    private function calculateEngagementRate(): float
    {
        $total = Notification::where('status', 'sent')->count();
        if ($total === 0) return 0;
        
        $engaged = Notification::where('status', 'sent')
                              ->whereHas('reads', function ($q) {
                                  $q->where('is_read', true);
                              })
                              ->count();
        
        return round(($engaged / $total) * 100, 2);
    }

    private function exportToCsv($notifications)
    {
        // Implementar exportação CSV
    }

    private function exportToExcel($notifications)
    {
        // Implementar exportação Excel
    }
}