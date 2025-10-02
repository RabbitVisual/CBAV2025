<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrayerRequest;
use App\Services\IntercessorService;
use Illuminate\Support\Facades\Auth;

class PrayerRequestController extends Controller
{
    protected $intercessorService;

    public function __construct(IntercessorService $intercessorService)
    {
        $this->intercessorService = $intercessorService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $pedidos = $this->intercessorService->getMemberPrayerRequests($user, $request);
        $pedidosCompartilhados = $this->intercessorService->getSharedPrayerRequests($user);

        $estatisticas = [
            'total' => $user->prayerRequests()->count(),
            'pendentes' => $user->prayerRequests()->where('status', 'pendente')->count(),
            'em_oracao' => $user->prayerRequests()->where('status', 'em_oracao')->count(),
            'atendidos' => $user->prayerRequests()->where('status', 'atendido')->count(),
        ];

        return view('member.pedidos-oracao.index', compact('pedidos', 'pedidosCompartilhados', 'estatisticas'));
    }

    public function create()
    {
        return view('member.pedidos-oracao.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $this->intercessorService->createPrayerRequest($data, Auth::user());
        return redirect()->route('member.prayer-requests.index')->with('success', 'Pedido de oração criado com sucesso!');
    }

    public function show(PrayerRequest $prayerRequest)
    {
        $prayerRequest->load('intercessions.user');
        $isProprietario = $prayerRequest->user_id === Auth::id();
        return view('member.pedidos-oracao.show', ['pedido' => $prayerRequest, 'isProprietario' => $isProprietario]);
    }

    public function edit(PrayerRequest $prayerRequest)
    {
        if ($prayerRequest->user_id !== Auth::id() || $prayerRequest->status !== 'pendente') {
            abort(403, 'Acesso negado.');
        }
        return view('member.pedidos-oracao.edit', ['pedido' => $prayerRequest]);
    }

    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        try {
            $data = $request->validate($this->rules());
            $this->intercessorService->updatePrayerRequest($prayerRequest, $data, Auth::user());
            return redirect()->route('member.prayer-requests.show', $prayerRequest)->with('success', 'Pedido de oração atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        try {
            $this->intercessorService->deletePrayerRequest($prayerRequest, Auth::user());
            return redirect()->route('member.prayer-requests.index')->with('success', 'Pedido de oração excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function marcarAtendido(PrayerRequest $prayerRequest)
    {
        try {
            $this->intercessorService->markAsAnswered($prayerRequest, Auth::user());
            return redirect()->route('member.prayer-requests.show', $prayerRequest)->with('success', 'Pedido marcado como atendido!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'categoria' => 'required|in:saude,familia,trabalho,espiritual,outros',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'anonimo' => 'boolean',
            'pode_compartilhar' => 'boolean'
        ];
    }
}