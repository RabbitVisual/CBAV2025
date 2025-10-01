<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->middleware('auth');
    }

    /**
     * Exibir dashboard do chat
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            
            Log::info("Chat index - User ID: {$userId}");
            
            // Buscar todas as salas ativas
            $allRooms = ChatRoom::where('ativo', true)->get();
            Log::info("Total rooms found: " . $allRooms->count());
            
            // Buscar salas onde o usuário participa (ativas ou inativas)
            $userRooms = $allRooms->filter(function($room) use ($userId) {
                return $room->participants()->where('user_id', $userId)->exists();
            });
            
            // Buscar salas disponíveis (onde o usuário não participa)
            $availableRooms = $allRooms->filter(function($room) use ($userId) {
                return !$room->participants()->where('user_id', $userId)->exists();
            });
            
            Log::info("User rooms: " . $userRooms->count() . ", Available rooms: " . $availableRooms->count());
            
            // Estatísticas básicas
            $chatStats = [
                'total_rooms' => $userRooms->count(),
                'total_unread' => 0, // Simplificado por enquanto
                'total_messages' => ChatMessage::count(),
                'active_participants' => ChatParticipant::where('ativo', true)->count(),
                'active_rooms' => $allRooms->count()
            ];
            
            Log::info("Chat stats: " . json_encode($chatStats));
            Log::info("About to return view with data");

            return view('member.chat.index', compact('userRooms', 'availableRooms', 'chatStats'));
            
        } catch (\Exception $e) {
            Log::error("Erro no chat index", [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('member.chat.index', [
                'userRooms' => collect(),
                'availableRooms' => collect(),
                'chatStats' => [
                    'total_rooms' => 0,
                    'total_unread' => 0,
                    'total_messages' => 0,
                    'active_participants' => 0,
                    'active_rooms' => 0
                ]
            ]);
        }
    }

    /**
     * Exibir sala de chat específica
     */
    public function show($roomId)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Verificar se a sala existe
            $room = ChatRoom::find($roomId);
            if (!$room) {
                return redirect()->route('member.chat.index')
                    ->with('error', '❌ Sala não encontrada. A sala pode ter sido removida ou não existe.');
            }
            
            // Verificar se a sala está ativa
            if (!$room->ativo) {
                return redirect()->route('member.chat.index')
                    ->with('error', '❌ Esta sala está desativada e não pode ser acessada.');
            }
            
            // Verificar se o usuário pode acessar a sala
            $isParticipant = $room->participants()->where('user_id', $userId)->where('ativo', true)->exists();
            $isPublicRoom = $room->tipo === 'publico';
            
            // Se não é participante ativo, verificar permissões
            if (!$isParticipant) {
                $errorMessage = $this->validateRoomAccess($room, $user);
                if ($errorMessage) {
                    return redirect()->route('member.chat.index')->with('error', $errorMessage);
                }
                
                // Verificar se existe participação inativa
                $inactiveParticipant = $room->participants()->where('user_id', $userId)->where('ativo', false)->first();
                
                if ($inactiveParticipant) {
                    // Reativar participação inativa
                    $participantType = $this->determineParticipantType($room, $user);
                    $inactiveParticipant->update([
                        'ativo' => true,
                        'tipo' => $participantType,
                        'ultimo_acesso' => now()
                    ]);
                    
                    Log::info("Usuário reativou participação automaticamente", [
                        'user_id' => $userId,
                        'user_name' => $user->name,
                        'room_id' => $roomId,
                        'room_name' => $room->nome,
                        'participant_type' => $participantType
                    ]);
                } else {
                    // Criar nova participação
                    $participantType = $this->determineParticipantType($room, $user);
                    $room->participants()->create([
                        'user_id' => $userId,
                        'tipo' => $participantType,
                        'ativo' => true,
                        'ultimo_acesso' => now()
                    ]);
                    
                    Log::info("Usuário adicionado automaticamente à sala", [
                        'user_id' => $userId,
                        'user_name' => $user->name,
                        'room_id' => $roomId,
                        'room_name' => $room->nome,
                        'participant_type' => $participantType
                    ]);
                }
            }
            
            // Buscar mensagens (últimas 50)
            $messages = $room->messages()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->reverse();
            
            // Buscar participantes ativos
            $participants = $room->participants()
                ->with('user')
                ->where('ativo', true)
                ->orderBy('ultimo_acesso', 'desc')
                ->get();
            
            // Buscar salas do usuário para o sidebar
            $userRooms = ChatRoom::where('ativo', true)
                ->whereHas('participants', function($query) use ($userId) {
                    $query->where('user_id', $userId)->where('ativo', true);
                })
                ->with(['lastMessage', 'participants.user'])
                ->get();
            
            // Log de acesso
            Log::info("Usuário acessou sala de chat", [
                'user_id' => $userId,
                'user_name' => $user->name,
                'room_id' => $roomId,
                'room_name' => $room->nome,
                'messages_count' => $messages->count(),
                'participants_count' => $participants->count()
            ]);
            
            return view('member.chat.show', compact('room', 'messages', 'participants', 'userRooms'));
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Erro de banco de dados ao acessar sala", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage()
            ]);
            
            $errorMessage = $this->getDatabaseErrorMessage($e);
            return redirect()->route('member.chat.index')->with('error', $errorMessage);
            
        } catch (\Exception $e) {
            Log::error("Erro geral ao acessar sala", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('member.chat.index')
                ->with('error', '❌ Erro inesperado ao acessar a sala. Tente novamente ou contate o administrador.');
        }
    }

    /**
     * Entrar em uma sala
     */
    public function join($roomId)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Verificar se a sala existe
            $room = ChatRoom::find($roomId);
            if (!$room) {
                return redirect()->route('member.chat.index')
                    ->with('error', '❌ Sala não encontrada. A sala pode ter sido removida ou não existe.');
            }
            
            // Verificar se a sala está ativa
            if (!$room->ativo) {
                return redirect()->route('member.chat.index')
                    ->with('error', '❌ Esta sala está desativada e não aceita novos participantes.');
            }
            
            // Verificar se já é participante ativo
            $existingParticipant = $room->participants()->where('user_id', $userId)->where('ativo', true)->first();
            if ($existingParticipant) {
                return redirect()->route('member.chat.show', $roomId)
                    ->with('info', 'ℹ️ Você já é participante desta sala.');
            }
            
            // Verificar se existe participação inativa (usuário que saiu antes)
            $inactiveParticipant = $room->participants()->where('user_id', $userId)->where('ativo', false)->first();
            
            // Verificar tipo de sala e permissões
            $errorMessage = $this->validateRoomAccess($room, $user);
            if ($errorMessage) {
                return redirect()->route('member.chat.index')->with('error', $errorMessage);
            }
            
            // Verificar limite de participantes (se aplicável)
            $participantCount = $room->participants()->where('ativo', true)->count();
            if ($room->limite_participantes && $participantCount >= $room->limite_participantes) {
                return redirect()->route('member.chat.index')
                    ->with('error', '❌ Esta sala está cheia. Limite de ' . $room->limite_participantes . ' participantes atingido.');
            }
            
            // Determinar tipo de participante baseado no tipo de sala
            $participantType = $this->determineParticipantType($room, $user);
            
            // Se existe participação inativa, reativar
            if ($inactiveParticipant) {
                $inactiveParticipant->update([
                    'ativo' => true,
                    'tipo' => $participantType,
                    'ultimo_acesso' => now()
                ]);
                
                Log::info("Usuário reativou participação na sala", [
                    'user_id' => $userId,
                    'user_name' => $user->name,
                    'room_id' => $roomId,
                    'room_name' => $room->nome,
                    'participant_type' => $participantType
                ]);
                
                return redirect()->route('member.chat.show', $roomId)
                    ->with('success', '✅ Você reentrou na sala "' . $room->nome . '" com sucesso!');
            }
            
            // Criar nova participação
            $participant = $room->participants()->create([
                'user_id' => $userId,
                'tipo' => $participantType,
                'ativo' => true,
                'ultimo_acesso' => now()
            ]);
            
            // Log de sucesso
            Log::info("Usuário entrou na sala com sucesso", [
                'user_id' => $userId,
                'user_name' => $user->name,
                'room_id' => $roomId,
                'room_name' => $room->nome,
                'participant_type' => $participantType
            ]);
            
            return redirect()->route('member.chat.show', $roomId)
                ->with('success', '✅ Você entrou na sala "' . $room->nome . '" com sucesso!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Erro específico de banco de dados
            Log::error("Erro de banco de dados ao entrar na sala", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A'
            ]);
            
            $errorMessage = $this->getDatabaseErrorMessage($e);
            return redirect()->route('member.chat.index')->with('error', $errorMessage);
            
        } catch (\Exception $e) {
            Log::error("Erro geral ao entrar na sala", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('member.chat.index')
                ->with('error', '❌ Erro inesperado ao entrar na sala. Tente novamente ou contate o administrador.');
        }
    }
    
    /**
     * Validar acesso à sala
     */
    private function validateRoomAccess($room, $user)
    {
        try {
            // SALAS PÚBLICAS - Qualquer um pode entrar
            if ($room->tipo === 'publico') {
                return null; // Sem restrições
            }
            
            // SALAS PRIVADAS - Apenas com permissão específica
            if ($room->tipo === 'privado') {
                if ($user && method_exists($user, 'hasPermissionTo') && !$user->hasPermissionTo('chat.private.access')) {
                    return '🔒 Acesso negado. Esta é uma sala privada e você não tem permissão para acessá-la.';
                }
            }
            
            // SALAS DE MINISTÉRIO - Apenas membros do ministério específico
            if ($room->tipo === 'ministerio') {
                if ($room->ministerio_id && $user && method_exists($user, 'ministries')) {
                    try {
                        $hasMinistry = $user->ministries()->where('id', $room->ministerio_id)->exists();
                        if (!$hasMinistry) {
                            $ministryName = 'Específico';
                            try {
                                $ministry = \App\Models\Ministerio::find($room->ministerio_id);
                                if ($ministry) {
                                    $ministryName = $ministry->nome;
                                }
                            } catch (\Exception $e) {
                                // Se não conseguir buscar o ministério, usar nome padrão
                            }
                            return '⛪ Acesso negado. Esta sala é exclusiva para membros do ministério "' . $ministryName . '".';
                        }
                    } catch (\Exception $e) {
                        Log::warning("Erro ao verificar ministério do usuário", [
                            'user_id' => $user->id,
                            'room_id' => $room->id,
                            'error' => $e->getMessage()
                        ]);
                        return '⛪ Acesso negado. Esta sala é exclusiva para membros de um ministério específico.';
                    }
                } else {
                    return '⛪ Acesso negado. Esta sala é exclusiva para membros de ministérios específicos.';
                }
            }
            
            // SALAS ADMINISTRATIVAS - Apenas admins e super_admins
            if ($room->tipo === 'admin') {
                if ($user && method_exists($user, 'hasRole') && !$user->hasRole(['admin', 'super_admin'])) {
                    return '👑 Acesso negado. Esta sala é exclusiva para administradores.';
                }
            }
            
            // Verificar se a sala tem restrições de idade
            if (isset($room->idade_minima) && $room->idade_minima && $user && isset($user->idade) && $user->idade < $room->idade_minima) {
                return '📅 Acesso negado. Esta sala é exclusiva para pessoas com ' . $room->idade_minima . ' anos ou mais.';
            }
            
            // Verificar se a sala tem restrições de gênero
            if (isset($room->genero_especifico) && $room->genero_especifico && $user && isset($user->genero) && $user->genero !== $room->genero_especifico) {
                $generoText = $room->genero_especifico === 'M' ? 'masculino' : 'feminino';
                return '👥 Acesso negado. Esta sala é exclusiva para pessoas do gênero ' . $generoText . '.';
            }
            
            return null; // Sem erros
            
        } catch (\Exception $e) {
            Log::error("Erro ao validar acesso à sala", [
                'user_id' => $user ? $user->id : 'null',
                'room_id' => $room->id,
                'error' => $e->getMessage()
            ]);
            
            // Em caso de erro, permitir acesso (mais seguro)
            return null;
        }
    }
    
    /**
     * Determinar tipo de participante
     */
    private function determineParticipantType($room, $user)
    {
        try {
            // Se o usuário é admin, sempre será admin na sala
            if ($user && method_exists($user, 'hasRole') && $user->hasRole(['admin', 'super_admin'])) {
                return 'admin';
            }
            
            // Se é sala de ministério e o usuário é líder do ministério
            if ($room->tipo === 'ministerio' && $room->ministerio_id && method_exists($user, 'ministries')) {
                try {
                    $ministry = $user->ministries()->where('id', $room->ministerio_id)->first();
                    if ($ministry && isset($ministry->pivot) && $ministry->pivot->cargo === 'lider') {
                        return 'moderador';
                    }
                } catch (\Exception $e) {
                    // Se houver erro ao acessar ministérios, continuar com o padrão
                    Log::warning("Erro ao verificar ministério do usuário", [
                        'user_id' => $user->id,
                        'room_id' => $room->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Padrão para membros
            return 'participante';
            
        } catch (\Exception $e) {
            Log::error("Erro ao determinar tipo de participante", [
                'user_id' => $user ? $user->id : 'null',
                'room_id' => $room->id,
                'error' => $e->getMessage()
            ]);
            
            // Em caso de erro, retornar o tipo mais seguro
            return 'participante';
        }
    }
    
    /**
     * Obter mensagem de erro específica do banco de dados
     */
    private function getDatabaseErrorMessage($e)
    {
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
        
        // Erro de tipo truncado (campo enum)
        if (strpos($errorMessage, 'Data truncated for column \'tipo\'') !== false) {
            return '❌ Erro de configuração: Tipo de participante inválido. Contate o administrador.';
        }
        
        // Erro de chave duplicada
        if (strpos($errorMessage, 'Duplicate entry') !== false) {
            return '⚠️ Você já é participante desta sala.';
        }
        
        // Erro de chave estrangeira
        if (strpos($errorMessage, 'foreign key constraint') !== false) {
            return '❌ Erro de integridade: Sala ou usuário não encontrado.';
        }
        
        // Erro de conexão
        if (strpos($errorMessage, 'Connection') !== false) {
            return '❌ Erro de conexão com o banco de dados. Tente novamente em alguns instantes.';
        }
        
        // Erro genérico de banco
        return '❌ Erro de banco de dados. Tente novamente ou contate o administrador.';
    }

    /**
     * Sair de uma sala
     */
    public function leave($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            // Remover participação
            $room->participants()->where('user_id', $userId)->update(['ativo' => false]);
            
            return redirect()->route('member.chat.index')->with('success', 'Você saiu da sala com sucesso!');
            
        } catch (\Exception $e) {
            Log::error("Erro ao sair da sala", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('member.chat.index')->with('error', 'Erro ao sair da sala.');
        }
    }

    /**
     * Enviar mensagem
     */
    public function send(Request $request, $roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            $request->validate([
                'mensagem' => 'required|string|max:1000'
            ]);
            
            // Usar ChatService para verificar mute e outras validações
            $message = $this->chatService->sendMessage($roomId, $userId, $request->mensagem, 'texto');
            
            if (!$message) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Você não pode enviar mensagens nesta sala ou está mutado.'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'message' => $message->load('user')
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar mensagem", [
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Erro ao enviar mensagem.'], 500);
        }
    }

    /**
     * Marcar mensagem como lida
     */
    public function read($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            // Marcar mensagens como lidas
            $unreadMessages = $room->messages()
                ->whereDoesntHave('reads', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
            
            foreach ($unreadMessages as $message) {
                $message->reads()->create([
                    'user_id' => $userId
                ]);
            }
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao marcar como lida.'], 500);
        }
    }

    /**
     * Obter mensagens de uma sala
     */
    public function messages($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);
            
            // Verificar se é participante
            if (!$room->participants()->where('user_id', $userId)->where('ativo', true)->exists()) {
                return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
            }
            
            $messages = $room->messages()->with('user')->orderBy('created_at', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao obter mensagens.'], 500);
        }
    }

    /**
     * Obter estatísticas do chat
     */
    public function getChatStats()
    {
        try {
            $userId = Auth::id();
            
            $userRooms = ChatRoom::where('ativo', true)
                ->whereHas('participants', function($query) use ($userId) {
                    $query->where('user_id', $userId)->where('ativo', true);
                })
                ->count();
            
            $stats = [
                'total_rooms' => $userRooms,
                'total_unread' => 0, // Simplificado
                'total_messages' => ChatMessage::count(),
                'active_participants' => ChatParticipant::where('ativo', true)->count(),
                'active_rooms' => ChatRoom::where('ativo', true)->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }
} 