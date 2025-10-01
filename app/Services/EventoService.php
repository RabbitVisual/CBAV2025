<?php

namespace App\Services;

use App\Models\{Evento, EventoInscricao, EventoPagamento, Ministerio, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class EventoService
{
    // ... métodos existentes de getEventos, createEvento, etc. ...

    public function getEventos(Request $request): array
    {
        $query = Evento::with(['organizador', 'ministerio']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('titulo', 'like', '%' . $request->search . '%');
        $eventos = $query->orderBy('data_inicio', 'desc')->paginate(15);
        $estatisticas = ['total' => Evento::count(), 'ativos' => Evento::where('status', 'ativo')->count(), 'futuros' => Evento::futuros()->count(), 'passados' => Evento::passados()->count()];
        return compact('eventos', 'estatisticas');
    }
    public function getFormData(): array
    {
        $ministerios = Ministerio::ativos()->orderBy('nome')->get();
        $organizadores = User::where('ativo', true)->orderBy('name')->get();
        return compact('ministerios', 'organizadores');
    }
    public function createEvento(array $data): Evento
    {
        if (isset($data['imagem'])) $data['imagem'] = $data['imagem']->store('eventos', 'public');
        if (isset($data['vagas_totais'])) $data['vagas_disponiveis'] = $data['vagas_totais'];
        $data['criado_por'] = Auth::id();
        return Evento::create($data);
    }
    public function updateEvento(Evento $evento, array $data): bool
    {
        if (isset($data['imagem'])) {
            if ($evento->imagem) Storage::disk('public')->delete($evento->imagem);
            $data['imagem'] = $data['imagem']->store('eventos', 'public');
        }
        if (isset($data['vagas_totais']) && $data['vagas_totais'] != $evento->vagas_totais) {
            $inscritos = $evento->inscricoes()->where('status', 'confirmada')->count();
            $data['vagas_disponiveis'] = max(0, $data['vagas_totais'] - $inscritos);
        }
        $data['atualizado_por'] = Auth::id();
        return $evento->update($data);
    }
    public function deleteEvento(Evento $evento): bool
    {
        if ($evento->inscricoes()->count() > 0) throw new \Exception('Não é possível excluir um evento que possui inscrições.');
        if ($evento->imagem) Storage::disk('public')->delete($evento->imagem);
        return $evento->delete();
    }
    public function getEventoDetails(Evento $evento): array
    {
        $evento->load(['organizador', 'ministerio', 'inscricoes.user', 'pagamentos']);
        $estatisticas = ['total_inscricoes' => $evento->inscricoes()->count(), 'inscricoes_confirmadas' => $evento->inscricoes()->where('status', 'confirmada')->count(), 'valor_total_arrecadado' => $evento->pagamentos()->where('status', 'aprovado')->sum('valor')];
        return compact('evento', 'estatisticas');
    }

    // --- MÉTODOS DE INSCRIÇÃO ---

    public function getInscricoes(Evento $evento, Request $request)
    {
        return $evento->inscricoes()->with('user')->latest()->paginate(20);
    }

    public function confirmarInscricao(EventoInscricao $inscricao): void
    {
        if (!$inscricao->podeConfirmar()) throw new \Exception('Esta inscrição não pode ser confirmada.');
        $inscricao->confirmar();
        if ($inscricao->evento->vagas_disponiveis !== null) {
            $inscricao->evento->decrement('vagas_disponiveis');
        }
    }

    public function cancelarInscricao(EventoInscricao $inscricao): void
    {
        if (!$inscricao->podeCancelar()) throw new \Exception('Esta inscrição não pode ser cancelada.');

        $wasConfirmed = $inscricao->status === 'confirmada';
        $inscricao->cancelar();

        if ($wasConfirmed && $inscricao->evento->vagas_disponiveis !== null) {
            $inscricao->evento->increment('vagas_disponiveis');
        }
    }

    public function registrarPresenca(EventoInscricao $inscricao): void
    {
        if (!$inscricao->podeRegistrarPresenca()) throw new \Exception('Não é possível registrar presença para esta inscrição.');
        $inscricao->registrarPresenca();
    }

    public function registrarAusencia(EventoInscricao $inscricao): void
    {
        if (!$inscricao->podeRegistrarPresenca()) throw new \Exception('Não é possível registrar ausência para esta inscrição.');
        $inscricao->registrarAusencia();
    }

    public function aprovarPagamento(EventoPagamento $pagamento): void
    {
        if (!$pagamento->podeAprovar()) throw new \Exception('Este pagamento não pode ser aprovado.');
        $pagamento->aprovar();
    }

    public function rejeitarPagamento(EventoPagamento $pagamento, ?string $motivo): void
    {
        if (!$pagamento->podeRejeitar()) throw new \Exception('Este pagamento não pode ser rejeitado.');
        $pagamento->rejeitar($motivo);
    }

    public function processarAcaoEmLote(Evento $evento, string $acao, array $inscricoesIds): int
    {
        $inscricoes = EventoInscricao::whereIn('id', $inscricoesIds)->where('evento_id', $evento->id)->get();
        $contador = 0;

        foreach ($inscricoes as $inscricao) {
            try {
                switch ($acao) {
                    case 'confirmar': $this->confirmarInscricao($inscricao); break;
                    case 'cancelar': $this->cancelarInscricao($inscricao); break;
                    case 'presenca': $this->registrarPresenca($inscricao); break;
                    case 'ausencia': $this->registrarAusencia($inscricao); break;
                    case 'excluir': $this->deleteInscricao($inscricao); break;
                }
                $contador++;
            } catch (\Exception $e) {
                // Logar o erro se necessário, mas continuar o processo
                \Log::warning("Falha na ação em lote '{$acao}' para a inscrição {$inscricao->id}: " . $e->getMessage());
                continue;
            }
        }
        return $contador;
    }

    public function deleteInscricao(EventoInscricao $inscricao): bool
    {
        if ($inscricao->pagamentos()->count() > 0) {
            throw new \Exception('Não é possível excluir uma inscrição que possui pagamentos.');
        }

        $wasConfirmed = $inscricao->status === 'confirmada';
        $evento = $inscricao->evento;

        if ($inscricao->delete()) {
            if ($wasConfirmed && $evento->vagas_disponiveis !== null) {
                $evento->increment('vagas_disponiveis');
            }
            return true;
        }
        return false;
    }
}