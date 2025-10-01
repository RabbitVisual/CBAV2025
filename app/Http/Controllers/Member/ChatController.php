<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        $userRooms = $this->chatService->getUserRooms($userId);
        $availableRooms = ChatRoom::ativo()->where('tipo', 'publico')
            ->whereDoesntHave('participants', fn($q) => $q->where('user_id', $userId))
            ->get();
        $chatStats = $this->chatService->getChatStats($userId);

        return view('member.chat.index', compact('userRooms', 'availableRooms', 'chatStats'));
    }

    public function show($roomId)
    {
        try {
            $userId = Auth::id();
            $room = ChatRoom::findOrFail($roomId);

            if (!$room->isUserParticipant($userId)) {
                $this->chatService->addParticipant($room, $userId, 'participante');
            }

            $messages = $this->chatService->getRoomMessages($roomId, $userId);
            $participants = $room->participants()->with('user')->where('ativo', true)->get();
            $userRooms = $this->chatService->getUserRooms($userId);

            return view('member.chat.show', compact('room', 'messages', 'participants', 'userRooms'));
        } catch (\Exception $e) {
            return redirect()->route('member.chat.index')->with('error', 'Erro ao acessar a sala: ' . $e->getMessage());
        }
    }

    public function join(ChatRoom $room)
    {
        try {
            $this->chatService->addParticipant($room, Auth::id(), 'participante');
            return redirect()->route('member.chat.show', $room->id)->with('success', 'Você entrou na sala com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('member.chat.index')->with('error', 'Não foi possível entrar na sala: ' . $e->getMessage());
        }
    }

    public function leave(ChatRoom $room)
    {
        $participant = $room->participants()->where('user_id', Auth::id())->first();
        if ($participant) {
            $this->chatService->removeParticipant($participant);
        }
        return redirect()->route('member.chat.index')->with('success', 'Você saiu da sala.');
    }

    public function send(Request $request, $roomId)
    {
        $data = $request->validate(['mensagem' => 'required|string|max:1000']);
        $message = $this->chatService->sendMessage($roomId, Auth::id(), $data['mensagem'], 'texto', $request->file('arquivo'));

        if ($message) {
            return response()->json(['success' => true, 'message' => $message->load('user')]);
        }
        return response()->json(['success' => false, 'message' => 'Não foi possível enviar a mensagem.'], 403);
    }

    public function messages($roomId)
    {
        $messages = $this->chatService->getRoomMessages($roomId, Auth::id());
        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function stats()
    {
        $stats = $this->chatService->getChatStats(Auth::id());
        return response()->json(['success' => true, 'stats' => $stats]);
    }
}