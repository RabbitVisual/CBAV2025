<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas Quiz Bíblico - CBAV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            color: #374151;
            border-left: 4px solid #2563eb;
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        .stats-cell.header {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        
        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stats-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .table th {
            background-color: #f3f4f6;
            padding: 10px 12px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        
        .table td {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .level-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .level-facil {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .level-medio {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .level-dificil {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Estatísticas Quiz Bíblico</h1>
        <p>Sistema CBAV - Relatório Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="section">
        <div class="section-title">Estatísticas Gerais</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['total_perguntas'] }}</div>
                    <div class="stats-label">Total de Perguntas</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['perguntas_ativas'] }}</div>
                    <div class="stats-label">Perguntas Ativas</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['total_sessoes'] }}</div>
                    <div class="stats-label">Total de Sessões</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['sessoes_hoje'] }}</div>
                    <div class="stats-label">Sessões Hoje</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['sessoes_semana'] }}</div>
                    <div class="stats-label">Sessões Esta Semana</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estatisticas['sessoes_mes'] }}</div>
                    <div class="stats-label">Sessões Este Mês</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Melhores Pontuações -->
    <div class="section">
        <div class="section-title">Top 10 Melhores Pontuações</div>
        
        @if($melhoresPontuacoes->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Pontuação</th>
                    <th>Percentual</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($melhoresPontuacoes as $index => $sessao)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sessao->user->name ?? 'N/A' }}</td>
                    <td>{{ $sessao->pontuacao_total }} pontos</td>
                    <td>{{ number_format($sessao->percentual, 1) }}%</td>
                    <td>{{ $sessao->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #6b7280; font-style: italic;">
            Nenhuma sessão encontrada para gerar ranking.
        </p>
        @endif
    </div>

    <!-- Estatísticas por Nível -->
    <div class="section">
        <div class="section-title">Estatísticas por Nível</div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nível</th>
                    <th>Perguntas</th>
                    <th>Sessões</th>
                    <th>Média %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="level-badge level-facil">Fácil</span></td>
                    <td>{{ $estatisticasNivel['facil']['perguntas'] ?? 0 }}</td>
                    <td>{{ $estatisticasNivel['facil']['sessoes'] ?? 0 }}</td>
                    <td>{{ number_format($estatisticasNivel['facil']['media_percentual'] ?? 0, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="level-badge level-medio">Médio</span></td>
                    <td>{{ $estatisticasNivel['medio']['perguntas'] ?? 0 }}</td>
                    <td>{{ $estatisticasNivel['medio']['sessoes'] ?? 0 }}</td>
                    <td>{{ number_format($estatisticasNivel['medio']['media_percentual'] ?? 0, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="level-badge level-dificil">Difícil</span></td>
                    <td>{{ $estatisticasNivel['dificil']['perguntas'] ?? 0 }}</td>
                    <td>{{ $estatisticasNivel['dificil']['sessoes'] ?? 0 }}</td>
                    <td>{{ number_format($estatisticasNivel['dificil']['media_percentual'] ?? 0, 1) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Últimas Sessões -->
    @if(isset($ultimasSessoes) && $ultimasSessoes->count() > 0)
    <div class="section">
        <div class="section-title">Últimas Sessões</div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Nível</th>
                    <th>Pontuação</th>
                    <th>Percentual</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimasSessoes as $sessao)
                <tr>
                    <td>{{ $sessao->user->name ?? 'N/A' }}</td>
                    <td>
                        <span class="level-badge level-{{ $sessao->nivel }}">
                            {{ ucfirst($sessao->nivel) }}
                        </span>
                    </td>
                    <td>{{ $sessao->pontuacao_total }} pontos</td>
                    <td>{{ number_format($sessao->percentual, 1) }}%</td>
                    <td>{{ $sessao->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Relatório gerado automaticamente pelo Sistema CBAV</p>
        <p>© {{ date('Y') }} CBAV - Todos os direitos reservados</p>
    </div>
</body>
</html> 