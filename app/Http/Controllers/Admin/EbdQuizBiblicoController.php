<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdQuizPergunta;
use App\Models\EbdQuizSessao;
use App\Models\EbdQuizResposta;

class EbdQuizBiblicoController extends Controller
{
    public function index()
    {
        $perguntas = EbdQuizPergunta::orderBy('created_at', 'desc')->paginate(15);
        
        $estatisticas = [
            'total_perguntas' => EbdQuizPergunta::count(),
            'perguntas_ativas' => EbdQuizPergunta::ativas()->count(),
            'total_sessoes' => EbdQuizSessao::count(),
            'sessoes_hoje' => EbdQuizSessao::whereDate('created_at', today())->count(),
        ];
        
        return view('admin.ebd.quiz.index', compact('perguntas', 'estatisticas'));
    }

    public function create()
    {
        return view('admin.ebd.quiz.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pergunta' => 'required|string|max:500',
            'opcao_a' => 'required|string|max:200',
            'opcao_b' => 'required|string|max:200',
            'opcao_c' => 'required|string|max:200',
            'opcao_d' => 'required|string|max:200',
            'resposta_correta' => 'required|in:a,b,c,d',
            'explicacao' => 'nullable|string|max:1000',
            'referencia_biblica' => 'nullable|string|max:100',
            'nivel' => 'required|in:facil,medio,dificil',
            'categoria' => 'required|in:geral,antigo_testamento,novo_testamento,personagens,milagres,parabolas,profetas,apostolos',
            'pontuacao' => 'required|integer|min:1|max:100',
            'ativo' => 'boolean'
        ]);
        
        $data['ativo'] = $request->has('ativo');
        
        EbdQuizPergunta::create($data);
        
        return redirect()->route('admin.ebd.quiz-biblico.index')
                       ->with('success', 'Pergunta criada com sucesso!');
    }

    public function show(EbdQuizPergunta $pergunta)
    {
        $estatisticas = [
            'total_respostas' => $pergunta->respostas()->count(),
            'respostas_corretas' => $pergunta->respostas()->where('correta', true)->count(),
            'respostas_incorretas' => $pergunta->respostas()->where('correta', false)->count(),
            'percentual_acertos' => $pergunta->respostas()->count() > 0 ? 
                                  round(($pergunta->respostas()->where('correta', true)->count() / $pergunta->respostas()->count()) * 100, 2) : 0
        ];
        
        return view('admin.ebd.quiz.show', compact('pergunta', 'estatisticas'));
    }

    public function edit(EbdQuizPergunta $pergunta)
    {
        return view('admin.ebd.quiz.edit', compact('pergunta'));
    }

    public function update(Request $request, EbdQuizPergunta $pergunta)
    {
        $data = $request->validate([
            'pergunta' => 'required|string|max:500',
            'opcao_a' => 'required|string|max:200',
            'opcao_b' => 'required|string|max:200',
            'opcao_c' => 'required|string|max:200',
            'opcao_d' => 'required|string|max:200',
            'resposta_correta' => 'required|in:a,b,c,d',
            'explicacao' => 'nullable|string|max:1000',
            'referencia_biblica' => 'nullable|string|max:100',
            'nivel' => 'required|in:facil,medio,dificil',
            'categoria' => 'required|in:geral,antigo_testamento,novo_testamento,personagens,milagres,parabolas,profetas,apostolos',
            'pontuacao' => 'required|integer|min:1|max:100',
            'ativo' => 'boolean'
        ]);
        
        $data['ativo'] = $request->has('ativo');
        
        $pergunta->update($data);
        
        return redirect()->route('admin.ebd.quiz-biblico.index')
                       ->with('success', 'Pergunta atualizada com sucesso!');
    }

    public function destroy(EbdQuizPergunta $pergunta)
    {
        $pergunta->delete();
        
        return redirect()->route('admin.ebd.quiz-biblico.index')
                       ->with('success', 'Pergunta removida com sucesso!');
    }

    public function estatisticas()
    {
        $estatisticas = [
            'total_perguntas' => EbdQuizPergunta::count(),
            'perguntas_ativas' => EbdQuizPergunta::ativas()->count(),
            'total_sessoes' => EbdQuizSessao::count(),
            'sessoes_hoje' => EbdQuizSessao::whereDate('created_at', today())->count(),
            'sessoes_semana' => EbdQuizSessao::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'sessoes_mes' => EbdQuizSessao::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Estatísticas por nível
        $estatisticasNivel = [
            'facil' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('facil')->count(),
                'sessoes' => EbdQuizSessao::porNivel('facil')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('facil')->avg('percentual') ?? 0
            ],
            'medio' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('medio')->count(),
                'sessoes' => EbdQuizSessao::porNivel('medio')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('medio')->avg('percentual') ?? 0
            ],
            'dificil' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('dificil')->count(),
                'sessoes' => EbdQuizSessao::porNivel('dificil')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('dificil')->avg('percentual') ?? 0
            ]
        ];
        
        // Top 10 melhores pontuações
        $melhoresPontuacoes = EbdQuizSessao::with('user')
                                           ->orderBy('pontuacao_total', 'desc')
                                           ->limit(10)
                                           ->get();
        
        // Últimas sessões
        $ultimasSessoes = EbdQuizSessao::with('user')
                                       ->orderBy('created_at', 'desc')
                                       ->limit(10)
                                       ->get();
        
        return view('admin.ebd.quiz.estatisticas', compact(
            'estatisticas', 
            'estatisticasNivel', 
            'melhoresPontuacoes', 
            'ultimasSessoes'
        ));
    }

    /**
     * Exportar relatório de estatísticas
     */
    public function exportarEstatisticas(Request $request)
    {
        $formato = $request->get('formato', 'excel');
        
        $estatisticas = [
            'total_perguntas' => EbdQuizPergunta::count(),
            'perguntas_ativas' => EbdQuizPergunta::ativas()->count(),
            'total_sessoes' => EbdQuizSessao::count(),
            'sessoes_hoje' => EbdQuizSessao::whereDate('created_at', today())->count(),
            'sessoes_semana' => EbdQuizSessao::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'sessoes_mes' => EbdQuizSessao::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Estatísticas por nível
        $estatisticasNivel = [
            'facil' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('facil')->count(),
                'sessoes' => EbdQuizSessao::porNivel('facil')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('facil')->avg('percentual') ?? 0
            ],
            'medio' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('medio')->count(),
                'sessoes' => EbdQuizSessao::porNivel('medio')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('medio')->avg('percentual') ?? 0
            ],
            'dificil' => [
                'perguntas' => EbdQuizPergunta::ativas()->porNivel('dificil')->count(),
                'sessoes' => EbdQuizSessao::porNivel('dificil')->count(),
                'media_percentual' => EbdQuizSessao::porNivel('dificil')->avg('percentual') ?? 0
            ]
        ];
        
        // Top 10 melhores pontuações
        $melhoresPontuacoes = EbdQuizSessao::with('user')
                                           ->orderBy('pontuacao_total', 'desc')
                                           ->limit(10)
                                           ->get();
        
        // Últimas sessões
        $ultimasSessoes = EbdQuizSessao::with('user')
                                       ->orderBy('created_at', 'desc')
                                       ->limit(10)
                                       ->get();
        
        if ($formato === 'pdf') {
            $pdf = \PDF::loadView('admin.ebd.quiz.exportar-estatisticas-pdf', compact(
                'estatisticas', 
                'estatisticasNivel', 
                'melhoresPontuacoes', 
                'ultimasSessoes'
            ));
            return $pdf->download('estatisticas-quiz-biblico.pdf');
        } else {
            return \Excel::download(new \App\Exports\QuizEstatisticasExport($estatisticas, $melhoresPontuacoes), 'estatisticas-quiz-biblico.xlsx');
        }
    }

    /**
     * Configurações do quiz
     */
    public function configuracoes()
    {
        $configuracoes = [
            // Configurações gerais
            'tempo_limite' => config('quiz.tempo_limite', 30),
            'perguntas_por_sessao' => config('quiz.perguntas_por_sessao', 10),
            'pontuacao_facil' => config('quiz.pontuacao.facil', 10),
            'pontuacao_medio' => config('quiz.pontuacao.medio', 20),
            'pontuacao_dificil' => config('quiz.pontuacao.dificil', 30),
            'notificar_novos_recordes' => config('quiz.notificar_recordes', true),
            'notificar_recordes_pessoais' => config('quiz.notificar_recordes_pessoais', true),
            'notificar_admins_recordes' => config('quiz.notificar_admins_recordes', true),
            
            // Configurações de e-mail
            'mail_host' => config('mail.mailers.smtp.host', 'smtp.gmail.com'),
            'mail_port' => config('mail.mailers.smtp.port', 587),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => config('mail.mailers.smtp.password', ''),
            'mail_from_address' => config('mail.from.address', 'admin@cbav.com'),
            'mail_from_name' => config('mail.from.name', 'CBAV Sistema'),
            
            // Configurações de jobs
            'queue_retry_after' => config('queue.connections.database.retry_after', 90),
            'queue_timeout' => config('queue.connections.database.timeout', 60),
            'job_tries' => config('quiz.job_tries', 3),
            
            // Configurações do template
            'template_primary_color' => config('quiz.template.primary_color', '#2563eb'),
            'template_secondary_color' => config('quiz.template.secondary_color', '#fbbf24'),
            'template_logo_url' => config('quiz.template.logo_url', ''),
            'template_footer_text' => config('quiz.template.footer_text', 'Continue estudando a Palavra de Deus e testando seus conhecimentos. Cada sessão é uma oportunidade de crescimento espiritual!'),
        ];
        
        return view('admin.ebd.quiz.configuracoes', compact('configuracoes'));
    }

    /**
     * Atualizar configurações
     */
    public function atualizarConfiguracoes(Request $request)
    {
        $tab = $request->get('tab', 'geral');
        
        switch ($tab) {
            case 'geral':
                $this->atualizarConfiguracoesGerais($request);
                break;
            case 'email':
                $this->atualizarConfiguracoesEmail($request);
                break;
            case 'template':
                $this->atualizarConfiguracoesTemplate($request);
                break;
        }
        
        return redirect()->route('admin.ebd.quiz-biblico.configuracoes')
                       ->with('success', 'Configurações atualizadas com sucesso!');
    }
    
    /**
     * Atualizar configurações gerais
     */
    private function atualizarConfiguracoesGerais(Request $request)
    {
        $request->validate([
            'tempo_limite' => 'required|integer|min:10|max:120',
            'perguntas_por_sessao' => 'required|integer|min:5|max:50',
            'pontuacao_facil' => 'required|integer|min:1|max:100',
            'pontuacao_medio' => 'required|integer|min:1|max:100',
            'pontuacao_dificil' => 'required|integer|min:1|max:100',
            'notificar_novos_recordes' => 'boolean',
            'notificar_recordes_pessoais' => 'boolean',
            'notificar_admins_recordes' => 'boolean'
        ]);
        
        // Salvar no banco de dados
        $this->salvarConfiguracao('quiz.tempo_limite', $request->tempo_limite);
        $this->salvarConfiguracao('quiz.perguntas_por_sessao', $request->perguntas_por_sessao);
        $this->salvarConfiguracao('quiz.pontuacao.facil', $request->pontuacao_facil);
        $this->salvarConfiguracao('quiz.pontuacao.medio', $request->pontuacao_medio);
        $this->salvarConfiguracao('quiz.pontuacao.dificil', $request->pontuacao_dificil);
        $this->salvarConfiguracao('quiz.notificar_recordes', $request->has('notificar_novos_recordes'));
        $this->salvarConfiguracao('quiz.notificar_recordes_pessoais', $request->has('notificar_recordes_pessoais'));
        $this->salvarConfiguracao('quiz.notificar_admins_recordes', $request->has('notificar_admins_recordes'));
    }
    
    /**
     * Atualizar configurações de e-mail
     */
    private function atualizarConfiguracoesEmail(Request $request)
    {
        $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|email',
            'mail_password' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'queue_retry_after' => 'required|integer|min:30|max:300',
            'queue_timeout' => 'required|integer|min:30|max:300',
            'job_tries' => 'required|integer|min:1|max:10'
        ]);
        
        // Salvar configurações de e-mail
        $this->salvarConfiguracao('mail.mailers.smtp.host', $request->mail_host);
        $this->salvarConfiguracao('mail.mailers.smtp.port', $request->mail_port);
        $this->salvarConfiguracao('mail.mailers.smtp.username', $request->mail_username);
        $this->salvarConfiguracao('mail.mailers.smtp.password', $request->mail_password);
        $this->salvarConfiguracao('mail.from.address', $request->mail_from_address);
        $this->salvarConfiguracao('mail.from.name', $request->mail_from_name);
        $this->salvarConfiguracao('queue.connections.database.retry_after', $request->queue_retry_after);
        $this->salvarConfiguracao('queue.connections.database.timeout', $request->queue_timeout);
        $this->salvarConfiguracao('quiz.job_tries', $request->job_tries);
    }
    
    /**
     * Atualizar configurações do template
     */
    private function atualizarConfiguracoesTemplate(Request $request)
    {
        $request->validate([
            'template_primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'template_secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'template_logo_url' => 'nullable|url',
            'template_footer_text' => 'required|string|max:500'
        ]);
        
        // Salvar configurações do template
        $this->salvarConfiguracao('quiz.template.primary_color', $request->template_primary_color);
        $this->salvarConfiguracao('quiz.template.secondary_color', $request->template_secondary_color);
        $this->salvarConfiguracao('quiz.template.logo_url', $request->template_logo_url);
        $this->salvarConfiguracao('quiz.template.footer_text', $request->template_footer_text);
    }
    
    /**
     * Salvar configuração no banco de dados
     */
    private function salvarConfiguracao($chave, $valor)
    {
        \App\Models\Configuracao::updateOrCreate(
            ['chave' => $chave],
            [
                'valor' => is_bool($valor) ? ($valor ? '1' : '0') : (string) $valor,
                'tipo' => is_bool($valor) ? 'boolean' : (is_numeric($valor) ? 'integer' : 'string'),
                'descricao' => 'Configuração do Quiz Bíblico'
            ]
        );
    }

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:csv,txt'
        ]);
        
        $arquivo = $request->file('arquivo');
        $conteudo = file_get_contents($arquivo->getPathname());
        $linhas = explode("\n", $conteudo);
        
        $importadas = 0;
        $erros = 0;
        
        foreach ($linhas as $linha) {
            if (empty(trim($linha))) continue;
            
            $dados = str_getcsv($linha, ',');
            
            if (count($dados) < 8) {
                $erros++;
                continue;
            }
            
            try {
                EbdQuizPergunta::create([
                    'pergunta' => trim($dados[0]),
                    'opcao_a' => trim($dados[1]),
                    'opcao_b' => trim($dados[2]),
                    'opcao_c' => trim($dados[3]),
                    'opcao_d' => trim($dados[4]),
                    'resposta_correta' => strtolower(trim($dados[5])),
                    'nivel' => strtolower(trim($dados[6])),
                    'categoria' => strtolower(trim($dados[7])),
                    'explicacao' => isset($dados[8]) ? trim($dados[8]) : null,
                    'referencia_biblica' => isset($dados[9]) ? trim($dados[9]) : null,
                    'ativo' => true
                ]);
                
                $importadas++;
            } catch (\Exception $e) {
                $erros++;
            }
        }
        
        return redirect()->route('admin.ebd.quiz-biblico.index')
                       ->with('success', "Importação concluída! {$importadas} perguntas importadas, {$erros} erros.");
    }

    /**
     * Testar configurações de e-mail
     */
    public function testarEmail(Request $request)
    {
        $tipo = $request->get('tipo', 'direct');
        $user = auth()->user();
        
        try {
            switch ($tipo) {
                case 'direct':
                    // Teste de e-mail direto
                    $sessao = EbdQuizSessao::where('user_id', $user->id)->first();
                    if (!$sessao) {
                        $sessao = EbdQuizSessao::create([
                            'user_id' => $user->id,
                            'nivel' => 'medio',
                            'categoria' => 'antigo_testamento',
                            'total_perguntas' => 10,
                            'acertos' => 8,
                            'pontuacao_total' => 160,
                            'percentual' => 80.0,
                            'iniciado_em' => now()->subMinutes(30),
                            'finalizado_em' => now()->subMinutes(5)
                        ]);
                    }
                    
                    \Mail::to($user->email)
                        ->send(new \App\Mail\QuizRecordeMail($sessao, 'pontuacao', $sessao->pontuacao_total, false));
                    
                    $mensagem = "✅ E-mail enviado diretamente com sucesso!\n📧 Verifique a caixa de entrada de: {$user->email}";
                    break;
                    
                case 'job':
                    // Teste de job
                    $sessao = EbdQuizSessao::where('user_id', $user->id)->first();
                    if (!$sessao) {
                        $sessao = EbdQuizSessao::create([
                            'user_id' => $user->id,
                            'nivel' => 'medio',
                            'categoria' => 'antigo_testamento',
                            'total_perguntas' => 10,
                            'acertos' => 8,
                            'pontuacao_total' => 160,
                            'percentual' => 80.0,
                            'iniciado_em' => now()->subMinutes(30),
                            'finalizado_em' => now()->subMinutes(5)
                        ]);
                    }
                    
                    \App\Jobs\EnviarNotificacaoQuizRecorde::dispatch($sessao, 'pontuacao', $sessao->pontuacao_total, false);
                    
                    $mensagem = "✅ Job de e-mail agendado com sucesso!\n📋 Verifique a fila de jobs para processamento.";
                    break;
                    
                case 'global':
                    // Teste de recorde global
                    $sessao = EbdQuizSessao::where('user_id', $user->id)->first();
                    if (!$sessao) {
                        $sessao = EbdQuizSessao::create([
                            'user_id' => $user->id,
                            'nivel' => 'medio',
                            'categoria' => 'antigo_testamento',
                            'total_perguntas' => 10,
                            'acertos' => 8,
                            'pontuacao_total' => 160,
                            'percentual' => 80.0,
                            'iniciado_em' => now()->subMinutes(30),
                            'finalizado_em' => now()->subMinutes(5)
                        ]);
                    }
                    
                    \App\Jobs\EnviarNotificacaoQuizRecorde::dispatch($sessao, 'pontuacao', $sessao->pontuacao_total, true);
                    
                    $mensagem = "✅ Job de recorde global agendado com sucesso!\n📋 Verifique a fila de jobs para processamento.";
                    break;
                    
                default:
                    $mensagem = "❌ Tipo de teste inválido: {$tipo}";
            }
            
        } catch (\Exception $e) {
            $mensagem = "❌ Erro ao executar teste: " . $e->getMessage();
        }
        
        return response()->json(['mensagem' => $mensagem]);
    }
} 