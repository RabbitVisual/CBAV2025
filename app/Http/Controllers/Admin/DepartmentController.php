<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Ministerio;
use App\Models\Membro;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:departments.access');
    }

    /**
     * Lista de departamentos
     */
    public function index(Request $request)
    {
        $query = Departamento::with(['ministerio', 'responsavel'])
            ->withCount(['membros', 'cargos']);

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('ministerio')) {
            $query->where('ministerio_id', $request->ministerio);
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        $departamentos = $query->paginate(15);
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();
        $totalDepartamentos = Departamento::count();
        $departamentosAtivos = Departamento::where('ativo', true)->count();

        return view('admin.people.departments.index', compact(
            'departamentos',
            'ministerios',
            'totalDepartamentos',
            'departamentosAtivos'
        ));
    }

    /**
     * Formulário para criar novo departamento
     */
    public function create()
    {
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();
        $responsaveis = Membro::where('ativo', true)->orderBy('nome')->get();

        return view('admin.people.departments.create', compact('ministerios', 'responsaveis'));
    }

    /**
     * Salvar novo departamento
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ministerio_id' => 'required|exists:ministerios,id',
            'responsavel_id' => 'nullable|exists:membros,id',
            'ativo' => 'boolean',
        ]);

        Departamento::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ministerio_id' => $request->ministerio_id,
            'responsavel_id' => $request->responsavel_id,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.departments.index')->with('success', 'Departamento criado com sucesso!');
    }

    /**
     * Formulário para editar departamento
     */
    public function edit(Departamento $departamento)
    {
        $departamento->load(['ministerio', 'responsavel', 'cargos' => function($q) {
            $q->withCount('membros')->orderBy('nome');
        }]);

        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();
        $responsaveis = Membro::where('ativo', true)->orderBy('nome')->get();
        $departamento->membros_count = $departamento->cargos->sum('membros_count');

        return view('admin.people.departments.edit', compact('departamento', 'ministerios', 'responsaveis'));
    }

    /**
     * Atualizar departamento
     */
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ministerio_id' => 'required|exists:ministerios,id',
            'responsavel_id' => 'nullable|exists:membros,id',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $departamento->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ministerio_id' => $request->ministerio_id,
            'responsavel_id' => $request->responsavel_id,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.departments.index')->with('success', 'Departamento atualizado com sucesso!');
    }

    /**
     * Visualizar departamento
     */
    public function show(Departamento $departamento)
    {
        $departamento->load(['ministerio', 'cargos.membros', 'responsavel']);
        return view('admin.people.departments.show', compact('departamento'));
    }

    /**
     * Excluir departamento
     */
    public function destroy(Departamento $departamento)
    {
        if ($departamento->cargos()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir um departamento com cargos. Remova os cargos primeiro.');
        }

        $departamento->delete();
        return redirect()->route('admin.people.departments.index')->with('success', 'Departamento excluído com sucesso!');
    }
}