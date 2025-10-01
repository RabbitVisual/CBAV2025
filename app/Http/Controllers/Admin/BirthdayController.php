<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\Ministerio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AniversariantesExport;

class BirthdayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:members.access');
    }

    /**
     * Lista de aniversariantes
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        $ministerioId = $request->get('ministerio_id');

        $query = Membro::where('ativo', true)->whereMonth('data_nascimento', $mes);

        if ($request->has('ano') && $ano != now()->year) {
            $query->whereYear('data_nascimento', $ano);
        }

        if ($ministerioId) {
            $query->whereHas('cargos.departamento.ministerio', function($q) use ($ministerioId) {
                $q->where('ministerios.id', $ministerioId);
            });
        }

        $aniversariantes = $query->orderByRaw('DAY(data_nascimento) ASC')->paginate(15);
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();

        $estatisticas = [
            'mes' => Membro::where('ativo', true)->whereMonth('data_nascimento', now()->month)->count(),
            'hoje' => Membro::where('ativo', true)->whereMonth('data_nascimento', now()->month)->whereDay('data_nascimento', now()->day)->count(),
            'semana' => Membro::where('ativo', true)
                ->where(function ($q) {
                    $start = now()->startOfWeek();
                    $end = now()->endOfWeek();
                    $q->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") BETWEEN ? AND ?', [$start->format('m-d'), $end->format('m-d')]);
                })->count(),
        ];

        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        return view('admin.people.birthdays.index', compact(
            'aniversariantes', 'ministerios', 'mes', 'ano', 'meses', 'estatisticas'
        ));
    }

    /**
     * Exportar aniversariantes para Excel
     */
    public function export(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        $ministerioId = $request->get('ministerio_id');

        return Excel::download(new AniversariantesExport($mes, $ano, $ministerioId), "aniversariantes_{$mes}_{$ano}.xlsx");
    }
}