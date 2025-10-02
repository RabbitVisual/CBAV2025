<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BibleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BibleController extends Controller
{
    protected $bibleService;

    public function __construct(BibleService $bibleService)
    {
        $this->middleware('auth');
        $this->bibleService = $bibleService;
    }

    /**
     * Display the main Bible page with user-specific data.
     */
    public function index()
    {
        $user = Auth::user();
        $versaoAtual = $this->bibleService->getCurrentVersion();
        $versoesDisponiveis = $this->bibleService->getFormattedVersions();
        $estatisticas = $this->bibleService->getStatistics();
        $versiculoDoDia = $this->bibleService->getVerseOfTheDay();
        $livros = $this->bibleService->getBooks();
        $versiculosFavoritos = $this->bibleService->getFavorites($user);
        $historicoLeitura = $this->bibleService->getReadingHistory($user);

        return view('member.bible.index', compact(
            'versaoAtual', 'versoesDisponiveis', 'estatisticas', 'versiculoDoDia',
            'livros', 'versiculosFavoritos', 'historicoLeitura'
        ));
    }

    /**
     * Search for a specific verse or chapter.
     */
    public function search(Request $request)
    {
        $request->validate([
            'livro' => 'required|string',
            'capitulo' => 'required|integer|min:1',
            'versiculo' => 'nullable|integer|min:1',
            'versao' => 'nullable|string',
        ]);

        try {
            $livro = $request->livro;
            $capitulo = $request->capitulo;
            $versiculo = $request->versiculo;
            $versao = $request->versao;

            if ($versiculo) {
                $referencia = "{$livro} {$capitulo}:{$versiculo}";
                $resultado = $this->bibleService->getVerse($referencia, $versao);
            } else {
                $resultado = $this->bibleService->getChapter($livro, $capitulo, $versao);
            }

            if (!$resultado) {
                return back()->with('error', 'Referência não encontrada.')->withInput();
            }

            // Save to history if it's a specific verse
            if ($versiculo && Auth::check()) {
                $this->bibleService->saveToHistory(Auth::user(), $resultado);
            }

            return view('member.bible.search-result', compact('resultado'));

        } catch (\Exception $e) {
            Log::error("Erro na busca da Bíblia: " . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao processar sua busca.');
        }
    }

    /**
     * Search by keyword.
     */
    public function searchByKeyword(Request $request)
    {
        if (!$request->filled('keyword')) {
            $data = [
                'bibleStatus' => $this->bibleService->getStatistics(),
                'versions' => $this->bibleService->getFormattedVersions(),
                'currentVersion' => $this->bibleService->getCurrentVersion(),
            ];
            return view('member.bible.search-keyword', $data);
        }

        $request->validate([
            'keyword' => 'required|string|min:3|max:50',
            'version' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        try {
            $results = $this->bibleService->searchByKeyword(
                $request->keyword,
                $request->limit ?? 20,
                $request->version
            );

            $data = [
                'results' => $results,
                'keyword' => $request->keyword,
                'version' => $request->version ?? $this->bibleService->getCurrentVersion(),
                'bibleStatus' => $this->bibleService->getStatistics(),
                'versions' => $this->bibleService->getFormattedVersions(),
                'currentVersion' => $request->version ?? $this->bibleService->getCurrentVersion(),
            ];
            
            return view('member.bible.search-keyword', $data);
        } catch (\Exception $e) {
            Log::error('Erro na busca por palavra-chave: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao buscar por palavra-chave.');
        }
    }

    /**
     * Display a full chapter.
     */
    public function readChapter(Request $request)
    {
        $request->validate([
            'livro' => 'required|string',
            'capitulo' => 'required|integer|min:1',
            'versao' => 'nullable|string',
        ]);

        try {
            $resultado = $this->bibleService->getChapter($request->livro, $request->capitulo, $request->versao);

            if (!$resultado) {
                return back()->with('error', 'Capítulo não encontrado.');
            }

            return view('member.bible.chapter', [
                'resultado' => $resultado,
                'livro' => $request->livro,
                'capitulo' => $request->capitulo,
                'versao' => $resultado['version_abbrev'],
                'nomeVersao' => $resultado['version'],
                'versoesDisponiveis' => $this->bibleService->getFormattedVersions(),
                'livroCapitalizado' => $resultado['book']
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar capítulo: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar o capítulo.');
        }
    }

    /**
     * Get a random verse.
     */
    public function randomVerse(Request $request)
    {
        try {
            $verse = $this->bibleService->getRandomVerse($request->version);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'verse' => $verse]);
            }

            return view('member.bible.random-verse', [
                'verse' => $verse,
                'version' => $this->bibleService->getCurrentVersion(),
                'bibleStatus' => $this->bibleService->getStatistics(),
                'versions' => $this->bibleService->getFormattedVersions(),
                'currentVersion' => $this->bibleService->getCurrentVersion(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao obter versículo aleatório: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao buscar versículo.'], 500);
            }
            return back()->with('error', 'Não foi possível obter um versículo aleatório.');
        }
    }

    /**
     * Change the current Bible version.
     */
    public function changeVersion(Request $request)
    {
        $request->validate(['versao' => 'required|string']);

        try {
            $success = $this->bibleService->setVersion($request->versao);
            if ($success) {
                return response()->json(['success' => true, 'message' => 'Versão alterada com sucesso!']);
            }
            return response()->json(['success' => false, 'message' => 'Versão inválida.'], 400);
        } catch (\Exception $e) {
            Log::error('Erro ao alterar versão da Bíblia: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocorreu um erro inesperado.'], 500);
        }
    }

    /**
     * Display user's favorite verses.
     */
    public function favorites()
    {
        $versiculosFavoritos = $this->bibleService->getFavorites(Auth::user());
        return view('member.bible.favorites', compact('versiculosFavoritos'));
    }

    /**
     * Add a verse to user's favorites.
     */
    public function addToFavorites(Request $request)
    {
        try {
            $request->validate([
                'reference' => 'required|string',
                'text' => 'required|string',
                'version' => 'required|string',
            ]);
            
            $result = $this->bibleService->addToFavorites(Auth::user(), $request->all());

            return response()->json($result, $result['success'] ? 200 : 409);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar favorito: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno ao adicionar favorito.'], 500);
        }
    }

    /**
     * Remove a verse from user's favorites.
     */
    public function removeFromFavorites(Request $request)
    {
        try {
            $request->validate(['index' => 'required|integer|min:0']);

            $result = $this->bibleService->removeFromFavorites(Auth::user(), $request->index);

            return response()->json($result, $result['success'] ? 200 : 404);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Índice inválido.'], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao remover favorito: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao remover favorito.'], 500);
        }
    }

    /**
     * Clear all favorite verses for the user.
     */
    public function clearFavorites()
    {
        try {
            $this->bibleService->clearFavorites(Auth::user());
            return response()->json(['success' => true, 'message' => 'Todos os favoritos foram removidos!']);
        } catch (\Exception $e) {
            Log::error('Erro ao limpar favoritos: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao limpar favoritos.'], 500);
        }
    }

    /**
     * Display user's reading history.
     */
    public function readingHistory()
    {
        $historico = $this->bibleService->getReadingHistory(Auth::user());
        return view('member.bible.history', compact('historico'));
    }
}