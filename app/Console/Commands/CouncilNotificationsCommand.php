<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conselho;
use App\Services\NotificacaoService;
use App\Helpers\CouncilSettingsHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CouncilNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'council:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar notificações automáticas para reuniões do conselho';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando envio de notificações do conselho...');

        // Obter antecedência para notificação (em horas)
        $antecedencia = CouncilSettingsHelper::getNotificationAdvance();
        
        // Calcular data/hora limite para notificação
        $limitDate = Carbon::now()->addHours($antecedencia);
        
        // Buscar reuniões agendadas que estão próximas
        $reunioesProximas = Conselho::where('status', 'agendada')
            ->where('data_reuniao', '<=', $limitDate->toDateString())
            ->whereTime('hora_inicio', '<=', $limitDate->toTimeString())
            ->whereDoesntHave('notificacoes', function($query) {
                $query->where('tipo', 'reminder')
                      ->where('created_at', '>=', Carbon::now()->subHours(24));
            })
            ->with(['participantes.user'])
            ->get();

        $count = 0;
        
        foreach ($reunioesProximas as $conselho) {
            $this->sendReminderNotifications($conselho);
            $count++;
        }

        // Verificar reuniões em atraso
        $reunioesAtrasadas = Conselho::where('status', 'agendada')
            ->where('data_reuniao', '<', Carbon::now()->toDateString())
            ->orWhere(function($query) {
                $query->where('data_reuniao', '=', Carbon::now()->toDateString())
                      ->whereTime('hora_inicio', '<', Carbon::now()->toTimeString());
            })
            ->with(['participantes.user'])
            ->get();

        foreach ($reunioesAtrasadas as $conselho) {
            $this->sendDelayNotifications($conselho);
            $count++;
        }

        // Verificar votações pendentes
        $this->checkPendingVotations();

        $this->info("Processadas {$count} reuniões para notificações.");
        Log::info("CouncilNotificationsCommand executado: {$count} reuniões processadas");

        return 0;
    }

    /**
     * Enviar notificações de lembrete
     */
    private function sendReminderNotifications(Conselho $conselho)
    {
        $participantes = $conselho->participantes()->with('user')->get();
        
        $dataFormatada = $conselho->data_reuniao->format('d/m/Y');
        $horaFormatada = $conselho->hora_inicio;

        foreach ($participantes as $participante) {
            if ($participante->user) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'info',
                    'Lembrete: Reunião do Conselho',
                    "Reunião do conselho agendada para hoje ({$dataFormatada}) às {$horaFormatada}. Local: {$conselho->local}",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'alta',
                        'tipo' => 'reminder',
                        'conselho_id' => $conselho->id,
                        'acao_url' => route('admin.council.show', $conselho),
                        'acao_texto' => 'Ver Reunião'
                    ]
                );
            }
        }

        $this->line("✓ Lembretes enviados para reunião: {$conselho->titulo}");
    }

    /**
     * Enviar notificações de atraso
     */
    private function sendDelayNotifications(Conselho $conselho)
    {
        // Notificar organizadores sobre reunião atrasada
        $organizadores = \App\Models\User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Super Admin', 'Pastor', 'Líder']);
        })->get();

        foreach ($organizadores as $organizador) {
            NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                $organizador,
                'warning',
                'Reunião do Conselho em Atraso',
                "A reunião '{$conselho->titulo}' estava agendada para {$conselho->data_reuniao->format('d/m/Y')} e ainda não foi iniciada.",
                [
                    'categoria' => 'conselho',
                    'prioridade' => 'urgente',
                    'tipo' => 'delay_alert',
                    'conselho_id' => $conselho->id,
                    'acao_url' => route('admin.council.show', $conselho),
                    'acao_texto' => 'Gerenciar Reunião'
                ]
            );
        }

        $this->line("⚠ Alertas de atraso enviados para: {$conselho->titulo}");
    }

    /**
     * Verificar votações pendentes
     */
    private function checkPendingVotations()
    {
        $votacoesPendentes = \App\Models\VotacaoConselho::where('status', 'em_andamento')
            ->where('tempo_limite', '>', 0)
            ->whereRaw('TIMESTAMPDIFF(MINUTE, data_inicio, NOW()) >= tempo_limite')
            ->with(['conselho.participantes.user'])
            ->get();

        foreach ($votacoesPendentes as $votacao) {
            // Notificar sobre votação expirando
            $participantes = $votacao->conselho->participantes()->with('user')->get();
            
            foreach ($participantes as $participante) {
                if ($participante->user) {
                    NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                        $participante->user,
                        'warning',
                        'Votação Expirando',
                        "A votação '{$votacao->titulo}' está expirando. Vote agora!",
                        [
                            'categoria' => 'conselho',
                            'prioridade' => 'urgente',
                            'tipo' => 'voting_expiring',
                            'conselho_id' => $votacao->conselho_id,
                            'votacao_id' => $votacao->id,
                            'acao_url' => route('admin.council.voting.show', [$votacao->conselho, $votacao]),
                            'acao_texto' => 'Votar Agora'
                        ]
                    );
                }
            }

            $this->line("⏰ Alertas de votação expirando enviados para: {$votacao->titulo}");
        }
    }
} 