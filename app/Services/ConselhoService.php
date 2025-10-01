<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Conselho;
use App\Models\PautaConselho;
use App\Models\VotacaoConselho;
use App\Models\ParticipanteConselho;
use App\Models\TemplatePauta;
use App\Models\User;
use App\Helpers\CouncilSettingsHelper;
use App\Events\CouncilMeetingScheduled;
use App\Events\CouncilMeetingStarted;
use App\Events\CouncilMeetingFinished;
use App\Events\CouncilQuorumAlert;
use App\Events\CouncilAgendaItemAdded;
use App\Events\CouncilVotingStarted;
use App\Events\CouncilVotingFinished;
use App\Models\VotoConselho;

class ConselhoService
{
    /**
     * Busca os dados necessários para o dashboard do conselho.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        $user = Auth::user();

        $totalReunioes = Conselho::count();
        $reunioesAtivas = Conselho::ativas()->count();
        $votacoesPendentes = VotacaoConselho::pendentes()->count();
        $pautasPendentes = PautaConselho::pendentes()->count();
        $conselhoAtivo = Conselho::ativas()->first();
        $reunioesRecentes = Conselho::with(['criadoPor', 'presidente', 'secretario'])
            ->orderBy('data_reuniao', 'desc')
            ->limit(5)
            ->get();
        $proximasReunioes = Conselho::with(['criadoPor', 'presidente', 'secretario'])
            ->proximas()
            ->limit(3)
            ->get();
        $votacoesEmAndamento = VotacaoConselho::with(['conselho', 'pauta'])
            ->emAndamento()
            ->get();
        $minhasParticipacoes = ParticipanteConselho::with(['conselho'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return compact(
            'totalReunioes', 'reunioesAtivas', 'votacoesPendentes', 'pautasPendentes', 'conselhoAtivo',
            'reunioesRecentes', 'proximasReunioes', 'votacoesEmAndamento', 'minhasParticipacoes'
        );
    }

    /**
     * Retorna uma lista paginada e filtrada de reuniões.
     *
     * @param Request $request
     * @return array
     */
    public function getReunioes(Request $request): array
    {
        $query = Conselho::with(['criadoPor', 'presidente', 'secretario', 'participantes']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('titulo', 'like', "%{$search}%")->orWhere('descricao', 'like', "%{$search}%"));
        }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('tipo')) { $query->where('tipo', $request->tipo); }
        if ($request->filled('data_inicio')) { $query->where('data_reuniao', '>=', $request->data_inicio); }
        if ($request->filled('data_fim')) { $query->where('data_reuniao', '<=', $request->data_fim); }

        $sort = $request->get('sort', 'data_desc');
        $query->orderBy(
            match ($sort) {
                'data_asc' => 'data_reuniao',
                'titulo' => 'titulo',
                'status' => 'status',
                default => 'data_reuniao',
            },
            in_array($sort, ['data_asc', 'titulo', 'status']) ? 'asc' : 'desc'
        );

        $reunioes = $query->paginate(15);
        $estatisticas = [
            'total' => Conselho::count(),
            'agendadas' => Conselho::where('status', 'agendada')->count(),
            'em_andamento' => Conselho::where('status', 'em_andamento')->count(),
            'finalizadas' => Conselho::where('status', 'finalizada')->count(),
        ];

        return compact('reunioes', 'estatisticas');
    }

    /**
     * Retorna os dados necessários para o formulário de reunião.
     *
     * @return array
     */
    public function getReuniaoFormData(): array
    {
        $configuracoes = CouncilSettingsHelper::getAll();
        $membros = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Pastor', 'Líder', 'Super Admin', 'Conselheiro']))
            ->orWhereHas('cargos', fn($q) => $q->where('sistema', true))
            ->orderBy('name')
            ->get();
        $templates = TemplatePauta::where('status', 'ativo')->orderBy('nome')->get();
        return compact('membros', 'configuracoes', 'templates');
    }

    /**
     * Cria uma nova reunião do conselho.
     *
     * @param array $data
     * @return Conselho
     */
    public function createReuniao(array $data): Conselho
    {
        return DB::transaction(function () use ($data) {
            $conselho = Conselho::create([
                'titulo' => $data['titulo'],
                'descricao' => $data['descricao'],
                'data_reuniao' => $data['data_reuniao'],
                'hora_inicio' => $data['hora_inicio'],
                'hora_fim' => $data['hora_fim'],
                'local' => $data['local'],
                'tipo' => $data['tipo'],
                'quorum_minimo' => $data['quorum_minimo'],
                'criado_por' => Auth::id(),
                'presidente_id' => $data['presidente_id'],
                'secretario_id' => $data['secretario_id'],
                'observacoes' => $data['observacoes'],
                'template_id' => $data['template_id']
            ]);

            foreach ($data['participantes'] as $participanteId) {
                $funcao = 'membro';
                if ($participanteId == $data['presidente_id']) $funcao = 'presidente';
                if ($participanteId == $data['secretario_id']) $funcao = 'secretario';
                ParticipanteConselho::create(['conselho_id' => $conselho->id, 'user_id' => $participanteId, 'funcao' => $funcao]);
            }

            if (!empty($data['template_id'])) {
                $template = TemplatePauta::find($data['template_id']);
                if ($template && $template->itens_pauta) {
                    $itens = json_decode($template->itens_pauta, true);
                    if (is_array($itens)) {
                        foreach ($itens as $index => $item) {
                            PautaConselho::create(['conselho_id' => $conselho->id, 'titulo' => $item, 'ordem' => $index + 1]);
                        }
                    }
                }
            }

            event(new CouncilMeetingScheduled($conselho, Auth::user()));

            return $conselho;
        });
    }

    /**
     * Atualiza uma reunião do conselho.
     *
     * @param Conselho $conselho
     * @param array $data
     * @return Conselho
     */
    public function updateReuniao(Conselho $conselho, array $data): Conselho
    {
        DB::transaction(function () use ($conselho, $data) {
            $conselho->update($data);
            $conselho->participantes()->delete();
            foreach ($data['participantes'] as $participanteId) {
                $funcao = 'membro';
                if ($participanteId == $data['presidente_id']) $funcao = 'presidente';
                if ($participanteId == $data['secretario_id']) $funcao = 'secretario';
                ParticipanteConselho::create(['conselho_id' => $conselho->id, 'user_id' => $participanteId, 'funcao' => $funcao]);
            }
        });
        return $conselho;
    }

    /**
     * Inicia uma reunião do conselho.
     *
     * @param Conselho $conselho
     * @return void
     */
    public function iniciarReuniao(Conselho $conselho): void
    {
        if (!$conselho->podeIniciar()) {
            throw new \Exception('Esta reunião não pode ser iniciada.');
        }

        $conselho->update(['status' => 'em_andamento']);
        event(new CouncilMeetingStarted($conselho));
        $this->checkQuorumAndAlert($conselho);
    }

    /**
     * Finaliza uma reunião do conselho.
     *
     * @param Conselho $conselho
     * @return void
     */
    public function finalizarReuniao(Conselho $conselho): void
    {
        if (!$conselho->podeFinalizar()) {
            throw new \Exception('Esta reunião não pode ser finalizada.');
        }

        $conselho->update(['status' => 'finalizada', 'hora_fim' => now()]);
        event(new CouncilMeetingFinished($conselho));
    }

    /**
     * Cancela uma reunião do conselho.
     *
     * @param Conselho $conselho
     * @return void
     */
    public function cancelarReuniao(Conselho $conselho): void
    {
        if (!$conselho->podeCancelar()) {
            throw new \Exception('Esta reunião não pode ser cancelada.');
        }
        $conselho->update(['status' => 'cancelada']);
    }

    /**
     * Retorna os dados para a página de presença.
     *
     * @param Conselho $conselho
     * @return array
     */
    public function getPresencaData(Conselho $conselho): array
    {
        $conselho->load('participantes.user');
        $participantes = $conselho->participantes;
        $totalParticipantes = $participantes->count();
        $presentes = $participantes->where('presente', true)->count();

        $estatisticas = [
            'total_participantes' => $totalParticipantes,
            'presentes' => $presentes,
            'ausentes' => $totalParticipantes - $presentes,
            'quorum_percentual' => $totalParticipantes > 0 ? round(($presentes / $totalParticipantes) * 100, 1) : 0
        ];

        return compact('conselho', 'participantes', 'estatisticas');
    }

    /**
     * Atualiza a presença dos participantes.
     *
     * @param Conselho $conselho
     * @param array $data
     * @return void
     */
    public function atualizarPresenca(Conselho $conselho, array $data): void
    {
        foreach ($data['participantes'] as $participanteData) {
            $participante = ParticipanteConselho::find($participanteData['id']);
            if ($participante) {
                $participante->update([
                    'presente' => $participanteData['presente'],
                    'hora_chegada' => $participanteData['hora_chegada'] ?? null,
                    'observacoes' => $participanteData['observacoes'] ?? null
                ]);
            }
        }
        if ($conselho->status === 'em_andamento') {
            $this->checkQuorumAndAlert($conselho);
        }
    }

    /**
     * Verifica o quórum e dispara um alerta se estiver abaixo do necessário.
     *
     * @param Conselho $conselho
     * @return void
     */
    private function checkQuorumAndAlert(Conselho $conselho): void
    {
        $totalParticipantes = $conselho->participantes()->count();
        if ($totalParticipantes == 0) return;

        $presentesCount = $conselho->participantes()->where('presente', true)->count();
        $currentQuorum = round(($presentesCount / $totalParticipantes) * 100, 1);
        $requiredQuorum = $conselho->quorum_minimo ?? CouncilSettingsHelper::get('quorum_padrao', 50);

        if ($currentQuorum < $requiredQuorum) {
            event(new CouncilQuorumAlert($conselho, $currentQuorum, $requiredQuorum));
        }
    }

    // --- MÉTODOS DE PAUTAS ---

    public function getPautasData(Conselho $conselho): array
    {
        $conselho->load(['pautas' => fn($q) => $q->orderBy('ordem'), 'pautas.responsavel']);
        $membros = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Pastor', 'Líder', 'Super Admin']))->get();
        $pautas = $conselho->pautas()->with('responsavel')->orderBy('ordem')->paginate(10);
        $estatisticas = [
            'total_pautas' => $conselho->pautas()->count(),
            'pautas_pendentes' => $conselho->pautas()->where('status', 'pendente')->count(),
            'pautas_discussao' => $conselho->pautas()->where('status', 'em_discussao')->count(),
            'pautas_finalizadas' => $conselho->pautas()->where('status', 'concluida')->count(),
            'tempo_estimado_total' => $conselho->pautas()->sum('tempo_estimado')
        ];
        return compact('conselho', 'membros', 'pautas', 'estatisticas');
    }

    public function addPauta(Conselho $conselho, array $data): PautaConselho
    {
        $pauta = $conselho->pautas()->create($data);
        event(new CouncilAgendaItemAdded($conselho, $pauta));
        return $pauta;
    }

    public function iniciarDiscussaoPauta(PautaConselho $pauta): void
    {
        if (!$pauta->podeIniciarDiscussao()) {
            throw new \Exception('Esta pauta não pode ser iniciada.');
        }
        $pauta->update(['status' => 'em_discussao']);
    }

    public function finalizarPauta(PautaConselho $pauta, array $data): void
    {
        $pauta->update([
            'status' => $data['status'],
            'decisao_final' => $data['decisao_final'],
            'data_decisao' => now()
        ]);
    }

    // --- MÉTODOS DE VOTAÇÕES ---

    public function getVotacoesData(Conselho $conselho): array
    {
        $votacoes = $conselho->votacoes()->with(['pauta', 'votos'])->paginate(10);
        $estatisticas = [
            'total_votacoes' => $conselho->votacoes()->count(),
            'votacoes_pendentes' => $conselho->votacoes()->where('status', 'pendente')->count(),
            'votacoes_andamento' => $conselho->votacoes()->where('status', 'em_andamento')->count(),
            'votacoes_finalizadas' => $conselho->votacoes()->where('status', 'finalizada')->count()
        ];
        return compact('conselho', 'votacoes', 'estatisticas');
    }

    public function createVotacao(Conselho $conselho, array $data): VotacaoConselho
    {
        return $conselho->votacoes()->create($data);
    }

    public function iniciarVotacao(VotacaoConselho $votacao): void
    {
        if (!$votacao->podeIniciar()) {
            throw new \Exception('Esta votação não pode ser iniciada.');
        }
        $votacao->update(['status' => 'em_andamento', 'data_inicio' => now()]);
        event(new CouncilVotingStarted($votacao->conselho, $votacao));
    }

    public function finalizarVotacao(VotacaoConselho $votacao): void
    {
        if (!$votacao->podeFinalizar()) {
            throw new \Exception('Esta votação não pode ser finalizada.');
        }
        $votacao->calcularResultado();
        $votacao->update(['status' => 'finalizada', 'data_fim' => now()]);
        $results = ['resultado' => $votacao->resultado, 'votos_sim' => $votacao->votos_sim, 'votos_nao' => $votacao->votos_nao, 'abstencoes' => $votacao->abstencoes, 'aprovada' => $votacao->aprovada];
        event(new CouncilVotingFinished($votacao->conselho, $votacao, $results));
    }

    public function registrarVoto(VotacaoConselho $votacao, array $data, User $user): void
    {
        if (VotoConselho::where('votacao_id', $votacao->id)->where('user_id', $user->id)->exists()) {
            throw new \Exception('Você já votou nesta votação.');
        }
        if (!ParticipanteConselho::where('conselho_id', $votacao->conselho_id)->where('user_id', $user->id)->exists()) {
            throw new \Exception('Você não é participante desta reunião.');
        }

        VotoConselho::create([
            'votacao_id' => $votacao->id,
            'user_id' => $user->id,
            'voto' => $data['voto'],
            'justificativa' => $data['justificativa'],
            'data_voto' => now(),
            'voto_anonimo' => $votacao->voto_secreto
        ]);

        $votacao->increment('total_votos');
        if ($data['voto'] === 'favoravel') $votacao->increment('votos_favoraveis');
        elseif ($data['voto'] === 'contrario') $votacao->increment('votos_contrarios');
        elseif ($data['voto'] === 'abstencao') $votacao->increment('votos_abstencao');
    }
}