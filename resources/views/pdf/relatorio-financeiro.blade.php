<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2E7D32;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2E7D32;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #E3F2FD;
            color: #1976D2;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-left: 4px solid #1976D2;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            text-align: center;
            border: 1px solid #E0E0E0;
        }
        
        .summary-item .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #2E7D32;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        th {
            background-color: #1976D2;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #E0E0E0;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #F5F5F5;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
            color: #2E7D32;
        }
        
        .percentage {
            text-align: center;
            color: #666;
        }
        
        .position {
            text-align: center;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .chart-placeholder {
            text-align: center;
            padding: 20px;
            background-color: #F5F5F5;
            border: 1px dashed #CCC;
            margin: 15px 0;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>RELATÓRIO FINANCEIRO</h1>
        <div class="subtitle">CBAV CRM Ministerial</div>
        <div class="subtitle">Período: 
            @if($periodo == 'mes')
                Mensal - {{ \Carbon\Carbon::create($ano, $mes, 1)->format('F') }}/{{ $ano }}
            @elseif($periodo == 'ano')
                Anual - {{ $ano }}
            @elseif($periodo == 'personalizado')
                Personalizado
            @else
                Período não especificado
            @endif
        </div>
        <div class="subtitle">Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <!-- Resumo Geral -->
    <div class="section">
        <div class="section-title">RESUMO GERAL</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Arrecadação Total</div>
                <div class="value">R$ {{ number_format($dados['arrecadacao_total'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Período</div>
                <div class="value">{{ $dados['periodo'] }}</div>
            </div>
        </div>
    </div>

    <!-- Detalhamento por Tipo -->
    @if(isset($dados['por_tipo']))
    <div class="section">
        <div class="section-title">DETALHAMENTO POR TIPO</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Total (R$)</th>
                    <th>Quantidade</th>
                    <th>Média (R$)</th>
                    <th>% do Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['por_tipo'] as $tipo)
                <tr>
                    <td>{{ ucfirst($tipo->tipo) }}</td>
                    <td class="amount">R$ {{ number_format($tipo->total, 2, ',', '.') }}</td>
                    <td class="position">{{ $tipo->quantidade }}</td>
                    <td class="amount">R$ {{ number_format($tipo->total / $tipo->quantidade, 2, ',', '.') }}</td>
                    <td class="percentage">{{ number_format(($tipo->total / $dados['arrecadacao_total']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Evolução Temporal -->
    @if(isset($dados['por_dia']))
    <div class="section">
        <div class="section-title">EVOLUÇÃO TEMPORAL</div>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Arrecadação (R$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['por_dia'] as $dia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($dia->dia)->format('d/m/Y') }}</td>
                    <td class="amount">R$ {{ number_format($dia->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Top Doadores -->
    @if(isset($dados['top_doadores']))
    <div class="section">
        <div class="section-title">TOP 10 DOADORES</div>
        <table>
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Membro</th>
                    <th>Total Doado (R$)</th>
                    <th>% do Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['top_doadores'] as $index => $doador)
                <tr>
                    <td class="position">#{{ $index + 1 }}</td>
                    <td>{{ $doador->membro->nome ?? 'Anônimo' }}</td>
                    <td class="amount">R$ {{ number_format($doador->total, 2, ',', '.') }}</td>
                    <td class="percentage">{{ number_format(($doador->total / $dados['arrecadacao_total']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Relatório por Mês (se aplicável) -->
    @if(isset($dados['por_mes']))
    <div class="section">
        <div class="section-title">DISTRIBUIÇÃO MENSUAL</div>
        <table>
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Arrecadação (R$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['por_mes'] as $mes)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($mes->mes)->format('F') }}</td>
                    <td class="amount">R$ {{ number_format($mes->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado automaticamente pelo sistema CBAV CRM Ministerial</p>
        <p>Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues</p>
    </div>
</body>
</html> 