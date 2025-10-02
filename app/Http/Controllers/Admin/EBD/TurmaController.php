<?php

namespace App\Http\Controllers\Admin\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\Turma;
use Illuminate\Http\Request;
use App\Services\EbdService;

class TurmaController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('permission:ebd.access');
        $this->ebdService = $ebdService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turmas = Turma::withCount(['alunos', 'professores'])->paginate(10);
        return view('admin.ebd.turmas.index', compact('turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ebd.turmas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'faixa_etaria' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        Turma::create($request->all());

        return redirect()->route('admin.ebd.turmas.index')
            ->with('success', 'Turma criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turma $turma)
    {
        $turma->load(['alunos', 'professores', 'grupos.membros']);
        return view('admin.ebd.turmas.show', compact('turma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turma $turma)
    {
        return view('admin.ebd.turmas.edit', compact('turma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'faixa_etaria' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        $turma->update($request->all());

        return redirect()->route('admin.ebd.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turma $turma)
    {
        $turma->delete();
        return redirect()->route('admin.ebd.turmas.index')
            ->with('success', 'Turma excluída com sucesso.');
    }
}