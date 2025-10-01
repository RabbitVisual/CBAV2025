<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transacao;
use App\Models\Campanha;
use App\Models\Membro;
use App\Models\Configuracao;
use Carbon\Carbon;

class PdfService
{
    public function gerarRelatorioMensal($mes, $transacoes, $resumo)
    {
        $dataInicio = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        $dataFim = Carbon::createFromFormat('Y-m', $mes)->endOfMonth();
        
        $html = view('pdf.relatorio-mensal', compact('mes', 'transacoes', 'resumo', 'dataInicio', 'dataFim'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    public function gerarRelatorioAnual($ano, $dadosMensais, $resumoAnual)
    {
        $html = view('pdf.relatorio-anual', compact('ano', 'dadosMensais', 'resumoAnual'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    public function gerarRelatorioMembro($membro)
    {
        $html = view('pdf.relatorio-membro', compact('membro'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    public function gerarRelatorioCampanha($campanha)
    {
        // Carregar relacionamentos necessários se não estiverem carregados
        if (!$campanha->relationLoaded('transacoes')) {
            $campanha->load(['transacoes.membro']);
        }
        
        // Calcular estatísticas adicionais
        $estatisticas = [
            'total_transacoes' => $campanha->transacoes->count(),
            'total_confirmado' => $campanha->transacoes->where('status', 'confirmado')->sum('valor'),
            'total_pendente' => $campanha->transacoes->where('status', 'pendente')->sum('valor'),
            'total_cancelado' => $campanha->transacoes->where('status', 'cancelado')->sum('valor'),
            'media_por_transacao' => $campanha->transacoes->where('status', 'confirmado')->avg('valor') ?? 0,
            'maior_contribuicao' => $campanha->transacoes->where('status', 'confirmado')->max('valor') ?? 0,
            'menor_contribuicao' => $campanha->transacoes->where('status', 'confirmado')->min('valor') ?? 0,
        ];
        
        $html = view('pdf.relatorio-campanha', compact('campanha', 'estatisticas'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Gerar ficha profissional de membro
     */
    public function gerarFichaMembro(Membro $membro)
    {
        // Buscar configurações da igreja
        $igrejaNome = Configuracao::get('igreja_nome', 'Igreja Batista');
        $igrejaEndereco = Configuracao::get('igreja_endereco', '');
        $igrejaTelefone = Configuracao::get('igreja_telefone', '');
        $igrejaEmail = Configuracao::get('igreja_email', '');
        $igrejaLogo = Configuracao::get('igreja_logo', '');
        $igrejaPastor = Configuracao::get('igreja_pastor', '');
        $igrejaCNPJ = Configuracao::get('igreja_cnpj', '');
        
        // Buscar dados completos do membro
        $membro->load(['cargos.departamento.ministerio', 'transacoes.campanha']);
        
        // Calcular estatísticas detalhadas
        $totalDoacoes = $membro->transacoes()->where('status', 'confirmado')->sum('valor') ?? 0;
        $totalDizimos = $membro->transacoes()->where('tipo', 'dizimo')->where('status', 'confirmado')->sum('valor') ?? 0;
        $totalOfertas = $membro->transacoes()->where('tipo', 'oferta')->where('status', 'confirmado')->sum('valor') ?? 0;
        $totalCampanhas = $membro->transacoes()->where('tipo', 'campanha')->where('status', 'confirmado')->sum('valor') ?? 0;
        
        // Estatísticas por período
        $doacoesUltimoAno = $membro->transacoes()
            ->where('status', 'confirmado')
            ->where('data', '>=', Carbon::now()->subYear())
            ->sum('valor') ?? 0;
        
        $doacoesUltimoMes = $membro->transacoes()
            ->where('status', 'confirmado')
            ->where('data', '>=', Carbon::now()->subMonth())
            ->sum('valor') ?? 0;
        
        // Histórico de cargos
        $cargosAtivos = $membro->cargos()->wherePivot('ativo', true)->get();
        $cargosHistorico = $membro->cargos()->wherePivot('ativo', false)->get();
        
        // Participação em campanhas
        $campanhasParticipadas = $membro->transacoes()
            ->where('tipo', 'campanha')
            ->where('status', 'confirmado')
            ->with('campanha')
            ->get()
            ->groupBy('campanha_id');
        
        // Frequência de contribuições
        $totalContribuicoes = $membro->transacoes()->where('status', 'confirmado')->count() ?? 0;
        $contribuicoesUltimoAno = $membro->transacoes()
            ->where('status', 'confirmado')
            ->where('data', '>=', Carbon::now()->subYear())
            ->count() ?? 0;
        
        // Tempo de membro
        $tempoMembro = 'Não informado';
        if ($membro->data_ingresso) {
            $tempoMembro = Carbon::parse($membro->data_ingresso)->diffForHumans();
        }
        
        $idade = null;
        if ($membro->data_nascimento) {
            $idade = Carbon::parse($membro->data_nascimento)->age;
        }
        
        // Ministérios organizados
        $ministeriosAtivos = collect();
        $historicoMinisterios = collect();
        
        if ($cargosAtivos->count() > 0) {
            $ministeriosAtivos = $cargosAtivos->groupBy(function($cargo) {
                return $cargo->departamento && $cargo->departamento->ministerio 
                    ? $cargo->departamento->ministerio->nome 
                    : 'Sem Ministério';
            });
        }
        
        if ($cargosHistorico->count() > 0) {
            $historicoMinisterios = $cargosHistorico->groupBy(function($cargo) {
                return $cargo->departamento && $cargo->departamento->ministerio 
                    ? $cargo->departamento->ministerio->nome 
                    : 'Sem Ministério';
            });
        }
        
        // Dados para o template
        $dados = [
            'membro' => $membro,
            'igrejaNome' => $igrejaNome,
            'igrejaEndereco' => $igrejaEndereco,
            'igrejaTelefone' => $igrejaTelefone,
            'igrejaEmail' => $igrejaEmail,
            'igrejaLogo' => $igrejaLogo,
            'igrejaPastor' => $igrejaPastor,
            'igrejaCNPJ' => $igrejaCNPJ,
            'totalDoacoes' => $totalDoacoes,
            'totalDizimos' => $totalDizimos,
            'totalOfertas' => $totalOfertas,
            'totalCampanhas' => $totalCampanhas,
            'doacoesUltimoAno' => $doacoesUltimoAno,
            'doacoesUltimoMes' => $doacoesUltimoMes,
            'cargosAtivos' => $cargosAtivos,
            'cargosHistorico' => $cargosHistorico,
            'ministeriosAtivos' => $ministeriosAtivos,
            'historicoMinisterios' => $historicoMinisterios,
            'campanhasParticipadas' => $campanhasParticipadas,
            'totalContribuicoes' => $totalContribuicoes,
            'contribuicoesUltimoAno' => $contribuicoesUltimoAno,
            'tempoMembro' => $tempoMembro,
            'idade' => $idade,
            'dataGeracao' => Carbon::now()->format('d/m/Y H:i:s'),
        ];
        
        $html = view('pdf.ficha-membro', $dados)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Gerar relatório profissional de transações
     */
    public function gerarRelatorioTransacoes($transacoes, $estatisticas, $filtros = [])
    {
        // Buscar configurações da igreja
        $igrejaNome = Configuracao::get('igreja_nome', 'Igreja Batista');
        $igrejaEndereco = Configuracao::get('igreja_endereco', '');
        $igrejaTelefone = Configuracao::get('igreja_telefone', '');
        $igrejaEmail = Configuracao::get('igreja_email', '');
        $igrejaLogo = Configuracao::get('igreja_logo', '');
        $igrejaPastor = Configuracao::get('igreja_pastor', '');
        $igrejaCNPJ = Configuracao::get('igreja_cnpj', '');
        
        // Preparar dados para o template
        $dados = [
            'transacoes' => $transacoes,
            'estatisticas' => $estatisticas,
            'filtros' => $filtros,
            'igrejaNome' => $igrejaNome,
            'igrejaEndereco' => $igrejaEndereco,
            'igrejaTelefone' => $igrejaTelefone,
            'igrejaEmail' => $igrejaEmail,
            'igrejaLogo' => $igrejaLogo,
            'igrejaPastor' => $igrejaPastor,
            'igrejaCNPJ' => $igrejaCNPJ,
            'dataGeracao' => Carbon::now()->format('d/m/Y H:i:s'),
            'periodo' => $this->getPeriodoRelatorio($filtros),
        ];
        
        $html = view('pdf.relatorio-transacoes', $dados)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Obter período do relatório baseado nos filtros
     */
    private function getPeriodoRelatorio($filtros)
    {
        if (isset($filtros['data_inicio']) && isset($filtros['data_fim'])) {
            return 'Período: ' . Carbon::parse($filtros['data_inicio'])->format('d/m/Y') . ' a ' . Carbon::parse($filtros['data_fim'])->format('d/m/Y');
        } elseif (isset($filtros['data_inicio'])) {
            return 'A partir de: ' . Carbon::parse($filtros['data_inicio'])->format('d/m/Y');
        } elseif (isset($filtros['data_fim'])) {
            return 'Até: ' . Carbon::parse($filtros['data_fim'])->format('d/m/Y');
        }
        
        return 'Período: Todos os registros';
    }

    /**
     * Gerar PDF do documento de baixa
     */
    public function gerarDocumentoBaixa($documento)
    {
        // Buscar configurações da igreja
        $igrejaNome = Configuracao::get('igreja_nome', 'Igreja Batista');
        $igrejaEndereco = Configuracao::get('igreja_endereco', '');
        $igrejaTelefone = Configuracao::get('igreja_telefone', '');
        $igrejaEmail = Configuracao::get('igreja_email', '');
        $igrejaLogo = Configuracao::get('igreja_logo', '');
        $igrejaPastor = Configuracao::get('igreja_pastor', '');
        $igrejaCNPJ = Configuracao::get('igreja_cnpj', '');

        // Carregar relacionamentos
        $documento->load(['transacao.membro', 'transacao.campanha']);

        // Preparar dados para o template (sem multa/juros e código de barras que eram ilegais)
        $dados = [
            'documento' => $documento,
            'igrejaNome' => $igrejaNome,
            'igrejaEndereco' => $igrejaEndereco,
            'igrejaTelefone' => $igrejaTelefone,
            'igrejaEmail' => $igrejaEmail,
            'igrejaLogo' => $igrejaLogo,
            'igrejaPastor' => $igrejaPastor,
            'igrejaCNPJ' => $igrejaCNPJ,
            'dataGeracao' => Carbon::now()->format('d/m/Y H:i:s'),
            'tiposDocumento' => \App\Models\DocumentoBaixa::TIPOS_DOCUMENTO,
            'statusDocumento' => \App\Models\DocumentoBaixa::STATUS,
        ];

        $html = view('pdf.documento-baixa', $dados)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Gerar certificado EBD
     */
    public function gerarCertificadoEbd($certificado)
    {
        // Buscar configurações da igreja
        $igrejaNome = Configuracao::get('igreja_nome', 'Igreja Batista');
        $igrejaEndereco = Configuracao::get('igreja_endereco', '');
        $igrejaTelefone = Configuracao::get('igreja_telefone', '');
        $igrejaEmail = Configuracao::get('igreja_email', '');
        $igrejaLogo = Configuracao::get('igreja_logo', '');
        $igrejaPastor = Configuracao::get('igreja_pastor', '');
        $igrejaCNPJ = Configuracao::get('igreja_cnpj', '');
        
        // Carregar relacionamentos
        $certificado->load(['aluno.membro', 'avaliacao']);
        
        $html = view('pdf.certificado-ebd', compact('certificado', 'igrejaNome', 'igrejaEndereco', 'igrejaTelefone', 'igrejaEmail', 'igrejaLogo', 'igrejaPastor', 'igrejaCNPJ'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }


} 