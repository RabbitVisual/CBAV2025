<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\User;
use App\Models\Ministerio;
use App\Models\Departamento;
use Illuminate\Http\Request;

class PeopleDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:people.access');
    }

    /**
     * Exibe o dashboard da seção de Pessoas.
     */
    public function __invoke(Request $request)
    {
        $estatisticas = [
            'total_membros' => Membro::count(),
            'total_usuarios' => User::count(),
            'total_ministerios' => Ministerio::count(),
            'total_departamentos' => Departamento::count(),
            'membros_ativos' => Membro::where('ativo', true)->count(),
            'usuarios_ativos' => User::where('ativo', true)->count(),
        ];

        $membrosRecentes = Membro::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ministeriosComEstatisticas = Ministerio::with('departamentos')
            ->get()
            ->map(function ($ministerio) {
                $membrosCount = $ministerio->getMembrosCountAttribute();
                $ministerio->membros_count = $membrosCount;
                return $ministerio;
            })
            ->sortByDesc('membros_count')
            ->take(5);

        return view('admin.people.dashboard', compact('estatisticas', 'membrosRecentes', 'ministeriosComEstatisticas'));
    }
}