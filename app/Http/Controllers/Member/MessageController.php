<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibir lista de mensagens do usuário
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $messages = Message::where(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id);
        })
        ->with(['sender', 'recipient'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('member.messages.index', compact('messages'));
    }

    /**
     * Exibir formulário para compor nova mensagem
     */
    public function compose(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())
                    ->where('ativo', true)
                    ->orderBy('name')
                    ->get();
        
        $recipient_id = $request->get('to');
        
        return view('member.messages.compose', compact('users', 'recipient_id'));
    }

    /**
     * Enviar nova mensagem
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'recipient_id.required' => 'Selecione um destinatário.',
            'recipient_id.exists' => 'Destinatário inválido.',
            'subject.required' => 'O assunto é obrigatório.',
            'subject.max' => 'O assunto não pode ter mais de 255 caracteres.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.max' => 'A mensagem não pode ter mais de 5000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $request->recipient_id,
                'subject' => $request->subject,
                'message' => $request->message,
                'is_read' => false,
            ]);

            Log::info('Mensagem enviada', [
                'message_id' => $message->id,
                'sender_id' => Auth::id(),
                'recipient_id' => $request->recipient_id
            ]);

            return redirect()->route('member.messages.index')
                           ->with('success', 'Mensagem enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao enviar mensagem. Tente novamente.')
                           ->withInput();
        }
    }

    /**
     * Exibir mensagem específica
     */
    public function show(Message $message)
    {
        $user = Auth::user();
        
        // Verificar se o usuário tem permissão para ver a mensagem
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Você não tem permissão para ver esta mensagem.');
        }
        
        // Marcar como lida se for o destinatário
        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }
        
        return view('member.messages.show', compact('message'));
    }

    /**
     * Responder a uma mensagem
     */
    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();
        
        // Verificar se o usuário tem permissão para responder
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Você não tem permissão para responder esta mensagem.');
        }
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000',
        ], [
            'message.required' => 'A mensagem é obrigatória.',
            'message.max' => 'A mensagem não pode ter mais de 5000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Determinar o destinatário (quem não é o remetente atual)
            $recipient_id = ($message->sender_id === $user->id) 
                          ? $message->recipient_id 
                          : $message->sender_id;
            
            $reply = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipient_id,
                'subject' => 'Re: ' . $message->subject,
                'message' => $request->message,
                'parent_id' => $message->id,
                'is_read' => false,
            ]);

            Log::info('Resposta enviada', [
                'reply_id' => $reply->id,
                'original_message_id' => $message->id,
                'sender_id' => $user->id
            ]);

            return redirect()->route('member.messages.show', $message)
                           ->with('success', 'Resposta enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar resposta: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao enviar resposta. Tente novamente.')
                           ->withInput();
        }
    }

    /**
     * Excluir mensagem
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();
        
        // Verificar se o usuário tem permissão para excluir
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir esta mensagem.');
        }
        
        try {
            $message->delete();
            
            Log::info('Mensagem excluída', [
                'message_id' => $message->id,
                'user_id' => $user->id
            ]);
            
            return redirect()->route('member.messages.index')
                           ->with('success', 'Mensagem excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir mensagem: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Erro ao excluir mensagem. Tente novamente.');
        }
    }

    /**
     * Marcar mensagem como lida
     */
    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        
        // Verificar se é o destinatário
        if ($message->recipient_id !== $user->id) {
            return response()->json(['error' => 'Permissão negada'], 403);
        }
        
        try {
            $message->update(['is_read' => true, 'read_at' => now()]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro ao marcar mensagem como lida: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno'], 500);
        }
    }
}