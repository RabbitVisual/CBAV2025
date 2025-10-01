<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Pessoas - {{ $data_geracao }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #fff;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .info-geral {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #1e40af;
        }
        .info-geral h2 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .info-geral p {
            margin: 0;
            color: #666;
        }
        .estatisticas {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .card {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            min-width: 200px;
            margin-bottom: 15px;
            flex: 1;
            margin-right: 15px;
        }
        .card:last-child {
            margin-right: 0;
        }
        .card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .card .valor {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .secao {
            margin-bottom: 30px;
        }
        .secao h2 {
            color: #1e40af;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .tabela {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .tabela th {
            background-color: #1e40af;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .tabela td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .tabela tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .distribuicao-idade {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .idade-item {
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        .idade-item .faixa {
            font-weight: bold;
            color: #1e40af;
            font-size: 16px;
        }
        .idade-item .quantidade {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
        }
        .idade-item .percentual {
            font-size: 12px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>Relatório de Pessoas</h1>
        <p>Sistema CBAV - CRM Ministerial</p>
        <p>Gerado em: {{ $data_geracao }}</p>
    </div>

    <!-- Informações Gerais -->
    <div class="info-geral">
        <h2>📊 Resumo Geral</h2>
        <p>Este relatório apresenta uma análise completa da gestão de pessoas da igreja, incluindo estatísticas de membros, distribuição por idade, ministérios e localização geográfica.</p>
    </div>

    <!-- Estatísticas Principais -->
    <div class="estatisticas">
        <div class="card">
            <h3>Total de Membros</h3>
            <div class="valor">{{ number_format($total_membros ?? 0) }}</div>
        </div>
        <div class="card">
            <h3>Membros Ativos</h3>
            <div class="valor">{{ number_format($membros_ativos ?? 0) }}</div>
        </div>
        <div class="card">
            <h3>Ministérios</h3>
            <div class="valor">{{ number_format($total_ministerios ?? 0) }}</div>
        </div>
        <div class="card">
            <h3>Aniversariantes</h3>
            <div class="valor">{{ number_format($aniversariantes_mes ?? 0) }}</div>
        </div>
    </div>

    <!-- Distribuição por Idade -->
    <div class="secao">
        <h2>👥 Distribuição por Faixa Etária</h2>
        <div class="distribuicao-idade">
            <div class="idade-item">
                <div class="faixa">18-30 anos</div>
                            <div class="quantidade">{{ $faixa_18_30 ?? 0 }}</div>
            <div class="percentual">{{ ($total_membros ?? 0) > 0 ? round((($faixa_18_30 ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</div>
            </div>
            <div class="idade-item">
                <div class="faixa">31-50 anos</div>
                            <div class="quantidade">{{ $faixa_31_50 ?? 0 }}</div>
            <div class="percentual">{{ ($total_membros ?? 0) > 0 ? round((($faixa_31_50 ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</div>
            </div>
            <div class="idade-item">
                <div class="faixa">51-65 anos</div>
                            <div class="quantidade">{{ $faixa_51_65 ?? 0 }}</div>
            <div class="percentual">{{ ($total_membros ?? 0) > 0 ? round((($faixa_51_65 ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</div>
            </div>
            <div class="idade-item">
                <div class="faixa">65+ anos</div>
                            <div class="quantidade">{{ $faixa_65_mais ?? 0 }}</div>
            <div class="percentual">{{ ($total_membros ?? 0) > 0 ? round((($faixa_65_mais ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</div>
            </div>
        </div>
    </div>

    <!-- Membros por Ministério -->
    <div class="secao">
        <h2>⛪ Membros por Ministério</h2>
        @if($membros_por_ministerio->count() > 0)
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Ministério</th>
                        <th>Total de Membros</th>
                        <th>Percentual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membros_por_ministerio as $ministerio)
                        <tr>
                            <td>{{ $ministerio->nome }}</td>
                            <td>{{ $ministerio->total ?? 0 }}</td>
                            <td>{{ ($total_membros ?? 0) > 0 ? round((($ministerio->total ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666; font-style: italic;">Nenhum ministério encontrado</p>
        @endif
    </div>

    <!-- Membros por Cidade -->
    <div class="secao">
        <h2>📍 Membros por Cidade</h2>
        @if($membros_por_cidade->count() > 0)
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Cidade</th>
                        <th>Total de Membros</th>
                        <th>Percentual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membros_por_cidade as $cidade)
                        <tr>
                            <td>{{ $cidade->cidade }}</td>
                            <td>{{ $cidade->total ?? 0 }}</td>
                            <td>{{ ($total_membros ?? 0) > 0 ? round((($cidade->total ?? 0) / ($total_membros ?? 1)) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666; font-style: italic;">Nenhuma cidade encontrada</p>
        @endif
    </div>

    <!-- Análise e Recomendações -->
    <div class="secao">
        <h2>📈 Análise e Recomendações</h2>
        
        <h3 style="color: #1e40af; margin-bottom: 10px;">Pontos Positivos:</h3>
        <ul style="margin-bottom: 20px;">
            <li>Total de {{ $total_membros ?? 0 }} membros ativos no sistema</li>
            <li>{{ $total_ministerios ?? 0 }} ministérios organizados</li>
            <li>{{ $aniversariantes_mes ?? 0 }} aniversariantes este mês</li>
        </ul>

        <h3 style="color: #dc2626; margin-bottom: 10px;">Oportunidades de Melhoria:</h3>
        <ul style="margin-bottom: 20px;">
            @if($faixa_18_30 < ($total_membros * 0.2))
                <li>Baixa representatividade de jovens (18-30 anos) - apenas {{ $faixa_18_30 ?? 0 }} membros</li>
            @endif
            @if($membros_por_ministerio->count() < 3)
                <li>Poucos ministérios ativos - considere criar novos ministérios</li>
            @endif
            @if($membros_por_cidade->count() < 2)
                <li>Concentração geográfica - considere expandir para outras cidades</li>
            @endif
        </ul>

        <h3 style="color: #059669; margin-bottom: 10px;">Ações Recomendadas:</h3>
        <ul>
            <li>Implementar programa de captação de jovens</li>
            <li>Criar novos ministérios para diversificar a atuação</li>
            <li>Desenvolver estratégias de expansão geográfica</li>
            <li>Realizar campanhas de aniversariantes mensais</li>
        </ul>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p><strong>Relatório Gerado Automaticamente</strong></p>
        <p>Sistema CBAV - CRM Ministerial</p>
        <p>Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues</p>
        <p>Data de geração: {{ $data_geracao }}</p>
    </div>
</body>
</html> 