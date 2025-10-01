<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Transacao, Campanha};
use App\Services\FinanceService;

class DonationController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
        $this->middleware('auth')->except(['publicIndex', 'processPublic']);
    }

    // --- Rotas Públicas ---

    public function publicIndex()
    {
        $campanhas = $this->financeService->getActiveCampaignsForMember();
        return view('member.donations.public', compact('campanhas'));
    }

    public function processPublic(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255', 'email' => 'required|email',
            'valor' => 'required|numeric|min:0.01', 'campanha_id' => 'nullable|exists:campanhas,id',
            'gateway' => 'required|in:stripe,mercadopago,pix',
        ]);

        try {
            $transacao = $this->financeService->processPublicDonation($data);
            // A lógica de redirecionamento para o gateway seria chamada aqui.
            // Ex: return $this->gatewayRedirect($data['gateway'], $transacao);
            return redirect()->route('home')->with('success', 'Obrigado por sua doação! Você será redirecionado para o pagamento.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar doação: ' . $e->getMessage())->withInput();
        }
    }

    // --- Rotas de Membro ---

    public function index()
    {
        $user = Auth::user();
        if (!$user->membro) {
            return redirect()->route('member.dashboard')->with('error', 'Perfil de membro não encontrado.');
        }
        
        $estatisticas = $this->financeService->getMemberDonationStats($user->membro);
        $doacoes = $user->membro->transacoes()->with('campanha')->latest()->paginate(10);

        return view('member.donations.index', compact('doacoes', 'estatisticas'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        if (!$user->membro) {
            return redirect()->route('member.dashboard')->with('error', 'Perfil de membro não encontrado.');
        }

        $transacoes = $this->financeService->getMemberDonationHistory($user->membro, $request);
        $estatisticas = $this->financeService->getMemberDonationStats($user->membro);

        return view('member.donations.history', compact('transacoes', 'estatisticas'));
    }

    public function campaigns()
    {
        $campanhas = $this->financeService->getActiveCampaignsForMember();
        return view('member.donations.campaigns', compact('campanhas'));
    }

    public function showCampaign(Campanha $campanha)
    {
        if (!$campanha->ativo) {
            return redirect()->route('member.donations.campaigns')->with('error', 'Campanha não encontrada ou inativa.');
        }
        return view('member.donations.campaign', compact('campanha'));
    }

    public function donate(Request $request)
    {
        $campanha = $request->filled('campanha_id') ? Campanha::find($request->campanha_id) : null;
        $campanhas = $this->financeService->getActiveCampaignsForMember();
        return view('member.donations.donate', compact('campanha', 'campanhas'));
    }

    public function processDonation(Request $request)
    {
        $data = $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'gateway' => 'required|in:stripe,mercadopago,pix',
            'descricao' => 'nullable|string|max:500',
        ]);

        try {
            $transacao = $this->financeService->processMemberDonation(Auth::user(), $data);
            // A lógica de redirecionamento para o gateway seria chamada aqui.
            return redirect()->route('home')->with('success', 'Obrigado por sua doação! Você será redirecionado para o pagamento.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar doação: ' . $e->getMessage())->withInput();
        }
    }
}