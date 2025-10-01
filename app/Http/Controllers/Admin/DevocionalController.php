<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devocional;
use App\Services\DevocionalService;
use App\Services\BibleService;

class DevocionalController extends Controller
{
    protected $devocionalService;
    protected $bibleService;

    public function __construct(DevocionalService $devocionalService, BibleService $bibleService)
    {
        $this->middleware('permission:devotionals.access');
        $this->devocionalService = $devocionalService;
        $this->bibleService = $bibleService;
    }

    public function index(Request $request)
    {
        $data = $this->devocionalService->getDevocionais($request);
        return view('admin.devotionals.index', $data);
    }

    public function create()
    {
        $data = $this->devocionalService->getDevocionalFormData();
        return view('admin.devotionals.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255', 'texto' => 'required|string',
            'versiculo' => 'required|string|max:255', 'texto_versiculo' => 'nullable|string',
            'reflexao' => 'nullable|string', 'data' => 'required|date',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'ativo' => 'boolean', 'ordem' => 'nullable|integer|min:0',
        ]);
        $validated['ativo'] = $request->has('ativo');

        $this->devocionalService->createDevocional($validated);

        return redirect()->route('admin.devotionals.index')->with('success', 'Devocional criado com sucesso!');
    }

    public function show(Devocional $devocional)
    {
        return view('admin.devotionals.show', compact('devocional'));
    }

    public function edit(Devocional $devocional)
    {
        $data = $this->devocionalService->getDevocionalFormData();
        $data['devocional'] = $devocional;
        return view('admin.devotionals.edit', $data);
    }

    public function update(Request $request, Devocional $devocional)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255', 'texto' => 'required|string',
            'versiculo' => 'required|string|max:255', 'texto_versiculo' => 'nullable|string',
            'reflexao' => 'nullable|string', 'data' => 'required|date',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'ativo' => 'boolean', 'ordem' => 'nullable|integer|min:0',
        ]);
        $validated['ativo'] = $request->has('ativo');

        $this->devocionalService->updateDevocional($devocional, $validated);

        return redirect()->route('admin.devotionals.index')->with('success', 'Devocional atualizado com sucesso!');
    }

    public function destroy(Devocional $devocional)
    {
        $this->devocionalService->deleteDevocional($devocional);
        return redirect()->route('admin.devotionals.index')->with('success', 'Devocional excluído com sucesso!');
    }

    public function batchForm()
    {
        return view('admin.devotionals.batch');
    }

    public function createBatch(Request $request)
    {
        $validated = $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'padrao' => 'nullable|boolean',
        ]);

        $criados = $this->devocionalService->createBatch($validated);

        return redirect()->route('admin.devotionals.index')->with('success', "{$criados} devocionais criados com sucesso!");
    }

    public function toggleStatus(Devocional $devocional)
    {
        $this->devocionalService->toggleStatus($devocional);
        $status = $devocional->ativo ? 'ativado' : 'desativado';
        return redirect()->route('admin.devotionals.index')->with('success', "Devocional {$status} com sucesso!");
    }

    public function duplicar(Devocional $devocional)
    {
        $this->devocionalService->duplicar($devocional);
        return redirect()->route('admin.devotionals.index')->with('success', 'Devocional duplicado com sucesso!');
    }

    public function preview()
    {
        $devocional = $this->devocionalService->getDevocionalDoDia();
        $versiculo = $this->devocionalService->getVersiculoDoDia();
        $oracao = $this->devocionalService->getOracaoDoDia();
        return view('admin.gestao-sistema.devocional-preview', compact('devocional', 'versiculo', 'oracao'));
    }

    public function buscarVersiculoOffline(Request $request)
    {
        $versiculo = $this->devocionalService->buscarVersiculo($request->referencia, $request->versao);
        if ($versiculo) return response()->json(['success' => true, 'versiculo' => ['texto' => $versiculo['text'], 'referencia' => $versiculo['reference'], 'versao' => $versiculo['version_abbrev'], 'fonte' => 'offline']]);
        return response()->json(['success' => false, 'message' => 'Versículo não encontrado'], 404);
    }

    public function buscarVersiculoAleatorio(Request $request)
    {
        $versiculo = $this->devocionalService->getVersiculoAleatorio($request->versao);
        if ($versiculo) return response()->json(['success' => true, 'versiculo' => ['texto' => $versiculo['text'], 'referencia' => $versiculo['reference'], 'versao' => $versiculo['version_abbrev'], 'fonte' => 'offline']]);
        return response()->json(['success' => false, 'message' => 'Erro ao buscar versículo'], 500);
    }

    public function buscarPorPalavraChave(Request $request)
    {
        $versiculos = $this->devocionalService->buscarPorPalavraChave($request->palavra, $request->versao, $request->limit ?? 20);
        return response()->json(['success' => true, 'versiculos' => $versiculos]);
    }

    public function export()
    {
        return $this->devocionalService->exportar();
    }
}