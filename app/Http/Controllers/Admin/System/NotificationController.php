<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
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
        $this->middleware('can:notifications.access');
    }

    /**
     * Exibir lista de notificações
     */
    public function index(Request $request)
    {
        $query = Notification::with(['user', 'sender']);

        // Aplicar filtros
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

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estatísticas
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::whereNull('read_at')->count(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'this_week' => Notification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.system.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create()
    {
        $users = User::where('ativo', true)->orderBy('name')->get();
        $membros = Membro::where('ativo', true)->orderBy('nome')->get();
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();

        return view('admin.system.notifications.create', compact('users', 'membros', 'ministerios'));
    }

    /**
     * Armazenar nova notificação
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'type' => 'required|in:info,success,warning,error',
            'priority' => 'required|in:low,normal,high,urgent',
            'recipient_type' => 'required|in:user,member,ministry,all',
            'recipient_id' => 'nullable|integer',
            'scheduled_for' => 'nullable|date|after:now',
            'channels' => 'array',
            'channels.*' => 'in:database,email,sms,push',
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.max' => 'A mensagem não pode ter mais de 5000 caracteres.',
            'type.required' => 'O tipo é obrigatório.',
            'type.in' => 'Tipo inválido.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'Prioridade inválida.',
            'recipient_type.required' => 'O tipo de destinatário é obrigatório.',
            'recipient_type.in' => 'Tipo de destinatário inválido.',
            'scheduled_for.date' => 'Data de agendamento inválida.',
            'scheduled_for.after' => 'A data de agendamento deve ser futura.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $notificationData = [
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'recipient_type' => $request->recipient_type,
                'recipient_id' => $request->recipient_id,
                'scheduled_for' => $request->scheduled_for,
                'channels' => $request->channels ?? ['database'],
                'sender_id' => Auth::id(),
                'data' => $request->data ? json_decode($request->data, true) : null,
            ];

            // Se for para um usuário específico
            if ($request->recipient_type === 'user' && $request->recipient_id) {
                $notificationData['user_id'] = $request->recipient_id;
            }

            // Se for para um membro específico
            if ($request->recipient_type === 'member' && $request->recipient_id) {
                $membro = Membro::find($request->recipient_id);
                if ($membro && $membro->email) {
                    $user = User::where('email', $membro->email)->first();
                    if ($user) {
                        $notificationData['user_id'] = $user->id;
                    }
                }
                $notificationData['data'] = array_merge(
                    $notificationData['data'] ?? [],
                    ['member_id' => $request->recipient_id]
                );
            }

            $notification = Notification::create($notificationData);

            // Se não foi agendada, enviar imediatamente
            if (!$request->scheduled_for) {
                NotificationService::send($notification);
            }

            Log::info('Notificação criada', [
                'notification_id' => $notification->id,
                'sender_id' => Auth::id(),
                'recipient_type' => $request->recipient_type
            ]);

            return redirect()->route('admin.system.notifications.index')
                           ->with('success', 'Notificação criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao criar notificação. Tente novamente.')
                           ->withInput();
        }
    }

    /**
     * Exibir notificação específica
     */
    public function show(Notification $notificacao)
    {
        $notificacao->load(['user', 'sender']);
        return view('admin.system.notifications.show', compact('notificacao'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(Notification $notificacao)
    {
        $users = User::where('ativo', true)->orderBy('name')->get();
        $membros = Membro::where('ativo', true)->orderBy('nome')->get();
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();

        return view('admin.system.notifications.edit', compact('notificacao', 'users', 'membros', 'ministerios'));
    }

    /**
     * Atualizar notificação
     */
    public function update(Request $request, Notification $notificacao)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'type' => 'required|in:info,success,warning,error',
            'priority' => 'required|in:low,normal,high,urgent',
            'recipient_type' => 'required|in:user,member,ministry,all',
            'recipient_id' => 'nullable|integer',
            'scheduled_for' => 'nullable|date|after:now',
            'channels' => 'array',
            'channels.*' => 'in:database,email,sms,push',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $notificationData = [
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'recipient_type' => $request->recipient_type,
                'recipient_id' => $request->recipient_id,
                'scheduled_for' => $request->scheduled_for,
                'channels' => $request->channels ?? ['database'],
                'data' => $request->data ? json_decode($request->data, true) : null,
            ];

            $notificacao->update($notificationData);

            Log::info('Notificação atualizada', [
                'notification_id' => $notificacao->id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('admin.system.notifications.index')
                           ->with('success', 'Notificação atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar notificação: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao atualizar notificação. Tente novamente.')
                           ->withInput();
        }
    }

    /**
     * Excluir notificação
     */
    public function destroy(Notification $notificacao)
    {
        try {
            $notificacao->delete();

            Log::info('Notificação excluída', [
                'notification_id' => $notificacao->id,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('admin.system.notifications.index')
                           ->with('success', 'Notificação excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir notificação: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao excluir notificação. Tente novamente.');
        }
    }

    /**
     * Enviar notificação
     */
    public function send(Notification $notificacao)
    {
        try {
            NotificationService::send($notificacao);

            $notificacao->update(['sent_at' => now()]);

            Log::info('Notificação enviada', [
                'notification_id' => $notificacao->id,
                'sent_by' => Auth::id()
            ]);

            return redirect()->back()
                           ->with('success', 'Notificação enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao enviar notificação. Tente novamente.');
        }
    }

    /**
     * Testar notificação
     */
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $testNotification = Notification::create([
                'title' => '[TESTE] ' . $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'priority' => 'normal',
                'recipient_type' => 'user',
                'user_id' => Auth::id(),
                'sender_id' => Auth::id(),
                'channels' => ['database'],
                'data' => ['test' => true],
            ]);

            NotificationService::send($testNotification);

            return response()->json([
                'success' => true,
                'message' => 'Notificação de teste enviada com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de teste: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar notificação de teste.'
            ], 500);
        }
    }
}
