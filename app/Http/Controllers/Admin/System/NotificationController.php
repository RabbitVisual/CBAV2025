<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\User;
use App\Models\Ministerio;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('auth');
        // Adicionar middleware de permissão, se necessário
        // $this->middleware('can:notifications.access');
    }

    /**
     * Exibe a lista de notificações do administrador logado.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getUserNotifications($user);

        return view('admin.system.notifications.index', compact('notifications'));
    }

    /**
     * Mostra o formulário para criar uma nova notificação.
     */
    public function create()
    {
        $users = User::where('ativo', true)->orderBy('name')->get();
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();
        return view('admin.system.notifications.create', compact('users', 'ministerios'));
    }

    /**
     * Armazena uma nova notificação e a envia aos destinatários.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'recipient_type' => 'required|in:user,ministry,all',
            'user_id' => 'nullable|exists:users,id|required_if:recipient_type,user',
            'ministry_id' => 'nullable|exists:ministerios,id|required_if:recipient_type,ministry',
            'action_url' => 'nullable|url',
        ]);

        try {
            switch ($data['recipient_type']) {
                case 'user':
                    $user = User::findOrFail($data['user_id']);
                    $this->notificationService->sendToUser($user, $data['title'], $data['message'], $data['type'], $data['action_url']);
                    break;
                case 'ministry':
                    $ministerio = Ministerio::with('membros.user')->findOrFail($data['ministry_id']);
                    $users = $ministerio->membros->map(fn($membro) => $membro->user)->filter();
                    if ($users->isNotEmpty()) {
                        $this->notificationService->sendToMultipleUsers($users, $data['title'], $data['message'], $data['type'], $data['action_url']);
                    }
                    break;
                case 'all':
                    $this->notificationService->sendToAll($data['title'], $data['message'], $data['type'], $data['action_url']);
                    break;
            }

            return redirect()->route('admin.system.notifications.index')
                           ->with('success', 'Notificação enviada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar notificação: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Exibe uma notificação específica.
     */
    public function show(string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($notificationId);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return view('admin.system.notifications.show', compact('notification'));
    }

    /**
     * Exclui uma notificação.
     */
    public function destroy(string $notificationId)
    {
        $user = Auth::user();
        if ($this->notificationService->deleteNotification($user, $notificationId)) {
            return redirect()->route('admin.system.notifications.index')
                           ->with('success', 'Notificação excluída com sucesso.');
        }

        return back()->with('error', 'Não foi possível excluir a notificação.');
    }
}