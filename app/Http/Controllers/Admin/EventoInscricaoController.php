<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Evento, EventoInscricao, EventoPagamento};
use Illuminate\Support\Facades\Auth;

class EventoInscricaoController extends Controller
{
    /**
     * Confirmar inscrição
     */
    public function confirmar(EventoInscricao $inscricao)
    {
        if (!$inscricao->podeConfirmar()) {
            return back()->with('error', 'Esta inscrição não pode ser confirmada.');
        }

        $inscricao->confirmar();

        // Atualizar vagas disponíveis do evento
        $evento = $inscricao->evento;
        if ($evento->vagas_disponiveis !== null) {
            $evento->decrement('vagas_disponiveis');
        }

        return back()->with('success', 'Inscrição confirmada com sucesso!');
    }

    /**
     * Cancelar inscrição
     */
    public function cancelar(EventoInscricao $inscricao)
    {
        if (!$inscricao->podeCancelar()) {
            return back()->with('error', 'Esta inscrição não pode ser cancelada.');
        }

        $inscricao->cancelar();

        // Atualizar vagas disponíveis do evento
        $evento = $inscricao->evento;
        if ($evento->vagas_disponiveis !== null && $inscricao->status === 'confirmada') {
            $evento->increment('vagas_disponiveis');
        }

        return back()->with('success', 'Inscrição cancelada com sucesso!');
    }

    /**
     * Registrar presença
     */
    public function registrarPresenca(EventoInscricao $inscricao)
    {
        if (!$inscricao->podeRegistrarPresenca()) {
            return back()->with('error', 'Não é possível registrar presença para esta inscrição.');
        }

        $inscricao->registrarPresenca();

        return back()->with('success', 'Presença registrada com sucesso!');
    }

    /**
     * Registrar ausência
     */
    public function registrarAusencia(EventoInscricao $inscricao)
    {
        if (!$inscricao->podeRegistrarPresenca()) {
            return back()->with('error', 'Não é possível registrar ausência para esta inscrição.');
        }

        $inscricao->registrarAusencia();

        return back()->with('success', 'Ausência registrada com sucesso!');
    }

    /**
     * Emitir certificado
     */
    public function emitirCertificado(EventoInscricao $inscricao)
    {
        if (!$inscricao->podeEmitirCertificado()) {
            return back()->with('error', 'Não é possível emitir certificado para esta inscrição.');
        }

        $inscricao->emitirCertificado();

        return back()->with('success', 'Certificado emitido com sucesso!');
    }

    /**
     * Aprovar pagamento
     */
    public function aprovarPagamento(EventoPagamento $pagamento)
    {
        if (!$pagamento->podeAprovar()) {
            return back()->with('error', 'Este pagamento não pode ser aprovado.');
        }

        $pagamento->aprovar();

        return back()->with('success', 'Pagamento aprovado com sucesso!');
    }

    /**
     * Rejeitar pagamento
     */
    public function rejeitarPagamento(Request $request, EventoPagamento $pagamento)
    {
        if (!$pagamento->podeRejeitar()) {
            return back()->with('error', 'Este pagamento não pode ser rejeitado.');
        }

        $pagamento->rejeitar($request->motivo);

        return back()->with('success', 'Pagamento rejeitado com sucesso!');
    }

    /**
     * Cancelar pagamento
     */
    public function cancelarPagamento(Request $request, EventoPagamento $pagamento)
    {
        if (!$pagamento->podeCancelar()) {
            return back()->with('error', 'Este pagamento não pode ser cancelado.');
        }

        $pagamento->cancelar($request->motivo);

        return back()->with('success', 'Pagamento cancelado com sucesso!');
    }

    /**
     * Excluir inscrição
     */
    public function destroy(EventoInscricao $inscricao)
    {
        // Verificar se há pagamentos associados
        if ($inscricao->pagamentos()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma inscrição que possui pagamentos.');
        }

        // Atualizar vagas disponíveis do evento
        $evento = $inscricao->evento;
        if ($evento->vagas_disponiveis !== null && $inscricao->status === 'confirmada') {
            $evento->increment('vagas_disponiveis');
        }

        $inscricao->delete();

        return back()->with('success', 'Inscrição excluída com sucesso!');
    }

    /**
     * Ações em lote
     */
    public function acaoLote(Request $request, Evento $evento)
    {
        $request->validate([
            'acao' => 'required|in:confirmar,cancelar,presenca,ausencia,excluir',
            'inscricoes' => 'required|array',
            'inscricoes.*' => 'exists:evento_inscricoes,id'
        ]);

        $inscricoes = EventoInscricao::whereIn('id', $request->inscricoes)
                                    ->where('evento_id', $evento->id)
                                    ->get();

        $contador = 0;

        foreach ($inscricoes as $inscricao) {
            try {
                switch ($request->acao) {
                    case 'confirmar':
                        if ($inscricao->podeConfirmar()) {
                            $inscricao->confirmar();
                            if ($evento->vagas_disponiveis !== null) {
                                $evento->decrement('vagas_disponiveis');
                            }
                            $contador++;
                        }
                        break;

                    case 'cancelar':
                        if ($inscricao->podeCancelar()) {
                            $inscricao->cancelar();
                            if ($evento->vagas_disponiveis !== null && $inscricao->status === 'confirmada') {
                                $evento->increment('vagas_disponiveis');
                            }
                            $contador++;
                        }
                        break;

                    case 'presenca':
                        if ($inscricao->podeRegistrarPresenca()) {
                            $inscricao->registrarPresenca();
                            $contador++;
                        }
                        break;

                    case 'ausencia':
                        if ($inscricao->podeRegistrarPresenca()) {
                            $inscricao->registrarAusencia();
                            $contador++;
                        }
                        break;

                    case 'excluir':
                        if ($inscricao->pagamentos()->count() === 0) {
                            if ($evento->vagas_disponiveis !== null && $inscricao->status === 'confirmada') {
                                $evento->increment('vagas_disponiveis');
                            }
                            $inscricao->delete();
                            $contador++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $acao = match($request->acao) {
            'confirmar' => 'confirmadas',
            'cancelar' => 'canceladas',
            'presenca' => 'com presença registrada',
            'ausencia' => 'com ausência registrada',
            'excluir' => 'excluídas',
            default => 'processadas'
        };

        return back()->with('success', "{$contador} inscrições foram {$acao} com sucesso!");
    }

    /**
     * Exportar presença
     */
    public function exportarPresenca(Evento $evento)
    {
        $inscricoes = $evento->inscricoes()
                             ->whereIn('status', ['confirmada', 'presente', 'ausente'])
                             ->with('user')
                             ->get();

        $filename = "presenca_evento_{$evento->id}_" . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($inscricoes) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, [
                'Nome', 'Email', 'Telefone', 'Status', 'Presença', 'Data Presença', 'Observações'
            ]);

            // Dados
            foreach ($inscricoes as $inscricao) {
                fputcsv($file, [
                    $inscricao->nome_completo,
                    $inscricao->email_completo,
                    $inscricao->telefone_completo,
                    $inscricao->status_formatado,
                    $inscricao->presenca_confirmada ? 'Presente' : 'Ausente',
                    $inscricao->data_presenca_formatada,
                    $inscricao->observacoes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 