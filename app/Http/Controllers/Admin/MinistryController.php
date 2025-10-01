<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministerio;
use App\Models\Membro;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MinisteriosExport;
use App\Exports\SingleMinistryExport;

class MinistryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ministries.access');
    }

    /**
     * Lista de ministérios
     */
    public function index(Request $request)
    {
        $query = Ministerio::with(['departamentos', 'responsavel'])
            ->withCount(['departamentos']);

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        $ministerios = $query->paginate(15);

        $ministerios->getCollection()->transform(function ($ministerio) {
            $ministerio->membros_count = $ministerio->getMembrosCountAttribute();
            return $ministerio;
        });

        $totalMinisterios = Ministerio::count();
        $ministeriosAtivos = Ministerio::where('ativo', true)->count();

        return view('admin.people.ministries.index', compact(
            'ministerios',
            'totalMinisterios',
            'ministeriosAtivos'
        ));
    }

    /**
     * Formulário para criar novo ministério
     */
    public function create()
    {
        $responsaveis = User::where('ativo', true)->whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Pastor', 'Lider']);
        })->get();

        return view('admin.people.ministries.create', compact('responsaveis'));
    }

    /**
     * Salvar novo ministério
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:ministerios,nome',
            'descricao' => 'nullable|string',
            'cor' => 'nullable|string|max:7',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_fundacao' => 'nullable|date',
            'reuniao_semanal' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        Ministerio::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cor' => $request->cor,
            'responsavel_id' => $request->responsavel_id,
            'data_fundacao' => $request->data_fundacao,
            'reuniao_semanal' => $request->reuniao_semanal,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.ministries.index')->with('success', 'Ministério criado com sucesso!');
    }

    /**
     * Formulário para editar ministério
     */
    public function edit(Ministerio $ministerio)
    {
        $ministerio->load([
            'departamentos' => function($query) {
                $query->withCount(['membros', 'cargos'])->orderBy('nome');
            },
            'responsavel.membro'
        ]);

        $responsaveis = User::where('ativo', true)->whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Pastor', 'Lider']);
        })->get();

        $ministerio->membros_count = $ministerio->getMembrosCountAttribute();

        return view('admin.people.ministries.edit', compact('ministerio', 'responsaveis'));
    }

    /**
     * Atualizar ministério
     */
    public function update(Request $request, Ministerio $ministerio)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:ministerios,nome,' . $ministerio->id,
            'descricao' => 'nullable|string',
            'cor' => 'nullable|string|max:7',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_fundacao' => 'nullable|date',
            'reuniao_semanal' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $ministerio->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cor' => $request->cor,
            'responsavel_id' => $request->responsavel_id,
            'data_fundacao' => $request->data_fundacao,
            'reuniao_semanal' => $request->reuniao_semanal,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.ministries.index')->with('success', 'Ministério atualizado com sucesso!');
    }

    /**
     * Visualizar ministério
     */
    public function show(Ministerio $ministerio)
    {
        $ministerio->load(['departamentos.cargos.membros', 'responsavel']);
        return view('admin.people.ministries.show', compact('ministerio'));
    }

    /**
     * Excluir ministério
     */
    public function destroy(Ministerio $ministerio)
    {
        // Adicionar verificação se há departamentos associados
        if ($ministerio->departamentos()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir um ministério com departamentos. Remova os departamentos primeiro.');
        }

        $ministerio->delete();
        return redirect()->route('admin.people.ministries.index')->with('success', 'Ministério excluído com sucesso!');
    }

    /**
     * Exportar todos os ministérios
     */
    public function export()
    {
        return Excel::download(new MinisteriosExport(), 'ministerios.xlsx');
    }

    /**
     * Exportar um único ministério com seus membros
     */
    public function exportMinistry(Ministerio $ministerio)
    {
        return Excel::download(new SingleMinistryExport($ministerio->id), 'ministerio_' . $ministerio->nome . '.xlsx');
    }
}