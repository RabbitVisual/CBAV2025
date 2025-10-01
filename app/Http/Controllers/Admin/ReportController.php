<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\Ministerio;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembrosExport;
use App\Exports\MinisteriosExport;
use App\Exports\AniversariantesExport;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports.access');
    }

    /**
     * Página principal de relatórios
     */
    public function index()
    {
        $estatisticas = [
            'total_membros' => Membro::count(),
            'membros_ativos' => Membro::where('ativo', true)->count(),
            'membros_inativos' => Membro::where('ativo', false)->count(),
            'total_ministerios' => Ministerio::count(),
            'ministerios_ativos' => Ministerio::where('ativo', true)->count(),
            'total_departamentos' => Departamento::count(),
            'departamentos_ativos' => Departamento::where('ativo', true)->count(),
        ];

        return view('admin.people.reports.index', compact('estatisticas'));
    }

    /**
     * Exportar relatórios
     */
    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'membros');

        switch ($tipo) {
            case 'membros':
                return Excel::download(new MembrosExport(), 'relatorio_membros.xlsx');
            case 'ministerios':
                return Excel::download(new MinisteriosExport(), 'relatorio_ministerios.xlsx');
            case 'aniversariantes':
                $mes = $request->get('mes', now()->month);
                $ano = $request->get('ano', now()->year);
                return Excel::download(new AniversariantesExport($mes, $ano), 'relatorio_aniversariantes.xlsx');
            default:
                return redirect()->back()->with('error', 'Tipo de relatório inválido');
        }
    }

    /**
     * Exportar todos os relatórios (placeholder)
     */
    public function exportAll()
    {
        // Lógica para exportar um pacote com todos os relatórios pode ser implementada aqui.
        return redirect()->back()->with('info', 'Funcionalidade de exportação de todos os relatórios em desenvolvimento.');
    }
}