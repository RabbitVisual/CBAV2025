<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->middleware('auth');
        $this->middleware('admin.access'); // Ou uma permissão mais específica
    }

    public function index()
    {
        $data = $this->chatService->getAdminDashboardData(Auth::id());
        return view('admin.chat.index', $data);
    }

    public function show($roomId)
    {
        try {
            $room = ChatRoom::findOrFail($roomId);
            if (!$room->isUserParticipant(Auth::id())) {
                $this->chatService->addParticipant($room, Auth::id(), 'participante');
            }
            $messages = $this->chatService->getRoomMessages($roomId, Auth::id());
            $participants = $room->participants()->with('user')->get();
            $userRooms = $this->chatService->getUserRooms(Auth::id());
            return view('admin.chat.show', compact('room', 'messages', 'participants', 'userRooms'));
        } catch (\Exception $e) {
            return redirect()->route('admin.chat.index')->with('error', 'Erro ao acessar a sala: ' . $e->getMessage());
        }
    }

    public function manage()
    {
        $rooms = ChatRoom::with(['lastMessage', 'participants.user'])->get();
        return view('admin.chat.manage', compact('rooms'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.chat.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255', 'descricao' => 'nullable|string', 'tipo' => 'required|in:publico,privado,ministerio,admin',
            'cor' => 'nullable|string|max:7', 'icone' => 'nullable|string|max:50', 'max_participantes' => 'nullable|integer|min:1',
            'participantes' => 'nullable|array'
        ]);
        $this->chatService->createRoom($data, Auth::id());
        return redirect()->route('admin.chat.manage')->with('success', 'Sala criada com sucesso!');
    }

    public function edit(ChatRoom $room)
    {
        return view('admin.chat.edit', compact('room'));
    }

    public function update(Request $request, ChatRoom $room)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255', 'descricao' => 'nullable|string', 'tipo' => 'required|in:publico,privado,ministerio,admin',
            'cor' => 'nullable|string|max:7', 'icone' => 'nullable|string|max:50', 'max_participantes' => 'nullable|integer|min:1', 'ativo' => 'boolean'
        ]);
        $data['ativo'] = $request->has('ativo');
        $this->chatService->updateRoom($room, $data);
        return redirect()->route('admin.chat.manage')->with('success', 'Sala atualizada com sucesso!');
    }

    public function destroy(ChatRoom $room)
    {
        $this->chatService->deleteRoom($room);
        return redirect()->route('admin.chat.manage')->with('success', 'Sala deletada com sucesso!');
    }

    public function manageParticipants(ChatRoom $room)
    {
        $data = $this->chatService->getParticipantsForManagement($room);
        return view('admin.chat.participants', $data);
    }

    public function addParticipant(Request $request, ChatRoom $room)
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id', 'tipo' => 'required|in:admin,moderador,participante']);
        $this->chatService->addParticipant($room, $data['user_id'], $data['tipo']);
        return redirect()->back()->with('success', 'Participante adicionado com sucesso!');
    }

    public function removeParticipant(ChatParticipant $participant)
    {
        $this->chatService->removeParticipant($participant);
        return redirect()->back()->with('success', 'Participante removido com sucesso!');
    }

    public function toggleMute(ChatParticipant $participant)
    {
        $this->chatService->toggleMute($participant);
        $action = $participant->mute_permanente ? 'mutado' : 'desmutado';
        return redirect()->back()->with('success', "Participante {$action} com sucesso!");
    }

    public function stats()
    {
        $statistics = $this->chatService->getChatStatistics();
        return view('admin.chat.stats', $statistics);
    }
    
    public function sendMessage(Request $request, $roomId)
    {
        $data = $request->validate(['mensagem' => 'required|string|max:1000', 'tipo' => 'in:texto,imagem,arquivo,sistema']);
        $message = $this->chatService->sendMessage($roomId, Auth::id(), $data['mensagem'], $data['tipo'] ?? 'texto', $request->file('arquivo'));
        if ($message) {
            return response()->json(['success' => true, 'message' => $message->load('user')]);
        }
        return response()->json(['success' => false, 'message' => 'Não foi possível enviar a mensagem.'], 400);
    }
    
    public function deleteMessage(ChatMessage $message)
    {
        $deleted = $this->chatService->adminDeleteMessage($message, Auth::user());
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Mensagem excluída com sucesso.']);
        }
        return response()->json(['success' => false, 'message' => 'Você não tem permissão para excluir esta mensagem.'], 403);
    }

    public function clearChat(ChatRoom $room)
    {
        try {
            $count = $this->chatService->adminClearChat($room, Auth::user());
            return response()->json(['success' => true, 'message' => "Chat limpo com sucesso. {$count} mensagens foram removidas."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        }
    }
}