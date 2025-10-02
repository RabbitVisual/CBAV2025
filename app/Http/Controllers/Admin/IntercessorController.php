<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrayerRequest;
use App\Services\IntercessorService;
use Illuminate\Support\Facades\Auth;

class IntercessorController extends Controller
{
    protected $intercessorService;

    public function __construct(IntercessorService $intercessorService)
    {
        $this->intercessorService = $intercessorService;
    }

    public function index(Request $request)
    {
        $pedidos = $this->intercessorService->getPrayerRequests($request);
        return view('admin.intercessor.index', ['pedidos' => $pedidos]);
    }

    public function dashboard()
    {
        $data = $this->intercessorService->getAdminDashboardData();
        return view('admin.intercessor.dashboard', $data);
    }

    public function show(PrayerRequest $prayerRequest)
    {
        $prayerRequest->load(['user.profile', 'intercessions.user']);
        return view('admin.intercessor.show', ['pedido' => $prayerRequest]);
    }

    public function registrarIntercessao(Request $request, PrayerRequest $prayerRequest)
    {
        $data = $request->validate([
            'tipo_oracao' => 'required|in:individual,grupo,igreja',
            'tempo_oracao' => 'nullable|integer|min:1',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $this->intercessorService->registerIntercession($prayerRequest, $data, Auth::user());

        return redirect()->back()->with('success', 'Intercessão registrada com sucesso!');
    }

    public function atualizarStatus(Request $request, PrayerRequest $prayerRequest)
    {
        $data = $request->validate([
            'novo_status' => 'required|in:pendente,em_oracao,atendido',
        ]);

        $this->intercessorService->updateStatus($prayerRequest, $data['novo_status']);

        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $prayerRequest->delete();
        return redirect()->route('admin.intercessor.index')->with('success', 'Pedido excluído com sucesso!');
    }
}