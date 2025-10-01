<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAvaliacao;
use App\Models\EbdAluno;
use App\Models\EbdRespostaAluno;
use App\Models\EbdNota;

class EbdQuizController extends Controller
{
    public function show(EbdAvaliacao $avaliacao)
    {
        $user = auth()->user();
        
        // Tentar encontrar o aluno pelo email primeiro
        $aluno = EbdAluno::where('email', $user->email)->first();
        
        // Se não encontrar pelo email, tentar pelo membro_id
        if (!$aluno && $user->membro) {
            $aluno = EbdAluno::where('membro_id', $user->membro->id)->first();
        }
        
        // Se ainda não encontrar, tentar pelo nome do usuário
        if (!$aluno) {
            $aluno = EbdAluno::where('nome', 'like', '%' . $user->name . '%')->first();
        }

        if (!$aluno) {
            return redirect()->route('member.ebd.dashboard')->with('error', 'Aluno não encontrado.');
        }

        // Verificar se o aluno já fez esta avaliação
        $nota = EbdNota::where('avaliacao_id', $avaliacao->id)
                       ->where('aluno_id', $aluno->id)
                       ->first();

        if ($nota) {
            return redirect()->route('member.ebd.dashboard')->with('error', 'Você já realizou esta avaliação.');
        }

        $questoes = $avaliacao->questoes()->ordenadas()->get();

        return view('member.ebd.quiz.show', compact('avaliacao', 'aluno', 'questoes'));
    }

    public function responder(Request $request, EbdAvaliacao $avaliacao)
    {
        $user = auth()->user();
        
        // Tentar encontrar o aluno pelo email primeiro
        $aluno = EbdAluno::where('email', $user->email)->first();
        
        // Se não encontrar pelo email, tentar pelo membro_id
        if (!$aluno && $user->membro) {
            $aluno = EbdAluno::where('membro_id', $user->membro->id)->first();
        }
        
        // Se ainda não encontrar, tentar pelo nome do usuário
        if (!$aluno) {
            $aluno = EbdAluno::where('nome', 'like', '%' . $user->name . '%')->first();
        }

        if (!$aluno) {
            return redirect()->route('member.ebd.dashboard')->with('error', 'Aluno não encontrado.');
        }

        // Verificar se o aluno já fez esta avaliação
        $nota = EbdNota::where('avaliacao_id', $avaliacao->id)
                       ->where('aluno_id', $aluno->id)
                       ->first();

        if ($nota) {
            return redirect()->route('member.ebd.dashboard')->with('error', 'Você já realizou esta avaliação.');
        }

        $questoes = $avaliacao->questoes()->ordenadas()->get();
        
        // Verificar se a avaliação tem questões
        if ($questoes->count() === 0) {
            return redirect()->route('member.ebd.dashboard')->with('error', 'Esta avaliação não possui questões cadastradas.');
        }
        
        $respostas = $request->input('respostas', []);
        
        // Validar se pelo menos uma resposta foi fornecida
        if (empty($respostas) || count(array_filter($respostas)) === 0) {
            return redirect()->back()->with('error', 'Você deve responder pelo menos uma questão antes de enviar a avaliação.');
        }
        
        // Validar se todas as questões obrigatórias foram respondidas
        $questoesObrigatorias = $questoes->where('tipo', '!=', 'dissertativa');
        $respostasObrigatorias = [];
        foreach ($questoesObrigatorias as $questao) {
            if (!isset($respostas[$questao->id]) || empty(trim($respostas[$questao->id]))) {
                return redirect()->back()->with('error', 'Todas as questões devem ser respondidas antes de enviar a avaliação.');
            }
        }
        
        $pontuacaoTotal = 0;
        $respostasSalvas = [];

        foreach ($questoes as $questao) {
            $resposta = $respostas[$questao->id] ?? null;
            $correta = false;
            $pontuacaoObtida = 0;

            if ($resposta) {
                // Verificar se a resposta está correta
                if ($questao->tipo === 'multipla_escolha' || $questao->tipo === 'verdadeiro_falso') {
                    $correta = $resposta === $questao->resposta_correta;
                } elseif ($questao->tipo === 'dissertativa') {
                    // Para questões dissertativas, marcar como correta por padrão
                    // O professor pode corrigir depois
                    $correta = true;
                }

                $pontuacaoObtida = $correta ? $questao->pontuacao : 0;
                $pontuacaoTotal += $pontuacaoObtida;

                // Salvar resposta do aluno
                EbdRespostaAluno::create([
                    'avaliacao_id' => $avaliacao->id,
                    'aluno_id' => $aluno->id,
                    'questao_id' => $questao->id,
                    'resposta' => $resposta,
                    'correta' => $correta,
                    'pontuacao_obtida' => $pontuacaoObtida,
                ]);
            }
        }

        // Calcular percentual
        $pontuacaoMaxima = $questoes->sum('pontuacao');
        $percentual = $pontuacaoMaxima > 0 ? round(($pontuacaoTotal / $pontuacaoMaxima) * 100, 2) : 0;

        // Salvar nota do aluno
        EbdNota::create([
            'avaliacao_id' => $avaliacao->id,
            'aluno_id' => $aluno->id,
            'nota' => $pontuacaoTotal,
            'pontuacao_maxima' => $pontuacaoMaxima,
            'percentual' => $percentual,
        ]);

        return redirect()->route('member.ebd.dashboard')->with('success', 'Avaliação realizada com sucesso! Sua nota: ' . $percentual . '%');
    }
}