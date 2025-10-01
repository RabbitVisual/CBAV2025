<?php

namespace App\Services;

use App\Models\Devocional;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Exports\DevocionaisExport;
use Maatwebsite\Excel\Facades\Excel;

class DevocionalService
{
    protected $bibleService;

    public function __construct(BibleService $bibleService)
    {
        $this->bibleService = $bibleService;
    }

    public function getDevocionais(Request $request): array
    {
        $query = Devocional::query();

        if ($request->filled('tipo')) { $query->where('tipo', $request->tipo); }
        if ($request->filled('data')) { $query->where('data', $request->data); }
        if ($request->filled('ativo')) { $query->where('ativo', $request->ativo); }
        if ($request->filled('busca')) {
            $query->where(fn($q) => $q->where('titulo', 'like', '%' . $request->busca . '%')
                ->orWhere('texto', 'like', '%' . $request->busca . '%')
                ->orWhere('versiculo', 'like', '%' . $request->busca . '%'));
        }

        $devocionais = $query->orderBy('data', 'desc')->orderBy('tipo')->orderBy('ordem')->paginate(20);

        $stats = [
            'total' => Devocional::count(),
            'ativos' => Devocional::where('ativo', true)->count(),
            'devocionais' => Devocional::where('tipo', 'devocional')->count(),
            'versiculos' => Devocional::where('tipo', 'versiculo')->count(),
            'oracoes' => Devocional::where('tipo', 'oracao')->count(),
            'hoje' => Devocional::where('data', Carbon::today())->count(),
        ];

        return compact('devocionais', 'stats');
    }

    public function getDevocionalFormData(): array
    {
        $rawVersoes = $this->bibleService->getVersions();
        $versoes = [];
        foreach ($rawVersoes as $code => $versionInfo) {
            $versoes[] = ['code' => $code, 'name' => $versionInfo['name'], 'abbrev' => $versionInfo['abbrev']];
        }
        return compact('versoes');
    }

    public function createDevocional(array $data): Devocional
    {
        $devocional = Devocional::create($data);
        $this->limparCache();
        return $devocional;
    }

    public function updateDevocional(Devocional $devocional, array $data): bool
    {
        $updated = $devocional->update($data);
        $this->limparCache();
        return $updated;
    }

    public function deleteDevocional(Devocional $devocional): ?bool
    {
        $deleted = $devocional->delete();
        $this->limparCache();
        return $deleted;
    }

    public function toggleStatus(Devocional $devocional): bool
    {
        $updated = $devocional->update(['ativo' => !$devocional->ativo]);
        $this->limparCache();
        return $updated;
    }

    public function duplicar(Devocional $devocional): Devocional
    {
        $novo = $devocional->replicate();
        $novo->titulo = $devocional->titulo . ' (Cópia)';
        $novo->data = Carbon::today();
        $novo->save();
        return $novo;
    }

    public function createBatch(array $data): int
    {
        $dataInicio = Carbon::parse($data['data_inicio']);
        $dataFim = Carbon::parse($data['data_fim']);
        $criados = 0;
        $padrao = $data['padrao'] ?? false;

        for ($currentDate = $dataInicio; $currentDate->lte($dataFim); $currentDate->addDay()) {
            $existente = Devocional::where('data', $currentDate->format('Y-m-d'))->where('tipo', $data['tipo'])->exists();
            if (!$existente) {
                if ($padrao) {
                    Devocional::criarPadrao($currentDate->format('Y-m-d'), $data['tipo']);
                } else {
                    Devocional::create([
                        'titulo' => ucfirst($data['tipo']) . ' - ' . $currentDate->format('d/m/Y'),
                        'texto' => 'Texto do ' . $data['tipo'] . ' para ' . $currentDate->format('d/m/Y'),
                        'versiculo' => 'Referência bíblica',
                        'reflexao' => 'Reflexão para o dia',
                        'data' => $currentDate->format('Y-m-d'),
                        'tipo' => $data['tipo'],
                        'ativo' => true,
                        'ordem' => 0
                    ]);
                }
                $criados++;
            }
        }
        $this->limparCache();
        return $criados;
    }

    public function exportar()
    {
        return Excel::download(new DevocionaisExport(Devocional::orderBy('data', 'desc')->get()), 'devocionais.xlsx');
    }

    public function getDevocionalDoDia()
    {
        $cacheKey = 'devocional_dia_' . Carbon::today()->format('Y-m-d');
        return Cache::remember($cacheKey, 3600, function () {
            $devocional = Devocional::ativos()->porTipo('devocional')->porData(Carbon::today())->orderBy('ordem')->first()
                ?? Devocional::ativos()->porTipo('devocional')->orderBy('data', 'desc')->first();
            if (!$devocional) return ['titulo' => 'Confiança em Deus', 'texto' => 'Confia no Senhor de todo o teu coração...', 'versiculo' => 'Provérbios 3:5-6', 'reflexao' => 'Quando confiamos em Deus...', 'data' => Carbon::today()->format('Y-m-d'), 'tipo' => 'devocional', 'source' => 'padrao'];
            return ['id' => $devocional->id, 'titulo' => $devocional->titulo, 'texto' => $devocional->texto, 'versiculo' => $devocional->versiculo, 'reflexao' => $devocional->reflexao, 'data' => $devocional->data->format('Y-m-d'), 'tipo' => $devocional->tipo, 'source' => 'banco'];
        });
    }

    public function getVersiculoDoDia()
    {
        $cacheKey = 'versiculo_dia_' . Carbon::today()->format('Y-m-d');
        return Cache::remember($cacheKey, 3600, function () {
            $versiculo = Devocional::ativos()->porTipo('versiculo')->porData(Carbon::today())->orderBy('ordem')->first()
                ?? Devocional::ativos()->porTipo('versiculo')->orderBy('data', 'desc')->first();
            if ($versiculo) return ['texto' => $versiculo->texto, 'referencia' => $versiculo->versiculo, 'versao' => 'ARA', 'fonte' => 'banco'];
            if ($this->bibleService->isAvailable()) {
                $versiculoOffline = $this->bibleService->getVerseOfTheDay();
                if ($versiculoOffline) return ['texto' => $versiculoOffline['text'], 'referencia' => $versiculoOffline['reference'], 'versao' => $versiculoOffline['version_abbrev'], 'fonte' => 'offline'];
            }
            return ['texto' => 'Porque para Deus nada é impossível.', 'referencia' => 'Lucas 1:37', 'versao' => 'ARA', 'fonte' => 'padrao'];
        });
    }

    public function getOracaoDoDia()
    {
        $cacheKey = 'oracao_dia_' . Carbon::today()->format('Y-m-d');
        return Cache::remember($cacheKey, 3600, function () {
            $oracao = Devocional::ativos()->porTipo('oracao')->porData(Carbon::today())->orderBy('ordem')->first()
                ?? Devocional::ativos()->porTipo('oracao')->orderBy('data', 'desc')->first();
            if ($oracao) return ['texto' => $oracao->texto, 'titulo' => $oracao->titulo, 'data' => $oracao->data->format('Y-m-d'), 'fonte' => 'banco'];
            return ['texto' => 'Senhor, hoje quero agradecer por mais um dia de vida...', 'titulo' => 'Oração do Dia', 'data' => Carbon::today()->format('Y-m-d'), 'fonte' => 'padrao'];
        });
    }

    public function buscarVersiculo($referencia, $versao = null) { return $this->bibleService->isAvailable() ? $this->bibleService->getVerse($referencia, $versao) : null; }
    public function buscarPorPalavraChave($palavra, $versao = null, $limit = 20) { return $this->bibleService->isAvailable() ? $this->bibleService->searchByKeyword($palavra, $limit, $versao) : []; }
    public function getVersiculoAleatorio($versao = null) { return $this->bibleService->isAvailable() ? $this->bibleService->getRandomVerse($versao) : null; }
    public function getEstatisticas() { /* ... */ }
    public function verificarStatus() { /* ... */ }
    public function limparCache() {
        Cache::forget('devocional_dia_' . Carbon::today()->format('Y-m-d'));
        Cache::forget('versiculo_dia_' . Carbon::today()->format('Y-m-d'));
        Cache::forget('oracao_dia_' . Carbon::today()->format('Y-m-d'));
    }
}