<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdQuizPergunta;
use App\Models\EbdQuizSessao;
use App\Models\EbdQuizResposta;

class EbdQuizBiblicoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Estatísticas do usuário
        $estatisticas = [
            'total_sessoes' => EbdQuizSessao::where('user_id', $user->id)->count(),
            'melhor_pontuacao' => EbdQuizSessao::where('user_id', $user->id)->max('pontuacao_total') ?? 0,
            'melhor_percentual' => EbdQuizSessao::where('user_id', $user->id)->max('percentual') ?? 0,
            'total_acertos' => EbdQuizSessao::where('user_id', $user->id)->sum('acertos'),
            'total_perguntas' => EbdQuizSessao::where('user_id', $user->id)->sum('total_perguntas'),
        ];
        
        // Últimas sessões
        $ultimasSessoes = EbdQuizSessao::where('user_id', $user->id)
                                       ->orderBy('created_at', 'desc')
                                       ->limit(5)
                                       ->get();
        
        // Perguntas disponíveis por nível
        $perguntasFacil = EbdQuizPergunta::ativas()->porNivel('facil')->count();
        $perguntasMedio = EbdQuizPergunta::ativas()->porNivel('medio')->count();
        $perguntasDificil = EbdQuizPergunta::ativas()->porNivel('dificil')->count();
        
        return view('member.ebd.quiz.index', compact(
            'estatisticas', 
            'ultimasSessoes', 
            'perguntasFacil', 
            'perguntasMedio', 
            'perguntasDificil'
        ));
    }

    public function iniciar(Request $request)
    {
        // Obter configurações do sistema
        $tempoLimite = config('quiz.tempo_limite', 30);
        $perguntasPorSessao = config('quiz.perguntas_por_sessao', 10);
        
        $request->validate([
            'nivel' => 'required|in:facil,medio,dificil',
            'categoria' => 'nullable|in:geral,antigo_testamento,novo_testamento,personagens,milagres,parabolas,profetas,apostolos',
            'quantidade' => 'required|integer|min:5|max:' . $perguntasPorSessao
        ]);

        $user = auth()->user();
        
        // Buscar perguntas
        $query = EbdQuizPergunta::ativas()->porNivel($request->nivel);
        
        if ($request->categoria && $request->categoria !== 'geral') {
            $query->porCategoria($request->categoria);
        }
        
        $perguntas = $query->inRandomOrder()->limit($request->quantidade)->get();
        
        if ($perguntas->count() < 5) {
            return redirect()->route('member.ebd.quiz-biblico.index')
                           ->with('error', 'Não há perguntas suficientes para este nível/categoria.');
        }
        
        // Criar sessão
        $sessao = EbdQuizSessao::create([
            'user_id' => $user->id,
            'nivel' => $request->nivel,
            'categoria' => $request->categoria,
            'total_perguntas' => $perguntas->count(),
            'acertos' => 0,
            'pontuacao_total' => 0,
            'percentual' => 0,
            'iniciado_em' => now()
        ]);
        
        return redirect()->route('member.ebd.quiz-biblico.jogar', $sessao);
    }

    public function jogar(EbdQuizSessao $sessao)
    {
        if ($sessao->user_id !== auth()->id()) {
            abort(403);
        }
        
        if ($sessao->esta_finalizada) {
            return redirect()->route('member.ebd.quiz-biblico.resultado', $sessao);
        }
        
        // Buscar perguntas da sessão
        $query = EbdQuizPergunta::ativas()->porNivel($sessao->nivel);
        
        if ($sessao->categoria && $sessao->categoria !== 'geral') {
            $query->porCategoria($sessao->categoria);
        }
        
        $perguntas = $query->inRandomOrder()->limit($sessao->total_perguntas)->get();
        
        // Perguntas já respondidas
        $perguntasRespondidas = $sessao->respostas()->pluck('pergunta_id')->toArray();
        
        // Próxima pergunta
        $proximaPergunta = $perguntas->whereNotIn('id', $perguntasRespondidas)->first();
        
        if (!$proximaPergunta) {
            // Todas as perguntas foram respondidas, finalizar sessão
            $this->finalizarSessao($sessao);
            return redirect()->route('member.ebd.quiz-biblico.resultado', $sessao);
        }
        
        $progresso = [
            'atual' => count($perguntasRespondidas) + 1,
            'total' => $sessao->total_perguntas,
            'percentual' => round(((count($perguntasRespondidas) + 1) / $sessao->total_perguntas) * 100)
        ];
        
        return view('member.ebd.quiz.jogar', compact('sessao', 'proximaPergunta', 'progresso'));
    }

    public function responder(Request $request, EbdQuizSessao $sessao)
    {
        if ($sessao->user_id !== auth()->id()) {
            abort(403);
        }
        
        if ($sessao->esta_finalizada) {
            return redirect()->route('member.ebd.quiz-biblico.resultado', $sessao);
        }
        
        $request->validate([
            'pergunta_id' => 'required|exists:ebd_quiz_perguntas,id',
            'resposta' => 'required|in:a,b,c,d',
            'tempo_resposta' => 'nullable|integer|min:0'
        ]);
        
        $pergunta = EbdQuizPergunta::findOrFail($request->pergunta_id);
        $correta = $pergunta->verificarResposta($request->resposta);
        
        // Calcular pontuação baseada nas configurações do sistema
        $pontuacao = 0;
        if ($correta) {
            switch ($sessao->nivel) {
                case 'facil':
                    $pontuacao = config('quiz.pontuacao.facil', 10);
                    break;
                case 'medio':
                    $pontuacao = config('quiz.pontuacao.medio', 20);
                    break;
                case 'dificil':
                    $pontuacao = config('quiz.pontuacao.dificil', 30);
                    break;
            }
        }
        
        // Salvar resposta
        EbdQuizResposta::create([
            'sessao_id' => $sessao->id,
            'pergunta_id' => $pergunta->id,
            'resposta_dada' => $request->resposta,
            'correta' => $correta,
            'pontuacao_obtida' => $pontuacao,
            'tempo_resposta' => $request->tempo_resposta
        ]);
        
        // Atualizar sessão
        $sessao->acertos += $correta ? 1 : 0;
        $sessao->pontuacao_total += $pontuacao;
        $sessao->save();
        
        // Verificar se é a última pergunta
        $perguntasRespondidas = $sessao->respostas()->count();
        
        if ($perguntasRespondidas >= $sessao->total_perguntas) {
            $this->finalizarSessao($sessao);
            return redirect()->route('member.ebd.quiz-biblico.resultado', $sessao);
        }
        
        return redirect()->route('member.ebd.quiz-biblico.jogar', $sessao)
                       ->with('success', $correta ? 'Resposta correta!' : 'Resposta incorreta. Continue tentando!');
    }

    public function resultado(EbdQuizSessao $sessao)
    {
        if ($sessao->user_id !== auth()->id()) {
            abort(403);
        }
        
        $respostas = $sessao->respostas()->with('pergunta')->get();
        
        return view('member.ebd.quiz.resultado', compact('sessao', 'respostas'));
    }

    public function historico()
    {
        $user = auth()->user();
        
        $sessoes = EbdQuizSessao::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('member.ebd.quiz.historico', compact('sessoes'));
    }

    private function finalizarSessao(EbdQuizSessao $sessao)
    {
        $percentual = $sessao->total_perguntas > 0 ? 
                     round(($sessao->acertos / $sessao->total_perguntas) * 100, 2) : 0;
        
        $sessao->update([
            'percentual' => $percentual,
            'finalizado_em' => now()
        ]);
    }
} 