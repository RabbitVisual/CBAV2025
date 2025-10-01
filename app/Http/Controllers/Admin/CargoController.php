<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function __construct()
    {
        // A permissão 'departments.access' está sendo usada aqui,
        // o que pode ser um ponto de melhoria no futuro para ter permissões mais granulares.
        $this->middleware('permission:departments.access');
    }

    /**
     * Lista de cargos
     */
    public function index(Request $request)
    {
        $query = Cargo::with('departamento.ministerio');

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }

        $cargos = $query->paginate(15);
        $departamentos = Departamento::orderBy('nome')->get();

        return view('admin.people.cargos.index', compact('cargos', 'departamentos'));
    }

    /**
     * Formulário para criar novo cargo
     */
    public function create()
    {
        $departamentos = Departamento::with('ministerio')->orderBy('nome')->get();
        return view('admin.people.cargos.create', compact('departamentos'));
    }

    /**
     * Salvar novo cargo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'departamento_id' => 'required|exists:departamentos,id',
            'ativo' => 'boolean',
        ]);

        Cargo::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'departamento_id' => $request->departamento_id,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.cargos.index')->with('success', 'Cargo criado com sucesso!');
    }

    /**
     * Formulário para editar cargo
     */
    public function edit(Cargo $cargo)
    {
        $departamentos = Departamento::with('ministerio')->orderBy('nome')->get();
        return view('admin.people.cargos.edit', compact('cargo', 'departamentos'));
    }

    /**
     * Atualizar cargo
     */
    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'departamento_id' => 'required|exists:departamentos,id',
            'ativo' => 'boolean',
        ]);

        $cargo->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'departamento_id' => $request->departamento_id,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.people.cargos.index')->with('success', 'Cargo atualizado com sucesso!');
    }

    /**
     * Excluir cargo
     */
    public function destroy(Cargo $cargo)
    {
        if ($cargo->membros()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir um cargo com membros associados.');
        }

        $cargo->delete();
        return redirect()->route('admin.people.cargos.index')->with('success', 'Cargo excluído com sucesso!');
    }
}