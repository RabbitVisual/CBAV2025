<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Devocional;
use App\Services\DevocionalService;
use App\Services\BibleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevocionalController extends Controller
{
    protected $devocionalService;
    protected $bibleService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->devocionalService = new DevocionalService();
        $this->bibleService = new BibleService();
    }

    /**
     * Página inicial dos devocionais
     */
    public function index()
    {
        $user = Auth::user();
        
        // Devocional do dia
        $devocionalHoje = $this->devocionalService->getDevocionalDoDia();
        
        // Versículo do dia
        $versiculoDoDia = $this->devocionalService->getVersiculoDoDia();
        
        // Oração do dia
        $oracaoDoDia = $this->devocionalService->getOracaoDoDia();
        
        // Devocionais recentes
        $devocionaisRecentes = Devocional::ativos()
            ->orderBy('data', 'desc')
            ->limit(5)
            ->get();
            
        // Devocionais favoritos (se implementado)
        $devocionaisFavoritos = collect(); // Implementar quando necessário
        
        // Estatísticas pessoais
        $estatisticas = [
            'total_lidos' => 0, // Implementar contador
            'sequencia_atual' => 0, // Implementar streak
            'versiculos_favoritos' => 0, // Implementar contador
            'tempo_medio' => 0 // Implementar cálculo
        ];

        return view('member.devotionals.index', compact(
            'devocionalHoje',
            'versiculoDoDia',
            'oracaoDoDia',
            'devocionaisRecentes',
            'devocionaisFavoritos',
            'estatisticas'
        ));
    }

    /**
     * Visualizar devocional específico
     */
    public function show(Devocional $devocional)
    {
        // Verificar se o devocional está ativo
        if (!$devocional->ativo) {
            return redirect()->route('member.devotionals.index')
                ->with('error', 'Devocional não disponível.');
        }

        // Buscar versículos relacionados
        $versiculos = [];
        if ($devocional->versiculos) {
            foreach ($devocional->versiculos as $versiculo) {
                $versiculoCompleto = $this->bibleService->getVerse($versiculo, 'almeida_ra');
                if ($versiculoCompleto) {
                    $versiculos[] = $versiculoCompleto;
                }
            }
        }

        // Devocionais relacionados
        $devocionaisRelacionados = Devocional::ativos()
            ->where('id', '!=', $devocional->id)
            ->where('tipo', $devocional->tipo)
            ->limit(3)
            ->get();

        return view('member.devotionals.show', compact(
            'devocional',
            'versiculos',
            'devocionaisRelacionados'
        ));
    }

    /**
     * Buscar devocionais
     */
    public function search(Request $request)
    {
        $query = Devocional::ativos();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('reflexao', 'like', "%{$search}%")
                  ->orWhere('oracao', 'like', "%{$search}%")
                  ->orWhere('meditacao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data', '<=', $request->data_fim);
        }

        // Ordenação
        $sort = $request->get('sort', 'data_desc');
        switch ($sort) {
            case 'data_asc':
                $query->orderBy('data', 'asc');
                break;
            case 'titulo':
                $query->orderBy('titulo', 'asc');
                break;
            case 'tipo':
                $query->orderBy('tipo', 'asc');
                break;
            default:
                $query->orderBy('data', 'desc');
        }

        $devocionais = $query->paginate(12);

        // Filtros disponíveis
        $tipos = [
            'devocional' => 'Devocional',
            'reflexao' => 'Reflexão',
            'estudo' => 'Estudo',
            'oracao' => 'Oração'
        ];

        $categorias = [
            'vida_crista' => 'Vida Cristã',
            'familia' => 'Família',
            'ministerio' => 'Ministério',
            'evangelismo' => 'Evangelismo',
            'adoracao' => 'Adoração',
            'discipulado' => 'Discipulado'
        ];

        return view('member.devotionals.search', compact(
            'devocionais',
            'tipos',
            'categorias'
        ));
    }

    /**
     * Versículo aleatório
     */
    public function randomVerse(Request $request)
    {
        $versao = $request->get('versao', 'almeida_ra');
        $versiculo = $this->bibleService->getRandomVerse($versao);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'versiculo' => $versiculo
            ]);
        }

        return view('member.devotionals.random-verse', compact('versiculo'));
    }

    /**
     * Buscar versículo
     */
    public function searchVerse(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string|max:100'
        ]);

        $versao = $request->get('versao', 'almeida_ra');
        $versiculo = $this->bibleService->getVerse($request->referencia, $versao);

        if ($request->ajax()) {
            return response()->json([
                'success' => $versiculo ? true : false,
                'versiculo' => $versiculo
            ]);
        }

        return view('member.devotionals.verse', compact('versiculo'));
    }

    /**
     * Buscar por palavra-chave
     */
    public function searchByKeyword(Request $request)
    {
        $request->validate([
            'palavra' => 'required|string|max:50'
        ]);

        $versao = $request->get('versao', 'almeida_ra');
        $versiculos = $this->bibleService->searchByKeyword($request->palavra, 20, $versao);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'versiculos' => $versiculos
            ]);
        }

        return view('member.devotionals.keyword-search', compact('versiculos', 'palavra'));
    }

    /**
     * Ler capítulo completo
     */
    public function readChapter(Request $request)
    {
        $request->validate([
            'livro' => 'required|string',
            'capitulo' => 'required|integer|min:1'
        ]);

        $versao = $request->get('versao', 'almeida_ra');
        $capitulo = $this->bibleService->getChapter($request->livro, $request->capitulo, $versao);

        if ($request->ajax()) {
            return response()->json([
                'success' => $capitulo ? true : false,
                'capitulo' => $capitulo
            ]);
        }

        return view('member.devotionals.chapter', compact('capitulo'));
    }

    /**
     * Favoritos
     */
    public function favorites()
    {
        $user = Auth::user();
        
        // Implementar sistema de favoritos
        $favoritos = collect(); // Buscar do banco quando implementado
        
        return view('member.devotionals.favorites', compact('favoritos'));
    }

    /**
     * Adicionar aos favoritos
     */
    public function addToFavorites(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:versiculo,devocional',
            'referencia' => 'required|string',
            'texto' => 'required|string'
        ]);

        // Implementar adição aos favoritos
        // Por enquanto, apenas retorna sucesso
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Adicionado aos favoritos!'
            ]);
        }

        return back()->with('success', 'Adicionado aos favoritos!');
    }

    /**
     * Remover dos favoritos
     */
    public function removeFromFavorites(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        // Implementar remoção dos favoritos
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Removido dos favoritos!'
            ]);
        }

        return back()->with('success', 'Removido dos favoritos!');
    }

    /**
     * Histórico de leitura
     */
    public function readingHistory()
    {
        $user = Auth::user();
        
        // Implementar histórico de leitura
        $historico = collect(); // Buscar do banco quando implementado
        
        return view('member.devotionals.history', compact('historico'));
    }

    /**
     * Salvar no histórico
     */
    public function saveToHistory(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:versiculo,devocional,capitulo',
            'referencia' => 'required|string',
            'texto' => 'nullable|string'
        ]);

        // Implementar salvamento no histórico
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return back();
    }

    /**
     * Remover do histórico
     */
    public function removeFromHistory(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        // Implementar remoção do histórico
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Removido do histórico!'
            ]);
        }

        return back()->with('success', 'Removido do histórico!');
    }

    /**
     * Compartilhar versículo
     */
    public function shareVerse(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string',
            'texto' => 'required|string',
            'plataforma' => 'required|in:whatsapp,facebook,twitter,email'
        ]);

        $texto = $request->texto;
        $referencia = $request->referencia;
        $plataforma = $request->plataforma;

        // Gerar links de compartilhamento
        $links = [
            'whatsapp' => "https://wa.me/?text=" . urlencode("{$texto} - {$referencia}"),
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode(request()->url()),
            'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode("{$texto} - {$referencia}"),
            'email' => "mailto:?subject=Versículo&body=" . urlencode("{$texto}\n\n{$referencia}")
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'link' => $links[$plataforma] ?? '#'
            ]);
        }

        return redirect($links[$plataforma] ?? '#');
    }

    /**
     * Verificar status da Bíblia
     */
    public function checkBibleStatus()
    {
        $status = $this->devocionalService->verificarStatus();
        
        return response()->json([
            'success' => true,
            'status' => $status
        ]);
    }

    /**
     * Mudar versão da Bíblia
     */
    public function changeVersion(Request $request)
    {
        $request->validate([
            'versao' => 'required|string'
        ]);

        $rawVersoes = $this->bibleService->getVersions();
        $versao = $request->versao;

        if (!isset($rawVersoes[$versao])) {
            return response()->json([
                'success' => false,
                'message' => 'Versão não disponível'
            ]);
        }

        // Salvar preferência do usuário
        $user = Auth::user();
        // Implementar salvamento da preferência

        return response()->json([
            'success' => true,
            'message' => 'Versão alterada com sucesso!'
        ]);
    }
} 