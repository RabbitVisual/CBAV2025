<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HomeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * Display the application's homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $data = $this->homeService->getHomepageData();
            return view('welcome', $data);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar a página inicial: ' . $e->getMessage());
            
            // Retorna uma view de erro ou uma com dados mínimos para não quebrar a página
            return view('welcome', [
                'configuracoes' => ['igreja_nome' => 'Erro ao carregar'],
                'ministerios' => collect(),
                'campanhas' => collect(),
                'aniversariantes' => collect(),
                'estatisticas' => [],
            ])->with('error', 'Não foi possível carregar os dados da página inicial. Tente novamente mais tarde.');
        }
    }

    /**
     * Display the credits page.
     *
     * @return \Illuminate\View\View
     */
    public function credits(): \Illuminate\View\View
    {
        $dados = Config::get('credits');
        return view('creditos', compact('dados'));
    }
}