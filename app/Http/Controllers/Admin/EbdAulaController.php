<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAula;
use App\Services\EbdService;

class EbdAulaController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $aulas = $this->ebdService->getAllAulas();
        return view('admin.ebd.aulas.index', compact('aulas'));
    }

    public function create()
    {
        $data = $this->ebdService->getAulaFormData();
        return view('admin.ebd.aulas.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'licao_id' => 'required|exists:ebd_licoes,id',
            'professor_id' => 'nullable|exists:ebd_professores,id',
            'data_aula' => 'required|date',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim' => 'required|date_format:H:i|after:horario_inicio',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:agendada,realizada,cancelada,adiada',
        ]);

        $this->ebdService->createAula($data);

        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula agendada com sucesso!');
    }

    public function show(EbdAula $aula)
    {
        return view('admin.ebd.aulas.show', compact('aula'));
    }

    public function edit(EbdAula $aula)
    {
        $data = $this->ebdService->getAulaFormData();
        return view('admin.ebd.aulas.edit', array_merge($data, compact('aula')));
    }

    public function update(Request $request, EbdAula $aula)
    {
        $data = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'licao_id' => 'required|exists:ebd_licoes,id',
            'professor_id' => 'nullable|exists:ebd_professores,id',
            'data_aula' => 'required|date',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim' => 'required|date_format:H:i|after:horario_inicio',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:agendada,realizada,cancelada,adiada',
        ]);

        $this->ebdService->updateAula($aula, $data);

        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula atualizada com sucesso!');
    }

    public function destroy(EbdAula $aula)
    {
        $this->ebdService->deleteAula($aula);

        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula removida com sucesso!');
    }
}