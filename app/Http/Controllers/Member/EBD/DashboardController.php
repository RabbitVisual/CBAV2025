<?php

namespace App\Http\Controllers\Member\EBD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EbdService;

class DashboardController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('auth');
        $this->ebdService = $ebdService;
    }

    /**
     * Display the EBD dashboard for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // The EbdService will be responsible for fetching all relevant data
        // For now, we'll just pass the user, but the service will handle the logic.
        // Example of what the service might do:
        // $turmas = $this->ebdService->getTurmasForUser($user);
        // $grupos = $this->ebdService->getGruposForUser($user);
        // $aulasRecentes = $this->ebdService->getRecentAulasForUser($user);

        $turmas = $user->turmasComoAluno; // Assuming this relationship is added to User model
        $grupos = $user->gruposDeEstudo;  // Assuming this relationship is added to User model

        return view('member.ebd.dashboard', compact('user', 'turmas', 'grupos'));
    }
}