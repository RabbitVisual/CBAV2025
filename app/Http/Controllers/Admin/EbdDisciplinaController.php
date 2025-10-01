<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EbdService;
use App\Models\Disciplina;
use Illuminate\Http\Request;

class EbdDisciplinaController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
        // $this->middleware('permission:ebd.manage'); // Adicionar permissão depois
    }

    public function index()
    {
        $disciplinas = $this->ebdService->getAllDisciplinas();
        return view('admin.ebd.disciplinas.index', compact('disciplinas'));
    }

    public function create()
    {
        $data = $this->ebdService->getDisciplinaFormData();
        return view('admin.ebd.disciplinas.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'professor_responsavel_id' => 'nullable|exists:users,id',
            'carga_horaria' => 'nullable|integer',
            'codigo_disciplina' => 'required|string|unique:ebd_disciplinas,codigo_disciplina',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->createDisciplina($data);

        return redirect()->route('admin.ebd.disciplinas.index')->with('success', 'Disciplina criada com sucesso.');
    }

    public function edit(Disciplina $disciplina)
    {
        $data = $this->ebdService->getDisciplinaFormData();
        $data['disciplina'] = $disciplina;
        return view('admin.ebd.disciplinas.edit', $data);
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'professor_responsavel_id' => 'nullable|exists:users,id',
            'carga_horaria' => 'nullable|integer',
            'codigo_disciplina' => 'required|string|unique:ebd_disciplinas,codigo_disciplina,' . $disciplina->id,
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->updateDisciplina($disciplina, $data);

        return redirect()->route('admin.ebd.disciplinas.index')->with('success', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(Disciplina $disciplina)
    {
        try {
            $this->ebdService->deleteDisciplina($disciplina);
            return redirect()->route('admin.ebd.disciplinas.index')->with('success', 'Disciplina excluída com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}