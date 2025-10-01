<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Mensal - {{ $dataInicio->format('F/Y') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #fff;
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
        .periodo {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #1e40af;
        }
        .periodo h2 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .periodo p {
            margin: 0;
            color: #666;
        }
        .resumo {
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
        .detalhamento {
            margin-bottom: 30px;
        }
        .detalhamento h2 {
            color: #1e40af;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .tipo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .tipo-item:last-child {
            border-bottom: none;
        }
        .tipo-nome {
            font-weight: 600;
            color: #374151;
        }
        .tipo-valor {
            font-weight: bold;
            color: #1e40af;
        }
        .transacoes {
            margin-bottom: 30px;
        }
        .transacoes h2 {
            color: #1e40af;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8fafc;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pago {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pendente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-cancelado {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 12px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Financeiro Mensal</h1>
        <p>Congregação Batista Avenida</p>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="periodo">
        <h2>Período do Relatório</h2>
        <p><strong>Mês/Ano:</strong> {{ $dataInicio->format('F/Y') }}</p>
        <p><strong>De:</strong> {{ $dataInicio->format('d/m/Y') }} <strong>Até:</strong> {{ $dataFim->format('d/m/Y') }}</p>
    </div>

    <div class="resumo">
        <div class="card">
            <h3>Total Entradas</h3>
            <p class="valor">R$ {{ number_format($resumo['total_entradas'], 2, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Total Saídas</h3>
            <p class="valor">R$ {{ number_format($resumo['total_saidas'], 2, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Saldo</h3>
            <p class="valor">R$ {{ number_format($resumo['saldo'], 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="detalhamento">
        <h2>Detalhamento por Tipo</h2>
        <div class="tipo-item">
            <span class="tipo-nome">Dízimos</span>
            <span class="tipo-valor">R$ {{ number_format($resumo['dizimos'], 2, ',', '.') }}</span>
        </div>
        <div class="tipo-item">
            <span class="tipo-nome">Ofertas</span>
            <span class="tipo-valor">R$ {{ number_format($resumo['ofertas'], 2, ',', '.') }}</span>
        </div>
        <div class="tipo-item">
            <span class="tipo-nome">Campanhas</span>
            <span class="tipo-valor">R$ {{ number_format($resumo['campanhas'], 2, ',', '.') }}</span>
        </div>
    </div>

    <div class="transacoes">
        <h2>Transações Detalhadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Membro</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transacoes as $transacao)
                <tr>
                    <td>{{ $transacao->data->format('d/m/Y') }}</td>
                    <td>{{ $transacao->membro->name ?? 'Anônimo' }}</td>
                    <td>{{ ucfirst($transacao->tipo) }}</td>
                    <td>{{ Str::limit($transacao->descricao, 40) }}</td>
                    <td>R$ {{ number_format($transacao->valor, 2, ',', '.') }}</td>
                    <td>
                        <span class="status status-{{ $transacao->status }}">
                            {{ ucfirst($transacao->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo Sistema CRM Ministerial</p>
        <p>Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues</p>
    </div>
</body>
</html> 