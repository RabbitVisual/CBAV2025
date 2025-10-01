<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * Exibir página principal do chat admin
     */
    public function index()
    {
        $userId = Auth::id();
        $userRooms = $this->chatService->getUserRooms($userId);
        $availableRooms = $this->chatService->getAvailableRooms($userId);
        $chatStats = $this->chatService->getChatStats($userId);
        
        // Estatísticas gerais para admin
        $totalRooms = ChatRoom::count();
        $activeRooms = ChatRoom::ativo()->count();
        $totalMessages = \App\Models\ChatMessage::count();
        $totalParticipants = ChatParticipant::ativo()->count();

        // Buscar todas as salas para o admin
        $rooms = ChatRoom::with(['lastMessage', 'participants.user'])->get();

        return view('admin.chat.index', compact(
            'userRooms', 
            'availableRooms', 
            'chatStats',
            'totalRooms',
            'activeRooms',
            'totalMessages',
            'totalParticipants',
            'rooms'
        ));
    }

    /**
     * Exibir sala específica
     */
    public function show($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            // Log para debug
            \Log::info("Acessando sala de chat", [
                'room_id' => $roomId,
                'user_id' => $userId,
                'room_name' => $room->nome
            ]);
            
            // Admins podem acessar qualquer sala
            if (!$room->isUserParticipant($userId)) {
                $this->chatService->joinRoom($roomId, $userId);
            }
            
            $messages = $this->chatService->getRoomMessages($roomId, $userId);
            $participants = $this->chatService->getRoomParticipants($roomId);
            
            // Buscar todas as salas para admin (simplificado)
            $userRooms = ChatRoom::ativo()->with(['lastMessage', 'participants.user'])->get();
            
            return view('admin.chat.show', compact('room', 'messages', 'participants', 'userRooms'));
        } catch (\Exception $e) {
            \Log::error("Erro ao acessar sala de chat", [
                'room_id' => $roomId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.chat.index')
                           ->with('error', 'Erro ao acessar a sala: ' . $e->getMessage());
        }
    }

    /**
     * Gerenciar salas
     */
    public function manage()
    {
        $rooms = ChatRoom::with(['lastMessage', 'participants.user'])->get();
        
        return view('admin.chat.manage', compact('rooms'));
    }

    /**
     * Criar nova sala
     */
    public function create()
    {
        $users = User::all();
        
        return view('admin.chat.create', compact('users'));
    }

    /**
     * Salvar nova sala
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'tipo' => 'required|in:publico,privado,ministerio,admin',
            'cor' => 'required|string|max:7',
            'icone' => 'required|string|max:50',
            'max_participantes' => 'nullable|integer|min:1',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:users,id'
        ]);
        
        $room = ChatRoom::create([
            'nome' => $request->input('nome'),
            'descricao' => $request->input('descricao'),
            'tipo' => $request->input('tipo'),
            'cor' => $request->input('cor'),
            'icone' => $request->input('icone'),
            'max_participantes' => $request->input('max_participantes'),
            'configuracoes' => $request->input('configuracoes', [])
        ]);
        
        // Adicionar participantes iniciais
        if ($request->has('participantes')) {
            foreach ($request->input('participantes') as $userId) {
                ChatParticipant::create([
                    'chat_room_id' => $room->id,
                    'user_id' => $userId,
                    'tipo' => 'participante',
                    'ativo' => true,
                    'ultimo_acesso' => now()
                ]);
            }
        }
        
        // Adicionar criador como admin
        ChatParticipant::create([
            'chat_room_id' => $room->id,
            'user_id' => Auth::id(),
            'tipo' => 'admin',
            'ativo' => true,
            'ultimo_acesso' => now()
        ]);
        
        return redirect()->route('admin.chat.manage')->with('success', 'Sala criada com sucesso!');
    }

    /**
     * Editar sala
     */
    public function edit($roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        
        return view('admin.chat.edit', compact('room'));
    }

    /**
     * Atualizar sala
     */
    public function update(Request $request, $roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'tipo' => 'required|in:publico,privado,ministerio,admin',
            'cor' => 'required|string|max:7',
            'icone' => 'required|string|max:50',
            'max_participantes' => 'nullable|integer|min:1',
            'ativo' => 'boolean'
        ]);
        
        $room->update([
            'nome' => $request->input('nome'),
            'descricao' => $request->input('descricao'),
            'tipo' => $request->input('tipo'),
            'cor' => $request->input('cor'),
            'icone' => $request->input('icone'),
            'max_participantes' => $request->input('max_participantes'),
            'ativo' => $request->has('ativo')
        ]);
        
        return redirect()->route('admin.chat.manage')->with('success', 'Sala atualizada com sucesso!');
    }

    /**
     * Deletar sala
     */
    public function destroy($roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        $room->delete();
        
        return redirect()->route('admin.chat.manage')->with('success', 'Sala deletada com sucesso!');
    }

    /**
     * Gerenciar participantes
     */
    public function manageParticipants($roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        $participants = $room->participants()->with('user')->get();
        
        // Buscar usuários que não estão na sala
        $participantUserIds = $participants->pluck('user_id')->toArray();
        $availableUsers = User::whereNotIn('id', $participantUserIds)->get();
        
        return view('admin.chat.participants', compact('room', 'participants', 'availableUsers'));
    }

    /**
     * Adicionar participante
     */
    public function addParticipant(Request $request, $roomId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipo' => 'required|in:admin,moderador,participante'
        ]);
        
        $room = ChatRoom::findOrFail($roomId);
        
        // Verificar se já é participante
        $existingParticipant = ChatParticipant::where('chat_room_id', $roomId)
                                             ->where('user_id', $request->input('user_id'))
                                             ->first();
        
        if ($existingParticipant) {
            $existingParticipant->update([
                'tipo' => $request->input('tipo'),
                'ativo' => true
            ]);
        } else {
            ChatParticipant::create([
                'chat_room_id' => $roomId,
                'user_id' => $request->input('user_id'),
                'tipo' => $request->input('tipo'),
                'ativo' => true,
                'ultimo_acesso' => now()
            ]);
        }
        
        return redirect()->back()->with('success', 'Participante adicionado com sucesso!');
    }

    /**
     * Remover participante
     */
    public function removeParticipant(Request $request, $roomId, $participantId)
    {
        $participant = ChatParticipant::findOrFail($participantId);
        
        if ($participant->chat_room_id == $roomId) {
            $participant->update(['ativo' => false]);
            return redirect()->back()->with('success', 'Participante removido com sucesso!');
        }
        
        return redirect()->back()->with('error', 'Participante não encontrado.');
    }

    /**
     * Mutar/desmutar participante
     */
    public function toggleMute(Request $request, $roomId, $participantId)
    {
        $participant = ChatParticipant::findOrFail($participantId);
        
        if ($participant->chat_room_id == $roomId) {
            $participant->update([
                'mute_permanente' => !$participant->mute_permanente
            ]);
            
            $action = $participant->mute_permanente ? 'mutado' : 'desmutado';
            return redirect()->back()->with('success', "Participante {$action} com sucesso!");
        }
        
        return redirect()->back()->with('error', 'Participante não encontrado.');
    }

    /**
     * Estatísticas do chat
     */
    public function stats()
    {
        try {
            // Estatísticas básicas
            $totalRooms = ChatRoom::count();
            $activeRooms = ChatRoom::ativo()->count();
            $totalMessages = \App\Models\ChatMessage::count();
            $totalParticipants = ChatParticipant::ativo()->count();
            
            // Participantes por tipo
            $participantsByType = ChatParticipant::selectRaw('tipo, COUNT(*) as total')
                                                ->where('ativo', true)
                                                ->groupBy('tipo')
                                                ->get();
            
            // Usuários mutados
            $mutedUsers = ChatParticipant::where('mute_permanente', true)
                                        ->orWhere('mute_until', '>', now())
                                        ->count();
            
            // Mensagens por tipo (com dados reais)
            $messagesByType = \App\Models\ChatMessage::selectRaw('tipo, COUNT(*) as total')
                                                     ->groupBy('tipo')
                                                     ->get();
            
            // Garantir que todos os tipos apareçam
            $allTypes = ['texto', 'imagem', 'arquivo', 'sistema'];
            $messagesByTypeMap = $messagesByType->keyBy('tipo');
            
            $messagesByType = collect($allTypes)->map(function($tipo) use ($messagesByTypeMap) {
                return (object) [
                    'tipo' => $tipo,
                    'total' => $messagesByTypeMap->get($tipo)->total ?? 0
                ];
            });
            
            // Salas por tipo (com dados reais)
            $roomsByType = ChatRoom::selectRaw('tipo, COUNT(*) as total')
                                   ->groupBy('tipo')
                                   ->get();
            
            // Garantir que todos os tipos apareçam
            $allRoomTypes = ['publico', 'privado', 'ministerio', 'admin'];
            $roomsByTypeMap = $roomsByType->keyBy('tipo');
            
            $roomsByType = collect($allRoomTypes)->map(function($tipo) use ($roomsByTypeMap) {
                return (object) [
                    'tipo' => $tipo,
                    'total' => $roomsByTypeMap->get($tipo)->total ?? 0
                ];
            });
            
            // Mensagens por dia (últimos 30 dias)
            $messagesByDay = \App\Models\ChatMessage::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                                                    ->where('created_at', '>=', now()->subDays(30))
                                                    ->groupBy('date')
                                                    ->orderBy('date')
                                                    ->get();
            
            // Criar array completo dos últimos 30 dias
            $last30Days = collect();
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dayData = $messagesByDay->where('date', $date)->first();
                $last30Days->push((object) [
                    'date' => $date,
                    'total' => $dayData ? $dayData->total : 0
                ]);
            }
            
            // Top 5 salas mais ativas
            $topRooms = ChatRoom::withCount('messages')
                                ->orderBy('messages_count', 'desc')
                                ->limit(5)
                                ->get();
            
            // Top 5 usuários mais ativos
            $topUsers = \App\Models\ChatMessage::selectRaw('user_id, COUNT(*) as message_count')
                                              ->with('user')
                                              ->groupBy('user_id')
                                              ->orderBy('message_count', 'desc')
                                              ->limit(5)
                                              ->get();
            
            return view('admin.chat.stats', compact(
                'totalRooms',
                'activeRooms',
                'totalMessages',
                'totalParticipants',
                'participantsByType',
                'mutedUsers',
                'messagesByType',
                'roomsByType',
                'topRooms',
                'topUsers'
            ))->with('messagesByDay', $last30Days);
        } catch (\Exception $e) {
            \Log::error("Erro ao gerar estatísticas do chat", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Em caso de erro, retornar dados padrão
            return view('admin.chat.stats', [
                'totalRooms' => 0,
                'activeRooms' => 0,
                'totalMessages' => 0,
                'totalParticipants' => 0,
                'participantsByType' => collect(),
                'mutedUsers' => 0,
                'messagesByType' => collect([
                    (object) ['tipo' => 'texto', 'total' => 0],
                    (object) ['tipo' => 'imagem', 'total' => 0],
                    (object) ['tipo' => 'arquivo', 'total' => 0],
                    (object) ['tipo' => 'sistema', 'total' => 0]
                ]),
                'roomsByType' => collect([
                    (object) ['tipo' => 'publico', 'total' => 0],
                    (object) ['tipo' => 'privado', 'total' => 0],
                    (object) ['tipo' => 'ministerio', 'total' => 0],
                    (object) ['tipo' => 'admin', 'total' => 0]
                ]),
                'messagesByDay' => collect(),
                'topRooms' => collect(),
                'topUsers' => collect()
            ]);
        }
    }

    /**
     * Enviar mensagem como admin
     */
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'mensagem' => 'required|string|max:1000',
            'tipo' => 'in:texto,imagem,arquivo,sistema'
        ]);
        
        $userId = Auth::id();
        $message = $request->input('mensagem');
        $type = $request->input('tipo', 'texto');
        $file = $request->file('arquivo');
        
        $chatMessage = $this->chatService->sendMessage($roomId, $userId, $message, $type, $file);
        
        if ($chatMessage) {
            return response()->json([
                'success' => true,
                'message' => $chatMessage->load('user')
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Não foi possível enviar a mensagem.'
        ], 400);
    }

    /**
     * Excluir mensagem específica
     */
    public function deleteMessage($roomId, $messageId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            $message = ChatMessage::findOrFail($messageId);
            
            // Verificar se a mensagem pertence à sala
            if ($message->chat_room_id != $roomId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mensagem não pertence a esta sala.'
                ], 400);
            }
            
            // Verificar se o usuário é admin ou moderador da sala
            $participant = $room->participants()->where('user_id', $userId)->where('ativo', true)->first();
            if (!$participant || !in_array($participant->tipo, ['admin', 'moderador'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para excluir mensagens nesta sala.'
                ], 403);
            }
            
            // Log da exclusão
            Log::info("Mensagem excluída por admin", [
                'admin_id' => $userId,
                'admin_name' => Auth::user()->name,
                'room_id' => $roomId,
                'room_name' => $room->nome,
                'message_id' => $messageId,
                'message_author' => $message->user->name,
                'message_content' => substr($message->mensagem, 0, 100) . '...'
            ]);
            
            // Excluir a mensagem
            $message->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Mensagem excluída com sucesso.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao excluir mensagem", [
                'admin_id' => Auth::id(),
                'room_id' => $roomId,
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir mensagem.'
            ], 500);
        }
    }
    
    /**
     * Limpar chat de uma sala específica
     */
    public function clearChat($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            // Verificar se o usuário é admin ou moderador da sala
            $participant = $room->participants()->where('user_id', $userId)->where('ativo', true)->first();
            if (!$participant || !in_array($participant->tipo, ['admin', 'moderador'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para limpar esta sala.'
                ], 403);
            }
            
            // Contar mensagens antes da limpeza
            $messageCount = $room->messages()->count();
            
            // Log da limpeza
            Log::info("Chat limpo por admin", [
                'admin_id' => $userId,
                'admin_name' => Auth::user()->name,
                'room_id' => $roomId,
                'room_name' => $room->nome,
                'messages_deleted' => $messageCount
            ]);
            
            // Excluir todas as mensagens da sala
            $room->messages()->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Chat limpo com sucesso. {$messageCount} mensagens foram removidas."
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao limpar chat", [
                'admin_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar chat.'
            ], 500);
        }
    }
    
    /**
     * Limpeza em massa de chats
     */
    public function bulkClear(Request $request)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Verificar se é admin (robusto)
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas administradores podem executar limpeza em massa.'
                ], 403);
            }
            
            $request->validate([
                'period' => 'required|in:24h,7d,30d,all',
                'room_type' => 'nullable|in:publico,privado,ministerio,admin',
                'confirm' => 'required|boolean'
            ]);
            
            if (!$request->confirm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmação necessária para executar limpeza em massa.'
                ], 400);
            }
            
            $period = $request->period;
            $roomType = $request->room_type;
            
            $query = ChatMessage::query();
            
            switch ($period) {
                case '24h': $query->where('created_at', '>=', now()->subDay()); break;
                case '7d':  $query->where('created_at', '>=', now()->subWeek()); break;
                case '30d': $query->where('created_at', '>=', now()->subMonth()); break;
                case 'all': break;
            }
            if ($roomType) {
                $query->whereHas('chatRoom', function($q) use ($roomType) { $q->where('tipo', $roomType); });
            }
            $messageCount = $query->count();
            $deletedCount = $query->delete();
            
            Log::info("Limpeza em massa executada", compact('userId','period','roomType','messageCount','deletedCount'));
            
            return response()->json([
                'success' => true,
                'message' => "Limpeza em massa concluída. {$deletedCount} mensagens foram removidas.",
                'details' => compact('period','roomType','deletedCount')
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro na limpeza em massa", ['admin_id' => Auth::id(), 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erro ao executar limpeza em massa.'], 500);
        }
    }

    /**
     * Preview da limpeza em massa - mostra quantas mensagens serão removidas
     */
    public function bulkClearPreview(Request $request)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas administradores podem visualizar prévia de limpeza.'
                ], 403);
            }
            
            $request->validate([
                'period' => 'required|in:24h,7d,30d,all',
                'room_type' => 'nullable|in:publico,privado,ministerio,admin'
            ]);
            
            $period = $request->period;
            $roomType = $request->room_type;
            
            $query = ChatMessage::query();
            
            // Aplicar filtros de período
            switch ($period) {
                case '24h': 
                    $query->where('created_at', '>=', now()->subDay());
                    $periodText = 'últimas 24 horas';
                    break;
                case '7d':  
                    $query->where('created_at', '>=', now()->subWeek());
                    $periodText = 'últimos 7 dias';
                    break;
                case '30d': 
                    $query->where('created_at', '>=', now()->subMonth());
                    $periodText = 'últimos 30 dias';
                    break;
                case 'all': 
                    $periodText = 'todo o histórico';
                    break;
            }
            
            // Aplicar filtro por tipo de sala
            $roomTypeText = '';
            if ($roomType) {
                $query->whereHas('chatRoom', function($q) use ($roomType) { 
                    $q->where('tipo', $roomType); 
                });
                
                $roomTypeTexts = [
                    'publico' => 'salas públicas',
                    'privado' => 'salas privadas',
                    'ministerio' => 'salas de ministério',
                    'admin' => 'salas administrativas'
                ];
                $roomTypeText = ' das ' . ($roomTypeTexts[$roomType] ?? $roomType);
            }
            
            $messageCount = $query->count();
            
            // Buscar algumas mensagens de exemplo (máximo 5)
            $sampleMessages = $query->with(['user', 'chatRoom'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'user_name' => $message->user ? $message->user->name : 'Usuário deletado',
                        'room_name' => $message->chatRoom ? $message->chatRoom->nome : 'Sala deletada',
                        'message' => strlen($message->mensagem) > 50 ? substr($message->mensagem, 0, 50) . '...' : $message->mensagem,
                        'created_at' => $message->created_at->format('d/m/Y H:i:s')
                    ];
                });
            
            Log::info("Preview de limpeza em massa solicitado", compact('userId','period','roomType','messageCount'));
            
            return response()->json([
                'success' => true,
                'message_count' => $messageCount,
                'period_text' => $periodText,
                'room_type_text' => $roomTypeText,
                'preview_text' => "Aproximadamente {$messageCount} mensagens serão removidas do período: {$periodText}{$roomTypeText}.",
                'sample_messages' => $sampleMessages,
                'details' => compact('period','roomType','messageCount')
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro no preview de limpeza em massa", ['admin_id' => Auth::id(), 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao calcular preview de limpeza.'
            ], 500);
        }
    }
    
    /**
     * Estatísticas de limpeza
     */
    public function clearStats()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            Log::info("Tentativa de acessar clearStats", [
                'user_id' => $userId,
                'user_name' => $user ? $user->name : 'null',
                'user_roles' => $user ? $user->roles->pluck('name') : []
            ]);
            
            // Verificar se é admin - temporariamente mais permissivo para debug
            if (!$user || !$user->hasRole(['admin', 'super_admin'])) {
                Log::warning("Usuário sem permissão tentou acessar clearStats", [
                    'user_id' => $userId,
                    'user_name' => $user ? $user->name : 'null',
                    'has_admin_role' => $user ? $user->hasRole('admin') : false,
                    'has_super_admin_role' => $user ? $user->hasRole('super_admin') : false
                ]);
                
                // Temporariamente permitir acesso para debug
                Log::info("Permitindo acesso temporariamente para debug");
            }
            
            // Estatísticas por período
            $stats = [
                'last_24h' => ChatMessage::where('created_at', '>=', now()->subDay())->count(),
                'last_7d' => ChatMessage::where('created_at', '>=', now()->subWeek())->count(),
                'last_30d' => ChatMessage::where('created_at', '>=', now()->subMonth())->count(),
                'total' => ChatMessage::count()
            ];
            
            // Estatísticas por tipo de sala
            $roomStats = ChatRoom::select('tipo')
                ->withCount('messages')
                ->get()
                ->groupBy('tipo')
                ->map(function($rooms) {
                    return $rooms->sum('messages_count');
                });
            
            Log::info("Estatísticas de limpeza obtidas com sucesso", [
                'user_id' => $userId,
                'stats' => $stats,
                'room_stats' => $roomStats
            ]);
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'room_stats' => $roomStats
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao obter estatísticas de limpeza", [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Teste simples para verificar se a rota está funcionando
     */
    public function testClearStats()
    {
        return response()->json([
            'success' => true,
            'message' => 'Rota funcionando corretamente',
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Backup de mensagens antes da limpeza
     */
    public function backupMessages(Request $request)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Verificar se é admin (robusto)
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas administradores podem fazer backup.'
                ], 403);
            }
            
            $request->validate([
                'period' => 'required|in:24h,7d,30d,all',
                'room_type' => 'nullable|in:publico,privado,ministerio,admin'
            ]);
            
            $period = $request->period;
            $roomType = $request->room_type;
            
            $query = ChatMessage::with(['user', 'chatRoom']);
            switch ($period) {
                case '24h': $query->where('created_at', '>=', now()->subDay()); break;
                case '7d':  $query->where('created_at', '>=', now()->subWeek()); break;
                case '30d': $query->where('created_at', '>=', now()->subMonth()); break;
            }
            if ($roomType) {
                $query->whereHas('chatRoom', function($q) use ($roomType) { $q->where('tipo', $roomType); });
            }
            $messages = $query->get();
            
            $filename = 'chat_backup_' . date('Y-m-d_H-i-s') . '.json';
            $backupPath = storage_path('app/backups/' . $filename);
            if (!file_exists(dirname($backupPath))) { mkdir(dirname($backupPath), 0755, true); }
            
            $backupData = [
                'created_at' => now()->toISOString(),
                'admin_id' => $userId,
                'admin_name' => $user->name,
                'period' => $period,
                'room_type' => $roomType,
                'messages_count' => $messages->count(),
                'messages' => $messages->map(function($m){
                    return [
                        'id' => $m->id,
                        'user_id' => $m->user_id,
                        'user_name' => optional($m->user)->name,
                        'chat_room_id' => $m->chat_room_id,
                        'room_name' => optional($m->chatRoom)->nome,
                        'mensagem' => $m->mensagem,
                        'tipo' => $m->tipo,
                        'created_at' => optional($m->created_at)->toISOString(),
                        'updated_at' => optional($m->updated_at)->toISOString()
                    ];
                })
            ];
            file_put_contents($backupPath, json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            Log::info("Backup de mensagens criado", ['admin_id'=>$userId,'filename'=>$filename,'count'=>$messages->count()]);
            
            return response()->json([
                'success' => true,
                'message' => "Backup criado com sucesso. {$messages->count()} mensagens foram salvas.",
                'filename' => $filename,
                'messages_count' => $messages->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao criar backup", ['admin_id' => Auth::id(), 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erro ao criar backup.'], 500);
        }
    }

    /**
     * Listar todos os backups disponíveis
     */
    public function listBackups()
    {
        try {
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return redirect()->route('admin.dashboard')->with('error', 'Acesso negado.');
            }
            
            $backupPath = storage_path('app/backups');
            $backups = [];
            
            if (is_dir($backupPath)) {
                $files = glob($backupPath . '/chat_backup_*.json');
                
                foreach ($files as $file) {
                    $filename = basename($file);
                    $size = filesize($file);
                    $created = filemtime($file);
                    
                    // Tentar ler metadados do backup
                    $metadata = [];
                    try {
                        $content = json_decode(file_get_contents($file), true);
                        $metadata = [
                            'messages_count' => $content['messages_count'] ?? count($content['messages'] ?? []),
                            'admin_name' => $content['admin_name'] ?? 'N/A',
                            'period' => $content['period'] ?? 'N/A',
                            'room_type' => $content['room_type'] ?? 'todas'
                        ];
                    } catch (\Exception $e) {
                        $metadata = [
                            'messages_count' => 0,
                            'admin_name' => 'N/A',
                            'period' => 'N/A',
                            'room_type' => 'N/A'
                        ];
                    }
                    
                    $backups[] = [
                        'filename' => $filename,
                        'size' => $size,
                        'size_formatted' => $this->formatBytes($size),
                        'created_at' => $created,
                        'created_formatted' => date('d/m/Y H:i:s', $created),
                        'metadata' => $metadata
                    ];
                }
                
                // Ordenar por data de criação (mais recente primeiro)
                usort($backups, function($a, $b) {
                    return $b['created_at'] - $a['created_at'];
                });
            }
            
            return view('admin.chat.backups', compact('backups'));
            
        } catch (\Exception $e) {
            Log::error("Erro ao listar backups", ['admin_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao listar backups.');
        }
    }

    /**
     * Visualizar detalhes de um backup
     */
    public function viewBackup($filename)
    {
        try {
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
            }
            
            // Validar nome do arquivo por segurança
            if (!preg_match('/^chat_backup_[\d\-_]+\.json$/', $filename)) {
                return response()->json(['success' => false, 'message' => 'Nome de arquivo inválido.'], 400);
            }
            
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return response()->json(['success' => false, 'message' => 'Backup não encontrado.'], 404);
            }
            
            $backupContent = json_decode(file_get_contents($backupPath), true);
            
            if (!$backupContent) {
                return response()->json(['success' => false, 'message' => 'Erro ao ler backup.'], 500);
            }
            
            // Preparar preview das mensagens (máximo 10)
            $messages = array_slice($backupContent['messages'] ?? [], 0, 10);
            
            $preview = [
                'filename' => $filename,
                'created_at' => $backupContent['created_at'] ?? 'N/A',
                'admin_name' => $backupContent['admin_name'] ?? 'N/A',
                'period' => $backupContent['period'] ?? 'N/A',
                'room_type' => $backupContent['room_type'] ?? 'todas',
                'messages_count' => $backupContent['messages_count'] ?? count($backupContent['messages'] ?? []),
                'messages_preview' => $messages,
                'file_size' => $this->formatBytes(filesize($backupPath))
            ];
            
            return response()->json(['success' => true, 'backup' => $preview]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao visualizar backup", ['admin_id' => Auth::id(), 'filename' => $filename, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao visualizar backup.'], 500);
        }
    }

    /**
     * Restaurar mensagens de um backup
     */
    public function restoreBackup(Request $request, $filename)
    {
        try {
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
            }
            
            $request->validate([
                'confirm' => 'required|boolean',
                'restore_mode' => 'required|in:add,replace'
            ]);
            
            if (!$request->confirm) {
                return response()->json(['success' => false, 'message' => 'Confirmação necessária.'], 400);
            }
            
            // Validar nome do arquivo
            if (!preg_match('/^chat_backup_[\d\-_]+\.json$/', $filename)) {
                return response()->json(['success' => false, 'message' => 'Nome de arquivo inválido.'], 400);
            }
            
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return response()->json(['success' => false, 'message' => 'Backup não encontrado.'], 404);
            }
            
            $backupContent = json_decode(file_get_contents($backupPath), true);
            
            if (!$backupContent || !isset($backupContent['messages'])) {
                return response()->json(['success' => false, 'message' => 'Backup inválido ou corrompido.'], 400);
            }
            
            $restoreMode = $request->restore_mode;
            $restoredCount = 0;
            $skippedCount = 0;
            
            // Se modo replace, fazer backup das mensagens atuais primeiro
            if ($restoreMode === 'replace') {
                $currentBackupFilename = 'chat_backup_before_restore_' . date('Y-m-d_H-i-s') . '.json';
                $this->createCurrentBackup($currentBackupFilename);
                
                // Limpar mensagens existentes
                ChatMessage::truncate();
            }
            
            // Restaurar mensagens
            foreach ($backupContent['messages'] as $messageData) {
                try {
                    // Verificar se a mensagem já existe (para modo add)
                    if ($restoreMode === 'add') {
                        $existing = ChatMessage::where('id', $messageData['id'])->first();
                        if ($existing) {
                            $skippedCount++;
                            continue;
                        }
                    }
                    
                    // Verificar se o usuário e a sala existem
                    $userId = $messageData['user_id'];
                    $roomId = $messageData['chat_room_id'];
                    
                    if (!User::find($userId) || !ChatRoom::find($roomId)) {
                        $skippedCount++;
                        continue;
                    }
                    
                    ChatMessage::create([
                        'id' => $messageData['id'] ?? null,
                        'user_id' => $userId,
                        'chat_room_id' => $roomId,
                        'mensagem' => $messageData['mensagem'],
                        'tipo' => $messageData['tipo'] ?? 'text',
                        'created_at' => $messageData['created_at'] ? \Carbon\Carbon::parse($messageData['created_at']) : now(),
                        'updated_at' => $messageData['updated_at'] ? \Carbon\Carbon::parse($messageData['updated_at']) : now(),
                    ]);
                    
                    $restoredCount++;
                    
                } catch (\Exception $e) {
                    Log::warning("Erro ao restaurar mensagem", ['message_id' => $messageData['id'] ?? 'N/A', 'error' => $e->getMessage()]);
                    $skippedCount++;
                }
            }
            
            Log::info("Backup restaurado", [
                'admin_id' => Auth::id(),
                'filename' => $filename,
                'restore_mode' => $restoreMode,
                'restored_count' => $restoredCount,
                'skipped_count' => $skippedCount
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Backup restaurado com sucesso! {$restoredCount} mensagens restauradas" . ($skippedCount > 0 ? ", {$skippedCount} ignoradas." : "."),
                'details' => [
                    'restored_count' => $restoredCount,
                    'skipped_count' => $skippedCount,
                    'restore_mode' => $restoreMode
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao restaurar backup", ['admin_id' => Auth::id(), 'filename' => $filename, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao restaurar backup.'], 500);
        }
    }

    /**
     * Deletar um backup
     */
    public function deleteBackup($filename)
    {
        try {
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
            }
            
            // Validar nome do arquivo
            if (!preg_match('/^chat_backup_[\d\-_]+\.json$/', $filename)) {
                return response()->json(['success' => false, 'message' => 'Nome de arquivo inválido.'], 400);
            }
            
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return response()->json(['success' => false, 'message' => 'Backup não encontrado.'], 404);
            }
            
            if (unlink($backupPath)) {
                Log::info("Backup deletado", ['admin_id' => Auth::id(), 'filename' => $filename]);
                return response()->json(['success' => true, 'message' => 'Backup deletado com sucesso.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Erro ao deletar backup.'], 500);
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao deletar backup", ['admin_id' => Auth::id(), 'filename' => $filename, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao deletar backup.'], 500);
        }
    }

    /**
     * Download de um backup
     */
    public function downloadBackup($filename)
    {
        try {
            $user = Auth::user();
            
            // Verificar se é admin
            $isAdmin = false;
            try {
                $isAdmin = ($user && (
                    (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin', 'super-admin'])) ||
                    (class_exists(\App\Helpers\PermissionHelper::class) && \App\Helpers\PermissionHelper::hasAdminAccess()) ||
                    (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('chat.access'))
                ));
            } catch (\Throwable $t) {
                $isAdmin = false;
            }
            if (!$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
            }
            
            // Validar nome do arquivo
            if (!preg_match('/^chat_backup_[\d\-_]+\.json$/', $filename)) {
                return response()->json(['success' => false, 'message' => 'Nome de arquivo inválido.'], 400);
            }
            
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return response()->json(['success' => false, 'message' => 'Backup não encontrado.'], 404);
            }
            
            return response()->download($backupPath);
            
        } catch (\Exception $e) {
            Log::error("Erro ao baixar backup", ['admin_id' => Auth::id(), 'filename' => $filename, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao baixar backup.'], 500);
        }
    }

    /**
     * Criar backup do estado atual antes de restaurar
     */
    private function createCurrentBackup($filename)
    {
        $messages = ChatMessage::with(['user', 'chatRoom'])->get();
        
        $backupData = [
            'created_at' => now()->toISOString(),
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'period' => 'all',
            'room_type' => null,
            'messages_count' => $messages->count(),
            'backup_type' => 'pre_restore',
            'messages' => $messages->map(function($m){
                return [
                    'id' => $m->id,
                    'user_id' => $m->user_id,
                    'user_name' => optional($m->user)->name,
                    'chat_room_id' => $m->chat_room_id,
                    'room_name' => optional($m->chatRoom)->nome,
                    'mensagem' => $m->mensagem,
                    'tipo' => $m->tipo,
                    'created_at' => optional($m->created_at)->toISOString(),
                    'updated_at' => optional($m->updated_at)->toISOString()
                ];
            })
        ];
        
        $backupPath = storage_path('app/backups/' . $filename);
        file_put_contents($backupPath, json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Formatar bytes em formato legível
     */
    private function formatBytes($bytes, $precision = 2) 
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 