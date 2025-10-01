<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devocional;
use App\Services\DevocionalService;
use App\Services\BibleService;
use App\Exports\DevocionaisExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DevocionalController extends Controller
{
    private $devocionalService;
    private $bibleService;

    public function __construct()
    {
        $this->middleware('permission:devotionals.access');
        $this->devocionalService = new DevocionalService();
        $this->bibleService = new BibleService();
    }

    /**
     * Lista de devocionais
     */
    public function index(Request $request)
    {
        $query = Devocional::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data')) {
            $query->where('data', $request->data);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->busca . '%')
                  ->orWhere('texto', 'like', '%' . $request->busca . '%')
                  ->orWhere('versiculo', 'like', '%' . $request->busca . '%');
            });
        }

        $devocionais = $query->orderBy('data', 'desc')
            ->orderBy('tipo')
            ->orderBy('ordem')
            ->paginate(20);

        $stats = [
            'total' => Devocional::count(),
            'ativos' => Devocional::where('ativo', true)->count(),
            'devocionais' => Devocional::where('tipo', 'devocional')->count(),
            'versiculos' => Devocional::where('tipo', 'versiculo')->count(),
            'oracoes' => Devocional::where('tipo', 'oracao')->count(),
            'hoje' => Devocional::where('data', Carbon::today())->count(),
        ];

        return view('admin.devotionals.index', compact('devocionais', 'stats'));
    }

    /**
     * Formulário para criar devocional
     */
    public function create()
    {
        $rawVersoes = $this->bibleService->getVersions();
        
        // Formatar versões para a view
        $versoes = [];
        foreach ($rawVersoes as $code => $versionInfo) {
            $versoes[] = [
                'code' => $code,
                'name' => $versionInfo['name'],
                'abbrev' => $versionInfo['abbrev']
            ];
        }
        
        return view('admin.devotionals.create', compact('versoes'));
    }

    /**
     * Salvar novo devocional
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'texto' => 'required|string',
            'versiculo' => 'required|string|max:255',
            'texto_versiculo' => 'nullable|string',
            'reflexao' => 'nullable|string',
            'data' => 'required|date',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $devocional = Devocional::create($validated);

        // Limpar cache
        $this->devocionalService->limparCache();

        return redirect()->route('admin.devotionals.index')
            ->with('success', 'Devocional criado com sucesso!');
    }

    /**
     * Formulário para editar devocional
     */
    public function edit(Devocional $devocional)
    {
        $rawVersoes = $this->bibleService->getVersions();
        
        // Formatar versões para a view
        $versoes = [];
        foreach ($rawVersoes as $code => $versionInfo) {
            $versoes[] = [
                'code' => $code,
                'name' => $versionInfo['name'],
                'abbrev' => $versionInfo['abbrev']
            ];
        }
        
        return view('admin.devotionals.edit', compact('devocional', 'versoes'));
    }

    /**
     * Atualizar devocional
     */
    public function update(Request $request, Devocional $devocional)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'texto' => 'required|string',
            'versiculo' => 'required|string|max:255',
            'texto_versiculo' => 'nullable|string',
            'reflexao' => 'nullable|string',
            'data' => 'required|date',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $devocional->update($validated);

        // Limpar cache
        $this->devocionalService->limparCache();

        return redirect()->route('admin.devotionals.index')
            ->with('success', 'Devocional atualizado com sucesso!');
    }

    /**
     * Excluir devocional
     */
    public function destroy(Devocional $devocional)
    {
        $devocional->delete();

        // Limpar cache
        $this->devocionalService->limparCache();

        return redirect()->route('admin.devotionals.index')
            ->with('success', 'Devocional excluído com sucesso!');
    }

    /**
     * Formulário para criar devocionais em lote
     */
    public function batchForm()
    {
        return view('admin.devotionals.batch');
    }

    /**
     * Criar devocionais em lote
     */
    public function createBatch(Request $request)
    {
        $validated = $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'tipo' => 'required|in:devocional,versiculo,oracao',
            'padrao' => 'nullable|boolean',
        ]);

        $dataInicio = Carbon::parse($validated['data_inicio']);
        $dataFim = Carbon::parse($validated['data_fim']);
        $criados = 0;
        $padrao = $validated['padrao'] ?? false;

        for ($data = $dataInicio; $data->lte($dataFim); $data->addDay()) {
            // Verificar se já existe devocional para esta data e tipo
            $existente = Devocional::where('data', $data->format('Y-m-d'))
                ->where('tipo', $validated['tipo'])
                ->first();

            if (!$existente) {
                if ($padrao) {
                    Devocional::criarPadrao($data->format('Y-m-d'), $validated['tipo']);
                } else {
                    Devocional::create([
                        'titulo' => ucfirst($validated['tipo']) . ' - ' . $data->format('d/m/Y'),
                        'texto' => 'Texto do ' . $validated['tipo'] . ' para ' . $data->format('d/m/Y'),
                        'versiculo' => 'Referência bíblica',
                        'reflexao' => 'Reflexão para o dia',
                        'data' => $data->format('Y-m-d'),
                        'tipo' => $validated['tipo'],
                        'ativo' => true,
                        'ordem' => 0
                    ]);
                }
                $criados++;
            }
        }

        // Limpar cache
        $this->devocionalService->limparCache();

        return redirect()->route('admin.devotionals.index')
            ->with('success', "{$criados} devocionais criados com sucesso!");
    }

    /**
     * Visualizar devocional
     */
    public function show(Devocional $devocional)
    {
        return view('admin.devotionals.show', compact('devocional'));
    }

    /**
     * Ativar/desativar devocional
     */
    public function toggleStatus(Devocional $devocional)
    {
        $devocional->update(['ativo' => !$devocional->ativo]);

        // Limpar cache
        $this->devocionalService->limparCache();

        $status = $devocional->ativo ? 'ativado' : 'desativado';
        return redirect()->route('admin.devotionals.index')
            ->with('success', "Devocional {$status} com sucesso!");
    }

    /**
     * Duplicar devocional
     */
    public function duplicar(Devocional $devocional)
    {
        $novo = $devocional->replicate();
        $novo->titulo = $devocional->titulo . ' (Cópia)';
        $novo->data = Carbon::today();
        $novo->save();

        return redirect()->route('admin.devotionals.index')
            ->with('success', 'Devocional duplicado com sucesso!');
    }

    /**
     * Preview do devocional no dashboard do membro
     */
    public function preview()
    {
        $devocional = $this->devocionalService->getDevocionalDoDia();
        $versiculo = $this->devocionalService->getVersiculoDoDia();
        $oracao = $this->devocionalService->getOracaoDoDia();

        return view('admin.gestao-sistema.devocional-preview', compact('devocional', 'versiculo', 'oracao'));
    }

    /**
     * Buscar versículo da Bíblia offline
     */
    public function buscarVersiculoOffline(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string',
            'versao' => 'nullable|string'
        ]);

        $referencia = $request->referencia;
        $versao = $request->versao;

        $versiculo = $this->devocionalService->buscarVersiculo($referencia, $versao);

        if ($versiculo) {
            return response()->json([
                'success' => true,
                'versiculo' => [
                    'texto' => $versiculo['text'],
                    'referencia' => $versiculo['reference'],
                    'versao' => $versiculo['version_abbrev'],
                    'fonte' => 'offline'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Versículo não encontrado na Bíblia offline'
        ], 404);
    }

    /**
     * Buscar versículo aleatório da Bíblia offline
     */
    public function buscarVersiculoAleatorio(Request $request)
    {
        $versao = $request->input('versao');
        $versiculo = $this->devocionalService->getVersiculoAleatorio($versao);

        if ($versiculo) {
            return response()->json([
                'success' => true,
                'versiculo' => [
                    'texto' => $versiculo['text'],
                    'referencia' => $versiculo['reference'],
                    'versao' => $versiculo['version_abbrev'],
                    'fonte' => 'offline'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar versículo aleatório'
        ], 500);
    }

    /**
     * Buscar versículos por palavra-chave
     */
    public function buscarPorPalavraChave(Request $request)
    {
        $request->validate([
            'palavra' => 'required|string|min:3',
            'versao' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $palavra = $request->palavra;
        $versao = $request->versao;
        $limit = $request->limit ?? 20;

        $versiculos = $this->devocionalService->buscarPorPalavraChave($palavra, $versao, $limit);

        return response()->json([
            'success' => true,
            'versiculos' => $versiculos
        ]);
    }

    /**
     * Criar devocional com versículo da Bíblia offline
     */
    public function criarComVersiculoOffline(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string',
            'data' => 'required|date',
            'versao' => 'nullable|string'
        ]);

        $referencia = $request->referencia;
        $data = $request->data;
        $versao = $request->versao;

        $devocional = $this->devocionalService->criarComVersiculoOffline($data, $referencia, $versao);

        if ($devocional) {
                    return redirect()->route('admin.devotionals.index')
            ->with('success', 'Devocional criado com versículo da Bíblia offline com sucesso!');
        }

        return redirect()->route('admin.devotionals.index')
            ->with('error', 'Erro ao buscar versículo da Bíblia offline. Verifique a referência.');
    }

    /**
     * Verificar status do sistema
     */
    public function verificarStatus()
    {
        $status = $this->devocionalService->verificarStatus();

        return response()->json([
            'success' => true,
            'status' => $status
        ]);
    }

    /**
     * Obter estatísticas do sistema
     */
    public function estatisticas()
    {
        $estatisticas = $this->devocionalService->getEstatisticas();

        return response()->json([
            'success' => true,
            'estatisticas' => $estatisticas
        ]);
    }

    /**
     * Limpar cache do sistema
     */
    public function limparCache()
    {
        $this->devocionalService->limparCache();

        return response()->json([
            'success' => true,
            'message' => 'Cache limpo com sucesso!'
        ]);
    }

    /**
     * Verificar status do sistema
     */
    public function status()
    {
        try {
            $status = $this->devocionalService->verificarStatus();
            
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => 'Sistema funcionando normalmente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar devocionais
     */
    public function export()
    {
        $devocionais = Devocional::orderBy('data', 'desc')->get();
        
        return Excel::download(new DevocionaisExport($devocionais), 'devocionais.xlsx');
    }
}
