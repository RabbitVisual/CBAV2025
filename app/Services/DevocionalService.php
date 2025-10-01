<?php

namespace App\Services;

use App\Models\Devocional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DevocionalService
{
    private $bibleService;

    public function __construct()
    {
        $this->bibleService = new BibleService();
    }

    /**
     * Obter devocional do dia
     */
    public function getDevocionalDoDia()
    {
        $cacheKey = 'devocional_dia_' . Carbon::today()->format('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function () {
            $devocional = Devocional::ativos()
                ->porTipo('devocional')
                ->porData(Carbon::today())
                ->orderBy('ordem')
                ->first();

            if (!$devocional) {
                $devocional = Devocional::ativos()
                    ->porTipo('devocional')
                    ->orderBy('data', 'desc')
                    ->first();
            }

            if (!$devocional) {
                return [
                    'titulo' => 'Confiança em Deus',
                    'texto' => 'Confia no Senhor de todo o teu coração e não te estribes no teu próprio entendimento.',
                    'versiculo' => 'Provérbios 3:5-6',
                    'reflexao' => 'Quando confiamos em Deus, Ele nos guia pelos caminhos certos.',
                    'data' => Carbon::today()->format('Y-m-d'),
                    'tipo' => 'devocional',
                    'source' => 'padrao'
                ];
            }

            return [
                'id' => $devocional->id,
                'titulo' => $devocional->titulo,
                'texto' => $devocional->texto,
                'versiculo' => $devocional->versiculo,
                'reflexao' => $devocional->reflexao,
                'data' => $devocional->data->format('Y-m-d'),
                'tipo' => $devocional->tipo,
                'source' => 'banco'
            ];
        });
    }

    /**
     * Obter versículo do dia
     */
    public function getVersiculoDoDia()
    {
        $cacheKey = 'versiculo_dia_' . Carbon::today()->format('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function () {
            // Primeiro tentar do banco de dados
            $versiculo = Devocional::ativos()
                ->porTipo('versiculo')
                ->porData(Carbon::today())
                ->orderBy('ordem')
                ->first();

            if (!$versiculo) {
                $versiculo = Devocional::ativos()
                    ->porTipo('versiculo')
                    ->orderBy('data', 'desc')
                    ->first();
            }

            if ($versiculo) {
                return [
                    'texto' => $versiculo->texto,
                    'referencia' => $versiculo->versiculo,
                    'versao' => 'ARA',
                    'fonte' => 'banco'
                ];
            }

            // Se não houver no banco, usar a Bíblia offline
            if ($this->bibleService->isAvailable()) {
                $versiculoOffline = $this->bibleService->getVerseOfTheDay();
                if ($versiculoOffline) {
                    return [
                        'texto' => $versiculoOffline['text'],
                        'referencia' => $versiculoOffline['reference'],
                        'versao' => $versiculoOffline['version_abbrev'],
                        'fonte' => 'offline'
                    ];
                }
            }

            // Versículo padrão
            return [
                'texto' => 'Porque para Deus nada é impossível.',
                'referencia' => 'Lucas 1:37',
                'versao' => 'ARA',
                'fonte' => 'padrao'
            ];
        });
    }

    /**
     * Obter oração do dia
     */
    public function getOracaoDoDia()
    {
        $cacheKey = 'oracao_dia_' . Carbon::today()->format('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function () {
            $oracao = Devocional::ativos()
                ->porTipo('oracao')
                ->porData(Carbon::today())
                ->orderBy('ordem')
                ->first();

            if (!$oracao) {
                $oracao = Devocional::ativos()
                    ->porTipo('oracao')
                    ->orderBy('data', 'desc')
                    ->first();
            }

            if ($oracao) {
                return [
                    'texto' => $oracao->texto,
                    'titulo' => $oracao->titulo,
                    'data' => $oracao->data->format('Y-m-d'),
                    'fonte' => 'banco'
                ];
            }

            return [
                'texto' => 'Senhor, hoje quero agradecer por mais um dia de vida. Ajuda-me a ser uma bênção para outras pessoas e a viver segundo a Tua vontade. Em nome de Jesus, amém.',
                'titulo' => 'Oração do Dia',
                'data' => Carbon::today()->format('Y-m-d'),
                'fonte' => 'padrao'
            ];
        });
    }

    /**
     * Buscar versículo na Bíblia offline
     */
    public function buscarVersiculo($referencia, $versao = null)
    {
        if (!$this->bibleService->isAvailable()) {
            return null;
        }

        return $this->bibleService->getVerse($referencia, $versao);
    }

    /**
     * Buscar versículos por palavra-chave
     */
    public function buscarPorPalavraChave($palavra, $versao = null, $limit = 20)
    {
        if (!$this->bibleService->isAvailable()) {
            return [];
        }

        return $this->bibleService->searchByKeyword($palavra, $limit, $versao);
    }

    /**
     * Obter versículo aleatório
     */
    public function getVersiculoAleatorio($versao = null)
    {
        if (!$this->bibleService->isAvailable()) {
            return null;
        }

        return $this->bibleService->getRandomVerse($versao);
    }

    /**
     * Obter capítulo completo
     */
    public function getCapitulo($livro, $capitulo, $versao = null)
    {
        if (!$this->bibleService->isAvailable()) {
            return null;
        }

        return $this->bibleService->getChapter($livro, $capitulo, $versao);
    }

    /**
     * Obter estatísticas do sistema
     */
    public function getEstatisticas()
    {
        $bibleStats = $this->bibleService->getStatistics();
        $devocionalStats = [
            'total_devocionais' => Devocional::count(),
            'devocionais_ativos' => Devocional::where('ativo', true)->count(),
            'devocionais_hoje' => Devocional::where('data', Carbon::today())->count(),
            'tipos' => [
                'devocional' => Devocional::where('tipo', 'devocional')->count(),
                'versiculo' => Devocional::where('tipo', 'versiculo')->count(),
                'oracao' => Devocional::where('tipo', 'oracao')->count()
            ]
        ];

        return [
            'biblia' => $bibleStats,
            'devocional' => $devocionalStats,
            'biblia_disponivel' => $this->bibleService->isAvailable(),
            'versoes_disponiveis' => $this->bibleService->getVersions()
        ];
    }

    /**
     * Criar devocional com versículo da Bíblia offline
     */
    public function criarComVersiculoOffline($data, $referencia, $versao = null)
    {
        $versiculo = $this->buscarVersiculo($referencia, $versao);
        
        if ($versiculo) {
            return Devocional::create([
                'titulo' => 'Versículo do Dia',
                'texto' => $versiculo['text'],
                'versiculo' => $versiculo['reference'],
                'reflexao' => 'Que este versículo seja uma fonte de inspiração para o seu dia.',
                'data' => $data,
                'tipo' => 'versiculo',
                'ativo' => true,
                'ordem' => 0,
                'dados_extras' => [
                    'versao' => $versiculo['version_abbrev'],
                    'fonte' => 'offline'
                ]
            ]);
        }
        
        return null;
    }

    /**
     * Verificar status do sistema
     */
    public function verificarStatus()
    {
        return [
            'biblia_offline' => $this->bibleService->isAvailable(),
            'versoes_disponiveis' => count($this->bibleService->getVersions()),
            'devocionais_ativos' => Devocional::where('ativo', true)->count(),
            'devocional_hoje' => Devocional::where('data', Carbon::today())->count() > 0
        ];
    }

    /**
     * Limpar cache do sistema
     */
    public function limparCache()
    {
        $today = Carbon::today()->format('Y-m-d');
        Cache::forget('devocional_dia_' . $today);
        Cache::forget('versiculo_dia_' . $today);
        Cache::forget('oracao_dia_' . $today);
        
        // Limpar cache da Bíblia
        foreach ($this->bibleService->getVersions() as $version => $info) {
            Cache::forget("bible_data_{$version}");
        }
    }
} 