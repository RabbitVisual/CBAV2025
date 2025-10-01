<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Presença - {{ $conselho->titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #0284c7;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        
        .organization-name {
            font-size: 18px;
            font-weight: bold;
            color: #0284c7;
            margin: 5px 0;
        }
        
        .church-name {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin: 5px 0;
        }
        
        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin: 15px 0 5px 0;
            text-transform: uppercase;
        }
        
        .report-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .meeting-info {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label, .info-value {
            display: table-cell;
            padding: 4px 15px 4px 0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            width: 25%;
            color: #374151;
        }
        
        .info-value {
            color: #1f2937;
        }
        
        .statistics {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        
        .stat-container {
            display: table-row;
        }
        
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 15px 10px;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            width: 20%;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #0284c7;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }
        
        .participants-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .participants-table th,
        .participants-table td {
            border: 1px solid #d1d5db;
            padding: 10px 8px;
            text-align: left;
        }
        
        .participants-table th {
            background-color: #0284c7;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .participants-table td {
            font-size: 11px;
        }
        
        .participants-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .status-present {
            background-color: #dcfce7 !important;
            color: #166534;
            font-weight: bold;
        }
        
        .status-absent {
            background-color: #fee2e2 !important;
            color: #dc2626;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-present {
            background-color: #10b981;
            color: white;
        }
        
        .badge-absent {
            background-color: #ef4444;
            color: white;
        }
        
        .badge-scheduled {
            background-color: #f59e0b;
            color: white;
        }
        
        .badge-in-progress {
            background-color: #3b82f6;
            color: white;
        }
        
        .badge-finished {
            background-color: #6b7280;
            color: white;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0284c7;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin: 25px 0 15px 0;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        
        .contact-info {
            font-size: 10px;
            color: #6b7280;
            margin-top: 10px;
        }
        
        .quorum-alert {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 10px;
            margin: 15px 0;
            font-size: 11px;
        }
        
        .quorum-alert.success {
            background-color: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        
        .quorum-alert.warning {
            background-color: #fef3c7;
            border-color: #f59e0b;
            color: #92400e;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($configuracoes['app_logo'])
            <img src="{{ public_path('storage/' . $configuracoes['app_logo']) }}" alt="Logo" class="logo">
        @endif
        
        <div class="organization-name">{{ $configuracoes['app_name'] }}</div>
        <div class="church-name">{{ $configuracoes['igreja_nome'] }}</div>
        
        <div class="report-title">Relatório de Presença</div>
        <div class="report-subtitle">{{ $conselho->titulo }}</div>
    </div>

    <div class="meeting-info">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Reunião:</div>
                <div class="info-value">{{ $conselho->titulo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data:</div>
                <div class="info-value">{{ $conselho->data_reuniao->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Horário:</div>
                <div class="info-value">
                    {{ $conselho->hora_inicio }}
                    @if($conselho->hora_fim)
                        - {{ $conselho->hora_fim }}
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Local:</div>
                <div class="info-value">{{ $conselho->local ?? 'Não informado' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge badge-{{ $conselho->status }}">
                        {{ ucfirst(str_replace('_', ' ', $conselho->status)) }}
                    </span>
                </div>
            </div>
            @if($conselho->presidente)
            <div class="info-row">
                <div class="info-label">Presidente:</div>
                <div class="info-value">{{ $conselho->presidente->name }}</div>
            </div>
            @endif
            @if($conselho->secretario)
            <div class="info-row">
                <div class="info-label">Secretário:</div>
                <div class="info-value">{{ $conselho->secretario->name }}</div>
            </div>
            @endif
        </div>
    </div>

    @php
        $totalParticipantes = $participantes->count();
        $presentes = $participantes->where('presente', true)->count();
        $ausentes = $totalParticipantes - $presentes;
        $percentualPresenca = $totalParticipantes > 0 ? round(($presentes / $totalParticipantes) * 100, 1) : 0;
        $quorumMinimo = $conselho->quorum_minimo ?? 50;
        $quorumAtingido = $percentualPresenca >= $quorumMinimo;
    @endphp

    <div class="statistics">
        <div class="stat-container">
            <div class="stat-item">
                <span class="stat-number">{{ $totalParticipantes }}</span>
                <span class="stat-label">Total de Participantes</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $presentes }}</span>
                <span class="stat-label">Presentes</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $ausentes }}</span>
                <span class="stat-label">Ausentes</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $percentualPresenca }}%</span>
                <span class="stat-label">Percentual de Presença</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $quorumMinimo }}%</span>
                <span class="stat-label">Quórum Mínimo</span>
            </div>
        </div>
    </div>

    @if($conselho->status === 'em_andamento' || $conselho->status === 'finalizada')
    <div class="quorum-alert {{ $quorumAtingido ? 'success' : 'warning' }}">
        <strong>Status do Quórum:</strong>
        @if($quorumAtingido)
            ✓ Quórum atingido ({{ $percentualPresenca }}% presentes, mínimo necessário: {{ $quorumMinimo }}%)
        @else
            ⚠ Quórum não atingido ({{ $percentualPresenca }}% presentes, mínimo necessário: {{ $quorumMinimo }}%)
        @endif
    </div>
    @endif

    <div class="section-title">Lista de Participantes</div>

    @if($participantes->count() > 0)
    <table class="participants-table">
        <thead>
            <tr>
                <th style="width: 35%">Nome</th>
                <th style="width: 30%">E-mail</th>
                <th style="width: 20%">Cargo</th>
                <th style="width: 10%">Presença</th>
                <th style="width: 5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participantes as $participante)
            <tr class="{{ $participante->presente ? 'status-present' : 'status-absent' }}">
                <td>{{ $participante->user->name ?? 'N/A' }}</td>
                <td>{{ $participante->user->email ?? 'N/A' }}</td>
                <td>{{ $participante->cargo ?? 'Membro' }}</td>
                <td style="text-align: center;">
                    @if($participante->presente)
                        <span class="status-badge badge-present">Presente</span>
                    @else
                        <span class="status-badge badge-absent">Ausente</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($participante->presente)
                        ✓
                    @else
                        ✗
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #6b7280; margin: 30px 0;">
        Nenhum participante cadastrado para esta reunião.
    </p>
    @endif

    @if($conselho->observacoes)
    <div class="section-title">Observações</div>
    <div style="background-color: #f8fafc; padding: 15px; border-left: 4px solid #0284c7; margin: 15px 0;">
        {{ $conselho->observacoes }}
    </div>
    @endif

    <div class="footer">
        <p><strong>Relatório gerado em:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        
        @if($configuracoes['contact_email'] || $configuracoes['contact_phone'] || $configuracoes['address'])
        <div class="contact-info">
            @if($configuracoes['address'])
                <div>{{ $configuracoes['address'] }}</div>
            @endif
            @if($configuracoes['contact_phone'])
                <div>Telefone: {{ $configuracoes['contact_phone'] }}</div>
            @endif
            @if($configuracoes['contact_email'])
                <div>E-mail: {{ $configuracoes['contact_email'] }}</div>
            @endif
        </div>
        @endif
        
        <div style="margin-top: 15px; font-style: italic;">
            Sistema {{ $configuracoes['app_name'] }} - Controle de Biblioteca e Atividades Virtuais
        </div>
    </div>
</body>
</html> 