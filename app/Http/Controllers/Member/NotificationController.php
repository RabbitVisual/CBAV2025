<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\NotificationPreference;
use App\Models\QuizAlert;
use App\Services\NotificationService;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibir lista de notificações do usuário
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Aplicar filtros
        $filters = $this->getFilters($request);
        
        // Buscar notificações
        $notifications = NotificationService::getUserNotifications($user->id, $filters);
        
        // Paginação manual para Collection
        $perPage = 15;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $paginatedNotifications = $notifications->slice($offset, $perPage);
        
        // Estatísticas
        $stats = $this->getUserStats($user->id);
        
        // Preferências do usuário
        $preferences = NotificationService::getUserPreferences($user->id);
        
        return view('member.notifications.index', compact(
            'paginatedNotifications',
            'notifications',
            'stats',
            'preferences',
            'filters'
        ));
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead(Request $request, $notificationId)
    {
        try {
            $success = NotificationService::markAsRead($notificationId, Auth::id());
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notificação marcada como lida.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificação como lida.'
            ], 500);
        }
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        try {
            $count = NotificationService::markAllAsRead(Auth::id());
            
            return response()->json([
                'success' => true,
                'message' => "{$count} notificação(ões) marcada(s) como lida(s).",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificações como lidas.'
            ], 500);
        }
    }

    /**
     * Arquivar notificação
     */
    public function archive($notificationId)
    {
        try {
            $success = NotificationService::archive($notificationId, Auth::id());
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notificação arquivada.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error archiving notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao arquivar notificação.'
            ], 500);
        }
    }

    /**
     * Favoritar/desfavoritar notificação
     */
    public function toggleStar($notificationId)
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.'
                ], 404);
            }

            $isStarred = $notification->isStarredBy(Auth::id());
            
            if ($isStarred) {
                $notification->unstarBy(Auth::id());
                $message = 'Notificação removida dos favoritos.';
            } else {
                $notification->starBy(Auth::id());
                $message = 'Notificação adicionada aos favoritos.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_starred' => !$isStarred
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling star: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao favoritar notificação.'
            ], 500);
        }
    }

    /**
     * Registrar clique em ação da notificação
     */
    public function recordAction(Request $request, $notificationId)
    {
        try {
            $data = $request->only(['action_type', 'url', 'timestamp']);
            $success = NotificationService::recordActionClick($notificationId, Auth::id(), $data);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ação registrada.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error recording action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar ação.'
            ], 500);
        }
    }

    /**
     * Obter contagem de notificações não lidas
     */
    public function getUnreadCount()
    {
        try {
            $count = NotificationService::getUnreadCount(Auth::id());
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }

    /**
     * Obter notificações para dropdown/header
     */
    public function getHeaderNotifications()
    {
        try {
            $notifications = NotificationService::getUserNotifications(Auth::id(), [
                'limit' => 10,
                'unread_only' => false
            ]);
            
            $unreadCount = NotificationService::getUnreadCount(Auth::id());
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => str_limit($notification->message, 100),
                        'type' => $notification->type,
                        'icon' => $notification->type_icon,
                        'color' => $notification->type_color,
                        'time_ago' => $notification->time_ago,
                        'is_read' => $notification->isReadBy(Auth::id()),
                        'action_url' => $notification->action_url,
                        'action_text' => $notification->action_text
                    ];
                }),
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting header notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => [],
                'unread_count' => 0
            ]);
        }
    }

    /**
     * Exibir configurações de notificação
     */
    public function preferences()
    {
        $preferences = NotificationService::getUserPreferences(Auth::id());
        
        return view('member.notifications.preferences', compact('preferences'));
    }

    /**
     * Atualizar configurações de notificação
     */
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'quiz_notifications' => 'boolean',
            'ministry_notifications' => 'boolean',
            'financial_notifications' => 'boolean',
            'event_notifications' => 'boolean',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
            'quiet_days' => 'array',
            'quiet_days.*' => 'integer|between:0,6',
            'group_similar' => 'boolean',
            'max_per_hour' => 'integer|min:1|max:50',
            'digest_frequency' => 'integer|in:0,1,7'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $success = NotificationService::updateUserPreferences(Auth::id(), $request->all());
            
            if ($success) {
                return back()->with('success', 'Preferências atualizadas com sucesso!');
            } else {
                return back()->with('error', 'Erro ao atualizar preferências.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating preferences: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar preferências.');
        }
    }

    /**
     * Exibir alertas de quiz
     */
    public function quizAlerts(Request $request)
    {
        $query = QuizAlert::forUser(Auth::id())
                         ->with(['quizSession'])
                         ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('unread_only')) {
            $query->unread();
        }

        $alerts = $query->paginate(20);
        
        $stats = [
            'total' => QuizAlert::forUser(Auth::id())->count(),
            'unread' => QuizAlert::forUser(Auth::id())->unread()->count(),
            'achievements' => QuizAlert::forUser(Auth::id())->byType('achievement')->count(),
            'records' => QuizAlert::forUser(Auth::id())->byType('new_record')->count()
        ];

        return view('member.notifications.quiz-alerts', compact('alerts', 'stats'));
    }

    /**
     * Marcar alerta de quiz como lido
     */
    public function markQuizAlertAsRead($alertId)
    {
        try {
            $alert = QuizAlert::where('id', $alertId)
                             ->where('user_id', Auth::id())
                             ->first();
            
            if (!$alert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alerta não encontrado.'
                ], 404);
            }

            $alert->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Alerta marcado como lido.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking quiz alert as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar alerta como lido.'
            ], 500);
        }
    }

    /**
     * Limpar notificações antigas
     */
    public function cleanup()
    {
        try {
            $userId = Auth::id();
            
            // Arquivar notificações lidas antigas (mais de 30 dias)
            $oldReadNotifications = Notification::forUser($userId)
                                               ->readForUser($userId)
                                               ->where('created_at', '<', now()->subDays(30))
                                               ->get();
            
            $count = 0;
            foreach ($oldReadNotifications as $notification) {
                $notification->archiveBy($userId);
                $count++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$count} notificação(ões) antiga(s) arquivada(s).",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error cleaning up notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar notificações.'
            ], 500);
        }
    }

    /**
     * Exportar notificações do usuário
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'json');
            $filters = $this->getFilters($request);
            
            $notifications = NotificationService::getUserNotifications(Auth::id(), $filters);
            
            $data = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'category' => $notification->category,
                    'priority' => $notification->priority,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    'is_read' => $notification->isReadBy(Auth::id()),
                    'read_at' => $notification->reads->first()?->read_at?->format('Y-m-d H:i:s')
                ];
            });
            
            if ($format === 'csv') {
                return $this->exportToCsv($data);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'total' => $data->count()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error exporting notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao exportar notificações.'
            ], 500);
        }
    }

    // Métodos auxiliares privados
    private function getFilters(Request $request): array
    {
        $filters = [];
        
        if ($request->filled('type')) {
            $filters['type'] = $request->type;
        }
        
        if ($request->filled('category')) {
            $filters['category'] = $request->category;
        }
        
        if ($request->filled('priority')) {
            $filters['priority'] = $request->priority;
        }
        
        if ($request->filled('status')) {
            $filters['unread_only'] = $request->status === 'unread';
        }
        
        if ($request->filled('limit')) {
            $filters['limit'] = min((int) $request->limit, 100);
        }
        
        return $filters;
    }

    private function getUserStats($userId): array
    {
        return [
            'total' => Notification::forUser($userId)->count(),
            'unread' => NotificationService::getUnreadCount($userId),
            'read' => Notification::forUser($userId)->readForUser($userId)->count(),
            'starred' => NotificationRead::where('user_id', $userId)
                                       ->where('is_starred', true)
                                       ->count(),
            'archived' => NotificationRead::where('user_id', $userId)
                                        ->where('is_archived', true)
                                        ->count(),
            'this_week' => Notification::forUser($userId)
                                     ->where('created_at', '>=', now()->startOfWeek())
                                     ->count(),
            'quiz_alerts' => QuizAlert::forUser($userId)->count(),
            'quiz_unread' => QuizAlert::forUser($userId)->unread()->count()
        ];
    }

    private function exportToCsv($data)
    {
        $filename = 'notificacoes_' . Auth::id() . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}"
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalhos
            fputcsv($file, ['ID', 'Título', 'Mensagem', 'Tipo', 'Categoria', 'Prioridade', 'Criado em', 'Lida', 'Lida em']);
            
            // Dados
            foreach ($data as $row) {
                fputcsv($file, [
                    $row['id'],
                    $row['title'],
                    $row['message'],
                    $row['type'],
                    $row['category'],
                    $row['priority'],
                    $row['created_at'],
                    $row['is_read'] ? 'Sim' : 'Não',
                    $row['read_at'] ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}