<?php

namespace App\Services;

use App\Models\Configuracao;
use App\Models\Ministerio;
use App\Models\User;
use App\Models\Campanha;
use App\Models\Transacao;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HomeService
{
    /**
     * Get all necessary data for the homepage.
     *
     * @return array
     */
    public function getHomepageData(): array
    {
        return Cache::remember('homepage_data', now()->addMinutes(10), function () {
            return [
                'configuracoes' => $this->getConfigurations(),
                'ministerios' => $this->getActiveMinistries(),
                'campanhas' => $this->getActiveCampaigns(),
                'aniversariantes' => $this->getBirthdayMembers(),
                'estatisticas' => $this->getChurchStatistics(),
            ];
        });
    }

    /**
     * Fetches all required configurations for the homepage.
     *
     * @return array
     */
    private function getConfigurations(): array
    {
        $configKeys = [
            'igreja_nome', 'igreja_slogan', 'igreja_endereco', 'igreja_telefone', 'igreja_email', 'igreja_website',
            'igreja_facebook', 'igreja_instagram', 'igreja_youtube', 'igreja_whatsapp',
            'culto_domingo_manha_titulo', 'culto_domingo_manha_horario', 'culto_domingo_manha_descricao',
            'culto_domingo_noite_titulo', 'culto_domingo_noite_horario', 'culto_domingo_noite_descricao',
            'culto_quarta_titulo', 'culto_quarta_horario', 'culto_quarta_descricao',
            'hero_titulo', 'hero_subtitulo', 'home_botao_texto', 'home_botao_link',
            'secao_sobre_ativa', 'secao_ministerios_ativa', 'secao_cultos_ativa', 'secao_contato_ativa',
            'secao_doacao_ativa', 'secao_eventos_ativa', 'secao_aniversariantes_ativa', 'aniversariantes_mostrar',
            'cor_primaria', 'cor_secundaria', 'cor_destaque', 'cor_texto', 'logo',
            'doacao_ativa', 'doacao_titulo', 'doacao_subtitulo',
            'escola_dominical_titulo', 'escola_dominical_horario', 'escola_dominical_descricao', 'escola_dominical_ativa',
            'header_texto_area_membro', 'footer_copyright_texto'
        ];

        $configuracoes = [];
        foreach ($configKeys as $key) {
            // The Configuracao::get method already handles defaults and caching
            $configuracoes[$key] = Configuracao::get($key);
        }
        return $configuracoes;
    }

    /**
     * Fetches active ministries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getActiveMinistries()
    {
        return Ministerio::where('ativo', true)
            ->withCount('users')
            ->orderBy('nome')
            ->get();
    }

    /**
     * Fetches active campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getActiveCampaigns()
    {
        return Campanha::where('ativo', true)
            ->where('status', 'ativa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Fetches birthday members of the current month if the setting is enabled.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getBirthdayMembers()
    {
        if (!Configuracao::get('aniversariantes_mostrar', false)) {
            return collect();
        }

        return User::whereHas('profile', function ($query) {
            $query->whereMonth('data_nascimento', '=', Carbon::now()->month);
        })->with('profile')->active()
          ->orderByRaw('DAY(profiles.data_nascimento)')
          ->limit(6)
          ->get();
    }

    /**
     * Fetches basic church statistics.
     *
     * @return array
     */
    private function getChurchStatistics(): array
    {
        return [
            'total_membros' => User::active()->count(),
            'total_ministerios' => Ministerio::where('ativo', true)->count(),
            'doacoes_mes' => Transacao::where('tipo', 'entrada')
                ->where('status', 'confirmado')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('valor'),
        ];
    }
}