<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Transações - {{ $igrejaNome }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #1976D2;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        
        .igreja-nome {
            font-size: 24px;
            font-weight: bold;
            color: #1976D2;
            margin: 10px 0;
        }
        
        .igreja-info {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        
        .titulo-relatorio {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            color: #333;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .estatisticas {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .estatistica-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 5px;
            min-width: 120px;
            flex: 1;
        }
        
        .estatistica-valor {
            font-size: 18px;
            font-weight: bold;
            color: #1976D2;
        }
        
        .estatistica-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .filtros {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1976D2;
        }
        
        .filtros h3 {
            margin: 0 0 10px 0;
            color: #1976D2;
            font-size: 14px;
        }
        
        .filtros p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .tabela-container {
            margin: 20px 0;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        
        th {
            background: #1976D2;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1565C0;
        }
        
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .valor-positivo {
            color: #2E7D32;
            font-weight: bold;
        }
        
        .valor-negativo {
            color: #C62828;
            font-weight: bold;
        }
        
        .status-confirmado {
            color: #2E7D32;
            font-weight: bold;
        }
        
        .status-pendente {
            color: #F57C00;
            font-weight: bold;
        }
        
        .status-cancelado {
            color: #C62828;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .assinatura {
            margin-top: 40px;
            text-align: center;
        }
        
        .linha-assinatura {
            width: 200px;
            border-top: 1px solid #333;
            margin: 10px auto;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .resumo-final {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        
        .resumo-final h3 {
            margin: 0 0 10px 0;
            color: #2E7D32;
            font-size: 14px;
        }
        
        .resumo-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }
        
        .resumo-valor {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        @if($igrejaLogo)
            <img src="{{ storage_path('app/public/' . $igrejaLogo) }}" alt="Logo" class="logo">
        @endif
        
        <div class="igreja-nome">{{ $igrejaNome }}</div>
        
        @if($igrejaEndereco)
            <div class="igreja-info">{{ $igrejaEndereco }}</div>
        @endif
        
        @if($igrejaTelefone || $igrejaEmail)
            <div class="igreja-info">
                @if($igrejaTelefone){{ $igrejaTelefone }}@endif
                @if($igrejaTelefone && $igrejaEmail) | @endif
                @if($igrejaEmail){{ $igrejaEmail }}@endif
            </div>
        @endif
        
        @if($igrejaCNPJ)
            <div class="igreja-info">CNPJ: {{ $igrejaCNPJ }}</div>
        @endif
    </div>

    <!-- Título do Relatório -->
    <div class="titulo-relatorio">
        Relatório de Transações Financeiras
    </div>

    <!-- Informações do Relatório -->
    <div class="filtros">
        <h3>📊 Informações do Relatório</h3>
        <p><strong>Período:</strong> {{ $periodo }}</p>
        <p><strong>Data de Geração:</strong> {{ $dataGeracao }}</p>
        <p><strong>Total de Transações:</strong> {{ $estatisticas['total_transacoes'] }}</p>
        
        @if(isset($filtros['status']))
            <p><strong>Status Filtrado:</strong> {{ ucfirst($filtros['status']) }}</p>
        @endif
        
        @if(isset($filtros['tipo']))
            <p><strong>Tipo Filtrado:</strong> {{ ucfirst($filtros['tipo']) }}</p>
        @endif
    </div>

    <!-- Estatísticas -->
    <div class="estatisticas">
        <div class="estatistica-item">
            <div class="estatistica-valor valor-positivo">
                R$ {{ number_format($estatisticas['receitas'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Receitas</div>
        </div>
        
        <div class="estatistica-item">
            <div class="estatistica-valor valor-negativo">
                R$ {{ number_format($estatisticas['despesas'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Despesas</div>
        </div>
        
        <div class="estatistica-item">
            <div class="estatistica-valor {{ $estatisticas['saldo'] >= 0 ? 'valor-positivo' : 'valor-negativo' }}">
                R$ {{ number_format($estatisticas['saldo'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Saldo</div>
        </div>
        
        <div class="estatistica-item">
            <div class="estatistica-valor status-pendente">
                R$ {{ number_format($estatisticas['pendentes'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Pendentes</div>
        </div>
        
        <div class="estatistica-item">
            <div class="estatistica-valor status-confirmado">
                R$ {{ number_format($estatisticas['confirmadas'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Confirmadas</div>
        </div>
        
        <div class="estatistica-item">
            <div class="estatistica-valor status-cancelado">
                R$ {{ number_format($estatisticas['canceladas'], 2, ',', '.') }}
            </div>
            <div class="estatistica-label">Canceladas</div>
        </div>
    </div>

    <!-- Tabela de Transações -->
    <div class="tabela-container">
        <h3>📋 Detalhamento das Transações</h3>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Membro</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Campanha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transacoes as $transacao)
                    <tr>
                        <td>{{ $transacao->id }}</td>
                        <td>{{ $transacao->data ? $transacao->data->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $transacao->descricao }}</td>
                        <td>{{ $transacao->membro ? $transacao->membro->nome : 'Anônimo' }}</td>
                        <td class="{{ $transacao->tipo === 'entrada' ? 'valor-positivo' : 'valor-negativo' }}">
                            R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                        </td>
                        <td>{{ ucfirst($transacao->tipo) }}</td>
                        <td class="status-{{ $transacao->status }}">
                            {{ ucfirst($transacao->status) }}
                        </td>
                        <td>{{ $transacao->campanha ? $transacao->campanha->titulo : 'Sem campanha' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                            Nenhuma transação encontrada para os filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Resumo Final -->
    <div class="resumo-final">
        <h3>💰 Resumo Financeiro</h3>
        
        <div class="resumo-item">
            <span>Total de Receitas:</span>
            <span class="resumo-valor valor-positivo">R$ {{ number_format($estatisticas['receitas'], 2, ',', '.') }}</span>
        </div>
        
        <div class="resumo-item">
            <span>Total de Despesas:</span>
            <span class="resumo-valor valor-negativo">R$ {{ number_format($estatisticas['despesas'], 2, ',', '.') }}</span>
        </div>
        
        <div class="resumo-item">
            <span>Saldo Final:</span>
            <span class="resumo-valor {{ $estatisticas['saldo'] >= 0 ? 'valor-positivo' : 'valor-negativo' }}">
                R$ {{ number_format($estatisticas['saldo'], 2, ',', '.') }}
            </span>
        </div>
        
        <div class="resumo-item">
            <span>Transações Pendentes:</span>
            <span class="resumo-valor status-pendente">R$ {{ number_format($estatisticas['pendentes'], 2, ',', '.') }}</span>
        </div>
        
        <div class="resumo-item">
            <span>Transações Confirmadas:</span>
            <span class="resumo-valor status-confirmado">R$ {{ number_format($estatisticas['confirmadas'], 2, ',', '.') }}</span>
        </div>
        
        <div class="resumo-item">
            <span>Transações Canceladas:</span>
            <span class="resumo-valor status-cancelado">R$ {{ number_format($estatisticas['canceladas'], 2, ',', '.') }}</span>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p><strong>Documento gerado em:</strong> {{ $dataGeracao }}</p>
        <p><strong>Sistema:</strong> CBAV - Congregação Batista Avenida</p>
        <p><strong>Este documento é oficial e contém informações confidenciais da igreja.</strong></p>
    </div>

    <!-- Assinatura -->
    <div class="assinatura">
        <div class="linha-assinatura"></div>
        <p><strong>{{ $igrejaPastor ?: 'Pastor/Presidente' }}</strong></p>
        <p>{{ $igrejaNome }}</p>
        <p>Convenção Batista Brasileira</p>
    </div>
</body>
</html> 