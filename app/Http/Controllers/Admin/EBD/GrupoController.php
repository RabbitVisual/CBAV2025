<?php

namespace App\Http\Controllers\Admin\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\Grupo;
use App\Models\EBD\Turma;
use Illuminate\Http\Request;
use App\Services\EbdService;

class GrupoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('permission:ebd.access');
        $this->ebdService = $ebdService;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turmas = Turma::ativas()->get();
        return view('admin.ebd.grupos.create', compact('turmas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'nome' => 'required|string|max:255',
            'lider_id' => 'nullable|exists:users,id',
        ]);

        Grupo::create($request->all());

        return redirect()->route('admin.ebd.turmas.show', $request->turma_id)
            ->with('success', 'Grupo criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        $grupo->load(['turma', 'lider', 'membros']);
        return view('admin.ebd.grupos.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        $turmas = Turma::ativas()->get();
        return view('admin.ebd.grupos.edit', compact('grupo', 'turmas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'nome' => 'required|string|max:255',
            'lider_id' => 'nullable|exists:users,id',
        ]);

        $grupo->update($request->all());

        return redirect()->route('admin.ebd.turmas.show', $request->turma_id)
            ->with('success', 'Grupo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        $turmaId = $grupo->turma_id;
        $grupo->delete();
        return redirect()->route('admin.ebd.turmas.show', $turmaId)
            ->with('success', 'Grupo excluído com sucesso.');
    }
}