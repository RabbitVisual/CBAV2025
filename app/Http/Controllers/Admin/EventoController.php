<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Services\EventoService;

class EventoController extends Controller
{
    protected $eventoService;

    public function __construct(EventoService $eventoService)
    {
        $this->eventoService = $eventoService;
        // Adicionar middleware de permissão aqui
    }

    public function index(Request $request)
    {
        $data = $this->eventoService->getEventos($request);
        return view('admin.eventos.index', $data);
    }

    public function create()
    {
        $data = $this->eventoService->getFormData();
        return view('admin.eventos.create', $data);
    }

    public function store(Request $request)
    {
        $data = $this->validateEvento($request);
        $this->eventoService->createEvento($data);
        return redirect()->route('admin.eventos.index')->with('success', 'Evento criado com sucesso!');
    }

    public function show(Evento $evento)
    {
        $data = $this->eventoService->getEventoDetails($evento);
        return view('admin.eventos.show', $data);
    }

    public function edit(Evento $evento)
    {
        $data = $this->eventoService->getFormData();
        $data['evento'] = $evento;
        return view('admin.eventos.edit', $data);
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $this->validateEvento($request, $evento->id);
        $this->eventoService->updateEvento($evento, $data);
        return redirect()->route('admin.eventos.index')->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy(Evento $evento)
    {
        try {
            $this->eventoService->deleteEvento($evento);
            return redirect()->route('admin.eventos.index')->with('success', 'Evento excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function validateEvento(Request $request, $eventoId = null): array
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'gratuito' => 'required|boolean',
            'valor_inscricao' => 'nullable|numeric|min:0',
            'inscricao_obrigatoria' => 'required|boolean',
            'status' => 'required|in:rascunho,ativo,cancelado,finalizado',
            // Adicionar outras regras de validação conforme necessário
        ];

        return $request->validate($rules);
    }
}