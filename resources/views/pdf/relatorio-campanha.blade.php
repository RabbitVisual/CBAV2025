<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório da Campanha - {{ $campanha->titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .page {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Cabeçalho */
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .header .info {
            font-size: 11px;
            color: #888;
        }
        
        /* Informações da Campanha */
        .campaign-details {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .campaign-details h2 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 12px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-label {
            font-weight: bold;
            color: #374151;
            font-size: 11px;
        }
        
        .detail-value {
            color: #1f2937;
            font-size: 11px;
        }
        
        /* Progresso */
        .progress-section {
            margin: 15px 0;
        }
        
        .progress-bar {
            width: 100%;
            height: 15px;
            background: #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin: 8px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            border-radius: 8px;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        
        /* Estatísticas */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        
        .stat-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 8px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        /* Resumo Financeiro */
        .financial-summary {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .financial-summary h3 {
            color: #0369a1;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #0ea5e9;
            padding-bottom: 5px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 10px;
        }
        
        .summary-label {
            font-weight: bold;
            color: #374151;
        }
        
        .summary-value {
            color: #1f2937;
        }
        
        /* Tabela de Transações */
        .transactions-section {
            margin: 20px 0;
        }
        
        .transactions-section h3 {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
        }
        
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        .transactions-table th {
            background: #f1f5f9;
            color: #374151;
            font-weight: bold;
            padding: 8px 4px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
        }
        
        .transactions-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .transactions-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-confirmado {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-pendente {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-cancelado {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Estatísticas Detalhadas */
        .detailed-stats {
            background: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .detailed-stats h3 {
            color: #15803d;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #22c55e;
            padding-bottom: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        /* Rodapé */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
        }
        
        /* Quebra de página */
        .page-break {
            page-break-before: always;
        }
        
        /* Responsividade para PDF */
        @media print {
            body {
                font-size: 10px;
            }
            
            .page {
                padding: 15px;
            }
            
            .stats-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Destaque para valores importantes */
        .highlight {
            background: #1e40af;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .success-highlight {
            background: #22c55e;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .warning-highlight {
            background: #f59e0b;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>RELATÓRIO DE CAMPANHA</h1>
            <div class="subtitle">{{ $campanha->titulo }}</div>
            <div class="info">
                Gerado em: {{ now()->format('d/m/Y H:i') }} | 
                Período: {{ $campanha->data_inicio->format('d/m/Y') }} a {{ $campanha->data_fim->format('d/m/Y') }}
            </div>
        </div>

        <!-- Informações da Campanha -->
        <div class="campaign-details">
            <h2>INFORMAÇÕES DA CAMPANHA</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Título:</span>
                    <span class="detail-value">{{ $campanha->titulo }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tipo:</span>
                    <span class="detail-value">{{ ucfirst($campanha->tipo) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">{{ ucfirst($campanha->status) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duração:</span>
                    <span class="detail-value">{{ $campanha->data_inicio->diffInDays($campanha->data_fim) }} dias</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Data Início:</span>
                    <span class="detail-value">{{ $campanha->data_inicio->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Data Fim:</span>
                    <span class="detail-value">{{ $campanha->data_fim->format('d/m/Y') }}</span>
                </div>
            </div>
            
            @if($campanha->descricao)
            <div style="margin-top: 12px;">
                <div style="font-weight: bold; color: #374151; margin-bottom: 5px;">Descrição:</div>
                <div style="color: #374151; line-height: 1.4;">{{ $campanha->descricao }}</div>
            </div>
            @endif
        </div>

        <!-- Estatísticas Principais -->
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-value">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</div>
                <div class="stat-label">Meta</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</div>
                <div class="stat-label">Arrecadado</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($campanha->progresso, 1) }}%</div>
                <div class="stat-label">Progresso</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $campanha->transacoes->count() }}</div>
                <div class="stat-label">Transações</div>
            </div>
        </div>

        <!-- Progresso Visual -->
        <div class="progress-section">
            <div style="font-weight: bold; color: #374151; margin-bottom: 5px;">PROGRESSO DA CAMPANHA</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min($campanha->progresso, 100) }}%"></div>
            </div>
            <div class="progress-info">
                <span>R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }} arrecadado</span>
                <span>{{ number_format($campanha->progresso, 1) }}% concluído</span>
                <span>R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }} meta</span>
            </div>
        </div>

        <!-- Resumo Financeiro -->
        <div class="financial-summary">
            <h3>RESUMO FINANCEIRO</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Meta da Campanha:</span>
                    <span class="summary-value">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Arrecadado:</span>
                    <span class="summary-value">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Restante:</span>
                    <span class="summary-value">R$ {{ number_format($campanha->meta_valor - $campanha->valor_arrecadado, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Percentual de Conclusão:</span>
                    <span class="summary-value">{{ number_format($campanha->progresso, 1) }}%</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dias Restantes:</span>
                    <span class="summary-value">{{ max(0, now()->diffInDays($campanha->data_fim, false)) }} dias</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Status:</span>
                    <span class="summary-value">
                        @if($campanha->progresso >= 100)
                            <span class="success-highlight">CONCLUÍDA</span>
                        @elseif($campanha->progresso >= 75)
                            <span class="highlight">EM ANDAMENTO</span>
                        @else
                            <span class="warning-highlight">EM INÍCIO</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        @if(isset($estatisticas))
        <!-- Estatísticas Detalhadas -->
        <div class="detailed-stats">
            <h3>ESTATÍSTICAS DETALHADAS</h3>
            <div class="stats-grid">
                <div class="summary-item">
                    <span class="summary-label">Total de Transações:</span>
                    <span class="summary-value">{{ $estatisticas['total_transacoes'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Confirmado:</span>
                    <span class="summary-value">R$ {{ number_format($estatisticas['total_confirmado'], 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Pendente:</span>
                    <span class="summary-value">R$ {{ number_format($estatisticas['total_pendente'], 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Média por Transação:</span>
                    <span class="summary-value">R$ {{ number_format($estatisticas['media_por_transacao'], 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Maior Contribuição:</span>
                    <span class="summary-value">R$ {{ number_format($estatisticas['maior_contribuicao'], 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Menor Contribuição:</span>
                    <span class="summary-value">R$ {{ number_format($estatisticas['menor_contribuicao'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($campanha->transacoes->count() > 0)
        <!-- Transações -->
        <div class="transactions-section">
            <h3>TRANSAÇÕES DA CAMPANHA</h3>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Membro</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campanha->transacoes->sortByDesc('created_at')->take(20) as $transacao)
                    <tr>
                        <td>{{ $transacao->membro->nome ?? 'Anônimo' }}</td>
                        <td>R$ {{ number_format($transacao->valor, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($transacao->tipo) }}</td>
                        <td>
                            <span class="status-badge status-{{ $transacao->status }}">
                                {{ ucfirst($transacao->status) }}
                            </span>
                        </td>
                        <td>{{ $transacao->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($campanha->transacoes->count() > 20)
            <div style="margin-top: 10px; text-align: center; font-size: 9px; color: #6b7280;">
                Mostrando as 20 transações mais recentes de {{ $campanha->transacoes->count() }} total
            </div>
            @endif
            
            <div style="margin-top: 15px; text-align: right;">
                <div class="summary-item">
                    <span class="summary-label">Total de Transações:</span>
                    <span class="summary-value">{{ $campanha->transacoes->count() }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Total Confirmado:</span>
                    <span class="summary-value">R$ {{ number_format($campanha->transacoes->where('status', 'confirmado')->sum('valor'), 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Valor Total Pendente:</span>
                    <span class="summary-value">R$ {{ number_format($campanha->transacoes->where('status', 'pendente')->sum('valor'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="transactions-section">
            <h3>TRANSAÇÕES DA CAMPANHA</h3>
            <div style="text-align: center; color: #6b7280; padding: 20px; font-size: 11px;">
                Nenhuma transação registrada para esta campanha.
            </div>
        </div>
        @endif

        <!-- Rodapé -->
        <div class="footer">
            <div style="font-weight: bold; margin-bottom: 5px;">Relatório gerado automaticamente pelo Sistema CBAV</div>
            <div>Data de geração: {{ now()->format('d/m/Y H:i:s') }}</div>
            <div style="margin-top: 5px;">Este relatório contém informações confidenciais da campanha "{{ $campanha->titulo }}"</div>
        </div>
    </div>
</body>
</html> 