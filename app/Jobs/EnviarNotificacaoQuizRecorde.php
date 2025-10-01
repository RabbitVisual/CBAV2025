<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuizRecordeMail;
use App\Models\EbdQuizSessao;
use App\Models\User;
use App\Helpers\LogHelper;

class EnviarNotificacaoQuizRecorde implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sessao;
    public $tipoRecorde;
    public $valor;
    public $isRecordeGlobal;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(EbdQuizSessao $sessao, string $tipoRecorde, float $valor, bool $isRecordeGlobal = false)
    {
        $this->sessao = $sessao;
        $this->tipoRecorde = $tipoRecorde;
        $this->valor = $valor;
        $this->isRecordeGlobal = $isRecordeGlobal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Verificar se o usuário tem e-mail
            if (!$this->sessao->user || !$this->sessao->user->email) {
                LogHelper::error('Tentativa de enviar e-mail para usuário sem e-mail', [
                    'user_id' => $this->sessao->user_id,
                    'sessao_id' => $this->sessao->id
                ]);
                return;
            }

            // Enviar e-mail para o usuário
            Mail::to($this->sessao->user->email)
                ->send(new QuizRecordeMail(
                    $this->sessao, 
                    $this->tipoRecorde, 
                    $this->valor, 
                    $this->isRecordeGlobal
                ));

            // Se for recorde global, notificar admins
            if ($this->isRecordeGlobal) {
                $this->notificarAdmins();
            }

            // Log do sucesso
            LogHelper::audit('email_recorde_enviado', $this->sessao, [
                'user_id' => $this->sessao->user_id,
                'user_email' => $this->sessao->user->email,
                'tipo_recorde' => $this->tipoRecorde,
                'valor' => $this->valor,
                'is_global' => $this->isRecordeGlobal
            ]);

        } catch (\Exception $e) {
            LogHelper::error('Erro ao enviar e-mail de recorde', [
                'sessao_id' => $this->sessao->id,
                'user_id' => $this->sessao->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw para que o job seja marcado como falhado
            throw $e;
        }
    }

    /**
     * Notificar administradores sobre novo recorde global
     */
    private function notificarAdmins(): void
    {
        try {
            $admins = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['Super Admin', 'Admin', 'Pastor']);
            })->whereNotNull('email')->get();

            foreach ($admins as $admin) {
                // Enviar e-mail para admin
                Mail::to($admin->email)
                    ->send(new QuizRecordeMail(
                        $this->sessao, 
                        $this->tipoRecorde, 
                        $this->valor, 
                        true // isRecordeGlobal = true para admins
                    ));
            }

            LogHelper::audit('email_recorde_global_admins_enviado', $this->sessao, [
                'admins_count' => $admins->count(),
                'tipo_recorde' => $this->tipoRecorde,
                'valor' => $this->valor
            ]);

        } catch (\Exception $e) {
            LogHelper::error('Erro ao notificar admins sobre recorde global', [
                'sessao_id' => $this->sessao->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        LogHelper::error('Job de notificação de recorde falhou', [
            'sessao_id' => $this->sessao->id,
            'user_id' => $this->sessao->user_id,
            'tipo_recorde' => $this->tipoRecorde,
            'valor' => $this->valor,
            'is_global' => $this->isRecordeGlobal,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
} 