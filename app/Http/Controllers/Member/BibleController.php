<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BibleService;
use App\Models\User;

class BibleController extends Controller
{
    protected $bibleService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->bibleService = new BibleService();
    }

    /**
     * Página principal da Bíblia
     */
    public function index()
    {
        $versaoAtual = $this->bibleService->getCurrentVersion();
        $rawVersoesDisponiveis = $this->bibleService->getVersions();
        
        // Formatar versões para a view
        $versoesDisponiveis = [];
        foreach ($rawVersoesDisponiveis as $code => $versionInfo) {
            $versoesDisponiveis[] = [
                'code' => $code,
                'name' => $versionInfo['name'],
                'abbrev' => $versionInfo['abbrev']
            ];
        }
        
        $estatisticas = $this->bibleService->getStatistics();
        $versiculoDoDia = $this->bibleService->getVerseOfTheDay();
        $livros = $this->bibleService->getBooks();

        // Obter dados do usuário
        $user = auth()->user();
        
        // Acessar dados diretamente do banco para evitar problemas com cast
        $userRaw = User::select('versiculos_favoritos', 'historico_leitura')->find($user->id);
        
        // Verificar se os dados já são arrays (devido ao cast) ou strings JSON
        $versiculosFavoritos = is_array($userRaw->versiculos_favoritos) 
            ? $userRaw->versiculos_favoritos 
            : (json_decode($userRaw->versiculos_favoritos, true) ?? []);
            
        $historicoLeitura = is_array($userRaw->historico_leitura) 
            ? $userRaw->historico_leitura 
            : (json_decode($userRaw->historico_leitura, true) ?? []);
            


        return view('member.bible.index', compact(
            'versaoAtual',
            'versoesDisponiveis',
            'estatisticas',
            'versiculoDoDia',
            'livros',
            'versiculosFavoritos',
            'historicoLeitura'
        ));
    }

    /**
     * Buscar por referência
     */
    public function searchByReference(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string|max:100',
            'versao' => 'nullable|string',
        ]);

        $referencia = $request->referencia;
        $versao = $request->versao ?? $this->bibleService->getCurrentVersion();

        try {
            $resultado = $this->bibleService->getVerse($referencia, $versao);
            
            if (!$resultado) {
                return back()->with('error', 'Referência não encontrada. Verifique a formatação.');
            }

            return view('member.bible.search-result', compact('resultado', 'referencia', 'versao'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao buscar referência: ' . $e->getMessage());
        }
    }

    /**
     * Buscar por livro, capítulo e versículo
     */
    public function searchByBookChapter(Request $request)
    {
        // Se é uma requisição GET, mostrar a página de busca
        if ($request->isMethod('get')) {
            return $this->showSearchBookPage();
        }

        // Se é uma requisição POST, processar a busca
        $request->validate([
            'livro' => 'required|string',
            'capitulo' => 'required|integer|min:1',
            'versiculo' => 'nullable|integer|min:1',
            'versao' => 'nullable|string',
        ]);

        $livro = $request->livro;
        $capitulo = $request->capitulo;
        $versiculo = $request->versiculo;
        $versao = $request->versao ?? $this->bibleService->getCurrentVersion();

        try {
            if ($versiculo) {
                // Buscar versículo específico
                $referencia = $livro . ' ' . $capitulo . ':' . $versiculo;
                $resultado = $this->bibleService->getVerse($referencia, $versao);
                
                if (!$resultado) {
                    return back()->with('error', 'Versículo não encontrado.');
                }
            } else {
                // Buscar capítulo completo
                $resultado = $this->bibleService->getChapter($livro, $capitulo, $versao);
                
                if (!$resultado) {
                    return back()->with('error', 'Capítulo não encontrado.');
                }
            }

            // Retornar para a mesma página com o resultado
            return $this->showSearchBookPage($resultado);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao buscar: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar página de busca por livro
     */
    private function showSearchBookPage($resultado = null)
    {
        // Status da Bíblia
        $bibleStatus = [
            'available' => $this->bibleService->isAvailable(),
            'version' => $this->bibleService->getCurrentVersionInfo()['name'] ?? 'N/A',
            'books' => count($this->bibleService->getBooks())
        ];

        // Livros disponíveis
        $livros = $this->bibleService->getBooks();

        // Versões disponíveis
        $versoesDisponiveis = $this->bibleService->getVersions();
        $versaoAtual = $this->bibleService->getCurrentVersion();

        return view('member.bible.search-book', compact(
            'bibleStatus',
            'livros', 
            'versoesDisponiveis',
            'versaoAtual',
            'resultado'
        ));
    }

    /**
     * Buscar por palavra-chave
     */
    public function searchByKeyword(Request $request)
    {
        // Log para debug
        \Log::info('SearchByKeyword request', [
            'keyword' => $request->keyword,
            'version' => $request->version,
            'limit' => $request->limit
        ]);

        // Se não há keyword, apenas mostrar a página de busca
        if (!$request->has('keyword') || empty($request->keyword)) {
            $bibleStatus = [
                'available' => $this->bibleService->isAvailable(),
                'version' => $this->bibleService->getCurrentVersion(),
                'books' => count($this->bibleService->getBooks())
            ];
            
            $rawVersions = $this->bibleService->getVersions();
            $versions = [];
            foreach ($rawVersions as $code => $versionInfo) {
                $versions[] = [
                    'code' => $code,
                    'name' => $versionInfo['name'],
                    'abbrev' => $versionInfo['abbrev']
                ];
            }
            
            return view('member.bible.search-keyword', compact('bibleStatus', 'versions'));
        }

        $request->validate([
            'keyword' => 'required|string|max:50',
            'version' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $keyword = $request->keyword;
        $version = $request->version ?? $this->bibleService->getCurrentVersion();
        $limit = $request->limit ?? 20;

        try {
            // Verificar status da Bíblia
            $bibleStatus = [
                'available' => $this->bibleService->isAvailable(),
                'version' => $this->bibleService->getCurrentVersion(),
                'books' => count($this->bibleService->getBooks())
            ];

            \Log::info('Bible status', $bibleStatus);

            if (!$bibleStatus['available']) {
                return view('member.bible.search-keyword', compact('bibleStatus'));
            }

            $results = $this->bibleService->searchByKeyword($keyword, $limit, $version);
            
            \Log::info('Search results', [
                'count' => is_array($results) ? count($results) : 'not array',
                'type' => gettype($results)
            ]);

            $rawVersions = $this->bibleService->getVersions();
            
            // Formatar versões para a view
            $versions = [];
            foreach ($rawVersions as $code => $versionInfo) {
                $versions[] = [
                    'code' => $code,
                    'name' => $versionInfo['name'],
                    'abbrev' => $versionInfo['abbrev']
                ];
            }
            
            $currentVersion = $version;
            
            return view('member.bible.search-keyword', compact('results', 'keyword', 'version', 'bibleStatus', 'versions', 'currentVersion'));
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar palavra-chave', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erro ao buscar palavra-chave: ' . $e->getMessage());
        }
    }

    /**
     * Ler capítulo completo
     */
    public function readChapter(Request $request)
    {
        // Se recebeu uma referência completa, extrair livro e capítulo
        if ($request->has('reference') && !$request->has('livro')) {
            $referencia = $request->reference;
            
            // Extrair livro e capítulo da referência (ex: "João 3:16" -> livro: "João", capítulo: 3)
            if (preg_match('/^([^0-9]+)\s+(\d+):/', $referencia, $matches)) {
                $livro = trim($matches[1]);
                $capitulo = (int)$matches[2];
            } else {
                return back()->with('error', 'Formato de referência inválido.');
            }
        } else {
            $request->validate([
                'livro' => 'required|string',
                'capitulo' => 'required|integer|min:1',
                'versao' => 'nullable|string',
            ]);

            $livro = $request->livro;
            $capitulo = $request->capitulo;
        }

        $versao = $request->versao ?? $this->bibleService->getCurrentVersion();

        try {
            $resultado = $this->bibleService->getChapter($livro, $capitulo, $versao);
            
            if (!$resultado) {
                return back()->with('error', 'Capítulo não encontrado.');
            }

            // Obter versões disponíveis
            $rawVersoesDisponiveis = $this->bibleService->getVersions();
            $versoesDisponiveis = [];
            foreach ($rawVersoesDisponiveis as $code => $versionInfo) {
                $versoesDisponiveis[] = [
                    'code' => $code,
                    'name' => $versionInfo['name'],
                    'abbrev' => $versionInfo['abbrev']
                ];
            }

            // Obter informações da versão atual para exibir o nome
            $versaoInfo = $this->bibleService->getCurrentVersionInfo();
            $nomeVersao = $versaoInfo ? $versaoInfo['name'] : $versao;
            
            // Capitalizar o nome do livro
            $livroCapitalizado = $this->capitalizeBookName($livro);
            
            return view('member.bible.chapter', compact('resultado', 'livro', 'capitulo', 'versao', 'nomeVersao', 'versoesDisponiveis', 'livroCapitalizado'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar capítulo: ' . $e->getMessage());
        }
    }

    /**
     * Versículo aleatório
     */
    public function randomVerse(Request $request)
    {
        $version = $request->version ?? $this->bibleService->getCurrentVersion();

        try {
            // Verificar status da Bíblia
            $bibleStatus = [
                'available' => $this->bibleService->isAvailable(),
                'version' => $this->bibleService->getCurrentVersion(),
                'books' => count($this->bibleService->getBooks())
            ];

            if (!$bibleStatus['available']) {
                return view('member.bible.random-verse', compact('bibleStatus'));
            }

            $verse = $this->bibleService->getRandomVerse($version);
            $rawVersions = $this->bibleService->getVersions();
            
            // Formatar versões para a view
            $versions = [];
            foreach ($rawVersions as $code => $versionInfo) {
                $versions[] = [
                    'code' => $code,
                    'name' => $versionInfo['name'],
                    'abbrev' => $versionInfo['abbrev']
                ];
            }
            
            $currentVersion = $version;
            
            // Se for uma requisição AJAX, retornar JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'verse' => [
                        'text' => $verse['text'] ?? $verse['texto'] ?? $verse['versiculo'] ?? '',
                        'reference' => $verse['reference'] ?? $verse['referencia'] ?? '',
                        'version' => $version
                    ]
                ]);
            }
            
            return view('member.bible.random-verse', compact('verse', 'version', 'bibleStatus', 'versions', 'currentVersion'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao gerar versículo aleatório: ' . $e->getMessage()
                ]);
            }
            
            return back()->with('error', 'Erro ao gerar versículo aleatório: ' . $e->getMessage());
        }
    }

    /**
     * Versículo do dia
     */
    public function verseOfTheDay(Request $request)
    {
        $versao = $request->versao ?? $this->bibleService->getCurrentVersion();

        try {
            $resultado = $this->bibleService->getVerseOfTheDay($versao);
            
            return view('member.bible.verse-of-day', compact('resultado', 'versao'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar versículo do dia: ' . $e->getMessage());
        }
    }

    /**
     * Alterar versão
     */
    public function changeVersion(Request $request)
    {
        // Log para debug
        \Log::info('ChangeVersion request', [
            'method' => $request->method(),
            'ajax' => $request->ajax(),
            'wantsJson' => $request->wantsJson(),
            'content_type' => $request->header('Content-Type'),
            'data' => $request->all()
        ]);

        $request->validate([
            'versao' => 'required|string',
        ]);

        $versao = $request->versao;
        
        try {
            $this->bibleService->setVersion($versao);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Versão alterada com sucesso!'
                ]);
            }
            
            return back()->with('success', 'Versão alterada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao alterar versão', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao alterar versão: ' . $e->getMessage()
                ]);
            }
            
            return back()->with('error', 'Erro ao alterar versão: ' . $e->getMessage());
        }
    }

    /**
     * Verificar status da Bíblia offline
     */
    public function checkStatus()
    {
        $status = [
            'disponivel' => $this->bibleService->isAvailable(),
            'versao_atual' => $this->bibleService->getCurrentVersion(),
            'versoes_disponiveis' => $this->bibleService->getVersions(),
            'estatisticas' => $this->bibleService->getStatistics(),
        ];

        return response()->json($status);
    }

    /**
     * Favoritos
     */
    public function favorites()
    {
        try {
            $user = auth()->user();
            
            // Carregar dados diretamente do banco para evitar problemas de cache
            $userRaw = User::select('versiculos_favoritos')->find($user->id);
            $versiculosFavoritos = is_array($userRaw->versiculos_favoritos) 
                ? $userRaw->versiculos_favoritos 
                : (json_decode($userRaw->versiculos_favoritos, true) ?? []);
            
            // Log para debug
            \Log::info('Carregando favoritos', [
                'user_id' => $user->id,
                'total_favoritos' => count($versiculosFavoritos)
            ]);

            return view('member.bible.favorites', compact('versiculosFavoritos'));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar favoritos', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erro ao carregar favoritos: ' . $e->getMessage());
        }
    }

    /**
     * Adicionar aos favoritos
     */
    public function addToFavorites(Request $request)
    {
        try {
            // Log para debug
            \Log::info('AddToFavorites request', [
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            $request->validate([
                'reference' => 'required|string',
                'text' => 'required|string',
                'version' => 'nullable|string',
            ]);

            $user = auth()->user();
            
            // Carregar dados diretamente do banco para evitar problemas de cache
            $userRaw = User::select('versiculos_favoritos')->find($user->id);
            $favoritos = is_array($userRaw->versiculos_favoritos) 
                ? $userRaw->versiculos_favoritos 
                : (json_decode($userRaw->versiculos_favoritos, true) ?? []);
            
            $novoFavorito = [
                'referencia' => $request->reference,
                'texto' => $request->text,
                'versao' => $request->version ?? $this->bibleService->getCurrentVersion(),
                'data' => now()->toISOString(),
            ];

            // Verificar se já existe para evitar duplicatas
            $jaExiste = false;
            foreach ($favoritos as $favorito) {
                if ($favorito['referencia'] === $novoFavorito['referencia'] && 
                    $favorito['versao'] === $novoFavorito['versao']) {
                    $jaExiste = true;
                    break;
                }
            }

            if ($jaExiste) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Este versículo já está nos seus favoritos!'
                ], 409);
            }

            $favoritos[] = $novoFavorito;
            $user->update(['versiculos_favoritos' => $favoritos]);

            \Log::info('Favorito adicionado com sucesso', [
                'user_id' => $user->id,
                'favorito' => $novoFavorito,
                'total_favoritos' => count($favoritos)
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Versículo adicionado aos favoritos!',
                'total_favoritos' => count($favoritos)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erro de validação em addToFavorites', [
                'user_id' => auth()->id(),
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Dados inválidos: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erro em addToFavorites', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao adicionar aos favoritos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover dos favoritos
     */
    public function removeFromFavorites(Request $request)
    {
        try {
            $request->validate([
                'index' => 'required|integer|min:0',
            ]);

            $user = auth()->user();
            
            // Carregar dados diretamente do banco para evitar problemas de cache
            $userRaw = User::select('versiculos_favoritos')->find($user->id);
            $favoritos = is_array($userRaw->versiculos_favoritos) 
                ? $userRaw->versiculos_favoritos 
                : (json_decode($userRaw->versiculos_favoritos, true) ?? []);
            
            // Log para debug
            \Log::info('Removendo favorito', [
                'user_id' => $user->id,
                'index' => $request->index,
                'total_favoritos' => count($favoritos),
                'favoritos_antes' => $favoritos
            ]);
            
            if (isset($favoritos[$request->index])) {
                $favoritoRemovido = $favoritos[$request->index];
                unset($favoritos[$request->index]);
                $favoritos = array_values($favoritos); // Reindexar array
                
                // Atualizar no banco
                $user->update(['versiculos_favoritos' => $favoritos]);
                
                // Log de sucesso
                \Log::info('Favorito removido com sucesso', [
                    'user_id' => $user->id,
                    'favorito_removido' => $favoritoRemovido,
                    'total_favoritos_apos' => count($favoritos)
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Versículo removido dos favoritos!',
                    'total_favoritos' => count($favoritos)
                ]);
            } else {
                \Log::warning('Índice de favorito não encontrado', [
                    'user_id' => $user->id,
                    'index' => $request->index,
                    'total_favoritos' => count($favoritos)
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Favorito não encontrado!'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao remover favorito', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao remover favorito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpar todos os favoritos
     */
    public function clearFavorites(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Log para debug
            \Log::info('Limpando todos os favoritos', [
                'user_id' => $user->id
            ]);
            
            $user->update(['versiculos_favoritos' => []]);

            \Log::info('Todos os favoritos removidos com sucesso', [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Todos os favoritos foram removidos!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao limpar favoritos', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao limpar favoritos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compartilhar versículo
     */
    public function shareVerse(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string',
            'texto' => 'required|string',
            'versao' => 'required|string',
        ]);

        $dados = [
            'referencia' => $request->referencia,
            'texto' => $request->texto,
            'versao' => $request->versao,
        ];

        // Gerar link de compartilhamento
        $link = route('member.bible.shared', $dados);

        return response()->json([
            'success' => true,
            'link' => $link,
            'message' => 'Link de compartilhamento gerado!'
        ]);
    }

    /**
     * Versículo compartilhado
     */
    public function sharedVerse(Request $request)
    {
        $referencia = $request->referencia ?? 'Referência não informada';
        $texto = $request->texto ?? 'Texto não informado';
        $versao = $request->versao ?? 'Versão não informada';

        return view('member.bible.shared', compact('referencia', 'texto', 'versao'));
    }

    /**
     * Histórico de leitura
     */
    public function readingHistory()
    {
        $user = auth()->user();
        $historico = $user->historicoLeitura ?? [];

        return view('member.bible.history', compact('historico'));
    }

    /**
     * Salvar no histórico
     */
    public function saveToHistory(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string',
            'texto' => 'required|string',
            'versao' => 'required|string',
        ]);

        $user = auth()->user();
        $historico = $user->historicoLeitura ?? [];
        
        $novaLeitura = [
            'referencia' => $request->referencia,
            'texto' => $request->texto,
            'versao' => $request->versao,
            'data' => now()->toISOString(),
        ];

        // Adicionar no início do array
        array_unshift($historico, $novaLeitura);
        
        // Manter apenas os últimos 50 registros
        $historico = array_slice($historico, 0, 50);
        
        $user->update(['historico_leitura' => $historico]);

        return response()->json(['success' => true]);
    }

    /**
     * Capitalizar nome do livro
     */
    private function capitalizeBookName($bookName)
    {
        // Capitalizar primeira letra e manter o resto
        return mb_convert_case(mb_strtolower($bookName), MB_CASE_TITLE, 'UTF-8');
    }
} 