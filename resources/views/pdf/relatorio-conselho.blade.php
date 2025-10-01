<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Conselho - {{ $conselho->titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0284c7;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0284c7;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label, .info-value {
            display: table-cell;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .status-agendada { background-color: #f59e0b; }
        .status-em_andamento { background-color: #10b981; }
        .status-finalizada { background-color: #6b7280; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 11px;
        }
        .table td {
            font-size: 10px;
        }
        .stats {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e5e5e5;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #0284c7;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e5e5e5;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório do Conselho</div>
        <div class="subtitle">{{ $conselho->titulo }}</div>
    </div>

    <div class="section">
        <div class="section-title">Informações Gerais</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Título:</div>
                <div class="info-value">{{ $conselho->titulo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data da Reunião:</div>
                <div class="info-value">{{ $conselho->data_reuniao->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Horário:</div>
                <div class="info-value">{{ $conselho->hora_inicio }} @if($conselho->hora_fim) - {{ $conselho->hora_fim }} @endif</div>
            </div>
            <div class="info-row">
                <div class="info-label">Local:</div>
                <div class="info-value">{{ $conselho->local ?? 'Não informado' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status status-{{ $conselho->status }}">
                        {{ ucfirst(str_replace('_', ' ', $conselho->status)) }}
                    </span>
                </div>
            </div>
            @if($conselho->descricao)
            <div class="info-row">
                <div class="info-label">Descrição:</div>
                <div class="info-value">{{ $conselho->descricao }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Estatísticas de Presença</div>
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number">{{ $conselho->participantes()->count() }}</div>
                <div class="stat-label">Total de Participantes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $conselho->participantes()->where('presente', true)->count() }}</div>
                <div class="stat-label">Presentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $conselho->participantes()->where('presente', false)->count() }}</div>
                <div class="stat-label">Ausentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">
                    @php
                        $total = $conselho->participantes()->count();
                        $presentes = $conselho->participantes()->where('presente', true)->count();
                        $percentual = $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
                    @endphp
                    {{ $percentual }}%
                </div>
                <div class="stat-label">Percentual de Presença</div>
            </div>
        </div>
    </div>

    @if($conselho->participantes()->count() > 0)
    <div class="section">
        <div class="section-title">Lista de Participantes</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Presença</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conselho->participantes as $participante)
                <tr>
                    <td>{{ $participante->user->name ?? 'N/A' }}</td>
                    <td>{{ $participante->user->email ?? 'N/A' }}</td>
                    <td>{{ $participante->cargo ?? 'Membro' }}</td>
                    <td>
                        @if($participante->presente)
                            <span style="color: #10b981; font-weight: bold;">✓ Presente</span>
                        @else
                            <span style="color: #ef4444; font-weight: bold;">✗ Ausente</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($conselho->pautas()->count() > 0)
    <div class="section">
        <div class="section-title">Pautas da Reunião</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Ordem</th>
                    <th>Título</th>
                    <th>Responsável</th>
                    <th>Tempo Est.</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conselho->pautas as $pauta)
                <tr>
                    <td>{{ $pauta->ordem ?? '-' }}</td>
                    <td>{{ $pauta->titulo }}</td>
                    <td>{{ $pauta->responsavel->name ?? 'N/A' }}</td>
                    <td>{{ $pauta->tempo_estimado ?? '-' }} min</td>
                    <td>{{ ucfirst($pauta->status ?? 'pendente') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($conselho->votacoes()->count() > 0)
    <div class="section">
        <div class="section-title">Votações Realizadas</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Total de Votos</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conselho->votacoes as $votacao)
                <tr>
                    <td>{{ $votacao->titulo }}</td>
                    <td>{{ ucfirst($votacao->tipo ?? 'simples') }}</td>
                    <td>{{ ucfirst($votacao->status ?? 'pendente') }}</td>
                    <td>{{ $votacao->votos()->count() }}</td>
                    <td>{{ $votacao->resultado ?? 'Pendente' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($conselho->observacoes)
    <div class="section">
        <div class="section-title">Observações</div>
        <div style="background-color: #f8fafc; padding: 15px; border-left: 4px solid #0284c7;">
            {{ $conselho->observacoes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i') }}</p>
        <p>Sistema CBAV - Controle de Biblioteca e Atividades Virtuais</p>
    </div>
</body>
</html> 