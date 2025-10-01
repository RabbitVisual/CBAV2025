<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Evento, EventoInscricao, EventoPagamento};
use App\Services\EventoService;

class EventoInscricaoController extends Controller
{
    protected $eventoService;

    public function __construct(EventoService $eventoService)
    {
        $this->eventoService = $eventoService;
    }

    public function confirmar(EventoInscricao $inscricao)
    {
        try {
            $this->eventoService->confirmarInscricao($inscricao);
            return back()->with('success', 'Inscrição confirmada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancelar(EventoInscricao $inscricao)
    {
        try {
            $this->eventoService->cancelarInscricao($inscricao);
            return back()->with('success', 'Inscrição cancelada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function registrarPresenca(EventoInscricao $inscricao)
    {
        try {
            $this->eventoService->registrarPresenca($inscricao);
            return back()->with('success', 'Presença registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function registrarAusencia(EventoInscricao $inscricao)
    {
        try {
            $this->eventoService->registrarAusencia($inscricao);
            return back()->with('success', 'Ausência registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function aprovarPagamento(EventoPagamento $pagamento)
    {
        try {
            $this->eventoService->aprovarPagamento($pagamento);
            return back()->with('success', 'Pagamento aprovado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejeitarPagamento(Request $request, EventoPagamento $pagamento)
    {
        try {
            $this->eventoService->rejeitarPagamento($pagamento, $request->motivo);
            return back()->with('success', 'Pagamento rejeitado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(EventoInscricao $inscricao)
    {
        try {
            $this->eventoService->deleteInscricao($inscricao);
            return back()->with('success', 'Inscrição excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function acaoLote(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'acao' => 'required|in:confirmar,cancelar,presenca,ausencia,excluir',
            'inscricoes' => 'required|array',
            'inscricoes.*' => 'exists:evento_inscricoes,id'
        ]);

        $contador = $this->eventoService->processarAcaoEmLote($evento, $data['acao'], $data['inscricoes']);

        $acao = match($data['acao']) {
            'confirmar' => 'confirmadas', 'cancelar' => 'canceladas',
            'presenca' => 'com presença registrada', 'ausencia' => 'com ausência registrada',
            'excluir' => 'excluídas', default => 'processadas'
        };

        return back()->with('success', "{$contador} inscrições foram {$acao} com sucesso!");
    }
}