<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Events\CouncilMeetingScheduled;
use App\Events\CouncilMeetingStarted;
use App\Events\CouncilMeetingFinished;
use App\Events\CouncilVotingStarted;
use App\Events\CouncilVotingFinished;
use App\Events\CouncilAgendaItemAdded;
use App\Events\CouncilQuorumAlert;
use App\Services\NotificacaoService;
use App\Helpers\CouncilSettingsHelper;
use App\Models\User;

class CouncilNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'notifications';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle council meeting scheduled event.
     */
    public function handleMeetingScheduled(CouncilMeetingScheduled $event): void
    {
        if (!CouncilSettingsHelper::shouldNotifyNewMeeting()) {
            return;
        }

        $council = $event->council;
        $scheduledBy = $event->scheduledBy;

        // Notificar todos os participantes
        $participantes = $council->participantes()->with('user')->get();
        
        foreach ($participantes as $participante) {
            if ($participante->user && $participante->user->id !== $scheduledBy->id) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'info',
                    'Nova Reunião do Conselho Agendada',
                    "Uma nova reunião do conselho foi agendada para {$council->data_reuniao->format('d/m/Y')} às {$council->hora_inicio}.",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'alta',
                        'conselho_id' => $council->id,
                        'acao_url' => route('admin.council.show', $council),
                        'acao_texto' => 'Ver Reunião'
                    ]
                );
            }
        }

        // Notificar administradores do sistema
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Super Admin', 'Pastor']);
        })->get();

        foreach ($admins as $admin) {
            if ($admin->id !== $scheduledBy->id) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $admin,
                    'info',
                    'Nova Reunião do Conselho',
                    "Reunião agendada por {$scheduledBy->name} para {$council->data_reuniao->format('d/m/Y')}.",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'normal',
                        'conselho_id' => $council->id
                    ]
                );
            }
        }

        Log::info("Notificações enviadas para reunião do conselho {$council->id}");
    }

    /**
     * Handle council meeting started event.
     */
    public function handleMeetingStarted(CouncilMeetingStarted $event): void
    {
        $council = $event->council;

        // Notificar participantes ausentes
        $participantesAusentes = $council->participantes()
            ->where('presente', false)
            ->orWhereNull('presente')
            ->with('user')
            ->get();

        foreach ($participantesAusentes as $participante) {
            if ($participante->user) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'warning',
                    'Reunião do Conselho Iniciada',
                    'A reunião do conselho foi iniciada. Sua presença é necessária.',
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'urgente',
                        'conselho_id' => $council->id,
                        'acao_url' => route('admin.council.show', $council),
                        'acao_texto' => 'Marcar Presença'
                    ]
                );
            }
        }

        Log::info("Reunião do conselho {$council->id} iniciada - notificações enviadas");
    }

    /**
     * Handle council meeting finished event.
     */
    public function handleMeetingFinished(CouncilMeetingFinished $event): void
    {
        $council = $event->council;

        // Notificar todos os participantes sobre o fim da reunião
        $participantes = $council->participantes()->with('user')->get();
        
        foreach ($participantes as $participante) {
            if ($participante->user) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'success',
                    'Reunião do Conselho Finalizada',
                    'A reunião do conselho foi finalizada. Relatório disponível para consulta.',
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'normal',
                        'conselho_id' => $council->id,
                        'acao_url' => route('admin.council.relatorio', $council),
                        'acao_texto' => 'Ver Relatório'
                    ]
                );
            }
        }

        Log::info("Reunião do conselho {$council->id} finalizada - notificações enviadas");
    }

    /**
     * Handle council voting started event.
     */
    public function handleVotingStarted(CouncilVotingStarted $event): void
    {
        if (!CouncilSettingsHelper::shouldNotifyVoting()) {
            return;
        }

        $council = $event->council;
        $voting = $event->voting;

        // Notificar todos os participantes presentes
        $participantesPresentes = $council->participantes()
            ->where('presente', true)
            ->with('user')
            ->get();

        foreach ($participantesPresentes as $participante) {
            if ($participante->user) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'info',
                    'Nova Votação Iniciada',
                    "Votação iniciada: {$voting->titulo}. Seu voto é necessário.",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'alta',
                        'conselho_id' => $council->id,
                        'votacao_id' => $voting->id,
                        'acao_url' => route('admin.council.voting.show', [$council, $voting]),
                        'acao_texto' => 'Votar'
                    ]
                );
            }
        }

        Log::info("Votação {$voting->id} iniciada no conselho {$council->id}");
    }

    /**
     * Handle council voting finished event.
     */
    public function handleVotingFinished(CouncilVotingFinished $event): void
    {
        if (!CouncilSettingsHelper::shouldNotifyResults()) {
            return;
        }

        $council = $event->council;
        $voting = $event->voting;
        $results = $event->results;

        // Notificar todos os participantes sobre o resultado
        $participantes = $council->participantes()->with('user')->get();
        
        foreach ($participantes as $participante) {
            if ($participante->user) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $participante->user,
                    'info',
                    'Resultado da Votação',
                    "Votação finalizada: {$voting->titulo}. Resultado: {$results['resultado']}",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'normal',
                        'conselho_id' => $council->id,
                        'votacao_id' => $voting->id,
                        'resultado' => $results,
                        'acao_url' => route('admin.council.voting.show', [$council, $voting]),
                        'acao_texto' => 'Ver Resultado'
                    ]
                );
            }
        }

        Log::info("Resultado da votação {$voting->id} enviado para conselho {$council->id}");
    }

    /**
     * Handle council agenda item added event.
     */
    public function handleAgendaItemAdded(CouncilAgendaItemAdded $event): void
    {
        $council = $event->council;
        $agendaItem = $event->agendaItem;

        // Notificar responsável pela pauta se não for o mesmo que adicionou
        if ($agendaItem->responsavel_id && $agendaItem->responsavel_id !== auth()->id()) {
            $responsavel = User::find($agendaItem->responsavel_id);
            if ($responsavel) {
                NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                    $responsavel,
                    'info',
                    'Nova Pauta Adicionada',
                    "Você foi designado como responsável pela pauta: {$agendaItem->titulo}",
                    [
                        'categoria' => 'conselho',
                        'prioridade' => 'normal',
                        'conselho_id' => $council->id,
                        'pauta_id' => $agendaItem->id,
                        'acao_url' => route('admin.council.agenda.show', [$council, $agendaItem]),
                        'acao_texto' => 'Ver Pauta'
                    ]
                );
            }
        }

        Log::info("Pauta {$agendaItem->id} adicionada ao conselho {$council->id}");
    }

    /**
     * Handle council quorum alert event.
     */
    public function handleQuorumAlert(CouncilQuorumAlert $event): void
    {
        $council = $event->council;
        $currentQuorum = $event->currentQuorum;
        $requiredQuorum = $event->requiredQuorum;

        // Notificar organizadores sobre falta de quórum
        $organizadores = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Super Admin', 'Pastor', 'Líder']);
        })->get();

        foreach ($organizadores as $organizador) {
            NotificacaoService::enviarNotificacaoRespeitandoPreferencias(
                $organizador,
                'warning',
                'Alerta de Quórum',
                "Quórum insuficiente para reunião. Presentes: {$currentQuorum}%. Necessário: {$requiredQuorum}%",
                [
                    'categoria' => 'conselho',
                    'prioridade' => 'urgente',
                    'conselho_id' => $council->id,
                    'quorum_atual' => $currentQuorum,
                    'quorum_necessario' => $requiredQuorum,
                    'acao_url' => route('admin.council.attendance.index', $council),
                    'acao_texto' => 'Gerenciar Presença'
                ]
            );
        }

        Log::warning("Alerta de quórum enviado para conselho {$council->id}: {$currentQuorum}% de {$requiredQuorum}%");
    }
} 