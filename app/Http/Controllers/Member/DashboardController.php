<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\MemberDashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(MemberDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Exibe o dashboard principal do membro, agora utilizando o MemberDashboardService.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $dashboardData = $this->dashboardService->getDataForDashboard($user);

            return view('member.dashboard.index', $dashboardData);
        } catch (\Exception $e) {
            // Em caso de erro, redireciona para a edição de perfil com uma mensagem amigável.
            // Isso pode acontecer se houver um problema ao criar o perfil de membro automaticamente.
            \Log::error('Erro ao carregar o dashboard do membro: ' . $e->getMessage());
            return redirect()->route('member.profile.edit')
                ->with('error', 'Houve um problema ao carregar seus dados. Por favor, verifique se seu perfil está completo.');
        }
    }
}