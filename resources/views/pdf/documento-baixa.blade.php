<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Baixa Patrimonial - {{ $documento->numero_documento }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #2d3748;
            margin: 0;
            padding: 15px;
            background: #ffffff;
        }
        
        .document-container {
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #2563eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Header Profissional */
        .document-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .document-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #ef4444 50%, #10b981 100%);
        }
        
        .organization-logo {
            margin-bottom: 12px;
        }
        
        .organization-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 6px 0;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .organization-subtitle {
            font-size: 11px;
            margin-bottom: 4px;
            opacity: 0.9;
        }
        
        .organization-details {
            font-size: 9px;
            opacity: 0.85;
            line-height: 1.3;
        }
        
        .document-type-badge {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            display: inline-block;
        }
        
        /* Content Area */
        .document-content {
            padding: 20px;
            background: #f8fafc;
        }
        
        .section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .section-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 10px 15px;
            border-bottom: 1px solid #cbd5e1;
            border-left: 4px solid #3b82f6;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #334155;
            margin: 0;
        }
        
        .section-content {
            padding: 15px;
        }
        
        /* Info Grid Responsivo */
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-row:nth-child(even) {
            background: #f8fafc;
        }
        
        .info-label, .info-value {
            display: table-cell;
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        
        .info-label {
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
            width: 40%;
            background: #f8fafc;
        }
        
        .info-value {
            font-size: 10px;
            color: #1e293b;
            font-weight: 500;
        }
        
        .info-value.highlight {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-quitado {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        
        .status-processado {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #60a5fa;
        }
        
        .status-pendente {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .status-cancelado {
            background: #fecaca;
            color: #b91c1c;
            border: 1px solid #ef4444;
        }
        
        /* Value Display */
        .value-highlight {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
        }
        
        .value-title {
            font-size: 10px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .value-amount {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin: 5px 0;
        }
        
        .value-subtitle {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }
        
        /* Transaction Details Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            background: white;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .details-table th {
            background: #3b82f6;
            color: white;
            padding: 8px 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: left;
        }
        
        .details-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9px;
            color: #374151;
        }
        
        .details-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .table-label {
            font-weight: bold;
            color: #64748b;
            width: 35%;
        }
        
        /* Authentication Section */
        .authentication-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px dashed #0284c7;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
        }
        
        .auth-title {
            font-size: 11px;
            font-weight: bold;
            color: #0369a1;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .hash-container {
            background: white;
            border: 1px solid #0284c7;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 8px;
            word-break: break-all;
            color: #0369a1;
            line-height: 1.3;
        }
        
        .validation-info {
            font-size: 8px;
            color: #0369a1;
            margin-top: 8px;
            line-height: 1.4;
        }
        
        /* Notes Section */
        .notes-section {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid #f59e0b;
            border-radius: 0 4px 4px 0;
            padding: 12px;
            margin: 12px 0;
        }
        
        .notes-title {
            font-size: 10px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .notes-content {
            font-size: 9px;
            color: #78350f;
            line-height: 1.4;
        }
        
        /* Declaration Section */
        .declaration-section {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #10b981;
            border-radius: 6px;
            padding: 12px;
            margin: 15px 0;
        }
        
        .declaration-title {
            font-size: 10px;
            font-weight: bold;
            color: #047857;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .declaration-content {
            font-size: 9px;
            color: #065f46;
            line-height: 1.4;
        }
        
        /* Footer */
        .document-footer {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        
        .footer-main {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .footer-details {
            font-size: 8px;
            opacity: 0.9;
            line-height: 1.4;
            margin-bottom: 5px;
        }
        
        .footer-system {
            font-size: 7px;
            opacity: 0.7;
            font-style: italic;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        /* QR Code Section */
        .qr-section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            margin: 12px 0;
        }
        
        .qr-title {
            font-size: 10px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: #f1f5f9;
            border: 2px dashed #94a3b8;
            margin: 8px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #64748b;
            text-align: center;
            line-height: 1.2;
        }
        
        .qr-url {
            font-size: 7px;
            color: #64748b;
            word-break: break-all;
            margin-top: 5px;
            font-family: 'Courier New', monospace;
        }
        
        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            
            .document-container {
                border: 1px solid #2563eb;
                box-shadow: none;
            }
            
            .section {
                page-break-inside: avoid;
            }
        }
        
        /* Page break utility */
        .page-break {
            page-break-before: always;
        }
        
        /* Utility classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
    </style>
</head>
<body>
    <div class="document-container">
        <!-- Header Premium -->
        <div class="document-header">
            <div class="organization-logo">
                @if(isset($configuracoes['app_logo']) && $configuracoes['app_logo'])
                    <img src="{{ public_path('storage/' . $configuracoes['app_logo']) }}" 
                         alt="Logo" 
                         style="max-height: 40px; margin-bottom: 8px;">
                @endif
            </div>
            
            <div class="organization-name">{{ $igrejaNome }}</div>
            
            @if($igrejaEndereco)
            <div class="organization-subtitle">{{ $igrejaEndereco }}</div>
            @endif
            
            <div class="organization-details">
                @if($igrejaTelefone){{ $igrejaTelefone }}@endif
                @if($igrejaEmail && $igrejaTelefone) • @endif
                @if($igrejaEmail){{ $igrejaEmail }}@endif
                @if($igrejaCNPJ)<br>CNPJ: {{ $igrejaCNPJ }}@endif
            </div>
            
            <div class="document-type-badge">
                Comprovante de Baixa Patrimonial
            </div>
        </div>

        <div class="document-content">
            <!-- Document Information -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">📄 Informações do Documento</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Número do Documento</div>
                            <div class="info-value highlight">{{ $documento->numero_documento }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Protocolo Interno</div>
                            <div class="info-value">{{ $documento->protocolo_receita }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Data de Emissão</div>
                            <div class="info-value">{{ $documento->data_emissao ? $documento->data_emissao->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Ano de Exercício</div>
                            <div class="info-value">{{ $documento->ano_exercicio }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Tipo de Baixa</div>
                            <div class="info-value">{{ $tiposDocumento[$documento->tipo_documento] ?? $documento->tipo_documento }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Status do Documento</div>
                            <div class="info-value">
                                <span class="status-badge status-{{ strtolower($documento->status) }}">
                                    {{ $statusDocumento[$documento->status] ?? $documento->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Value Section -->
            <div class="value-highlight">
                <div class="value-title">Valor da Baixa Patrimonial</div>
                <div class="value-amount">R$ {{ number_format($documento->valor_documento, 2, ',', '.') }}</div>
                @if($documento->valor_pago > 0)
                <div class="value-subtitle">
                    Valor Processado: R$ {{ number_format($documento->valor_pago, 2, ',', '.') }}
                </div>
                @endif
            </div>

            <!-- Transaction Details -->
            @if($documento->transacao)
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">🔄 Detalhes da Operação</h2>
                </div>
                <div class="section-content">
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Informação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-label">Responsável pela Operação</td>
                                <td>{{ $documento->transacao->membro->nome ?? 'Sistema Administrativo' }}</td>
                            </tr>
                            
                            @if($documento->transacao->campanha)
                            <tr>
                                <td class="table-label">Projeto/Campanha</td>
                                <td>{{ $documento->transacao->campanha->titulo }}</td>
                            </tr>
                            @endif
                            
                            <tr>
                                <td class="table-label">Tipo de Operação</td>
                                <td>{{ ucfirst($documento->transacao->tipo) }}</td>
                            </tr>
                            
                            <tr>
                                <td class="table-label">Data e Hora da Operação</td>
                                <td>{{ $documento->transacao->data->format('d/m/Y \à\s H:i') }}</td>
                            </tr>
                            
                            @if($documento->transacao->descricao)
                            <tr>
                                <td class="table-label">Descrição Detalhada</td>
                                <td>{{ Str::limit($documento->transacao->descricao, 100) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Authentication Section -->
            <div class="authentication-section">
                <div class="auth-title">🔐 Autenticação Digital</div>
                <div style="font-size: 9px; color: #0369a1; margin-bottom: 8px;">
                    Código de Integridade e Validação do Documento
                </div>
                <div class="hash-container">{{ $documento->hash_documento }}</div>
                <div class="validation-info">
                    <strong>Validação Online:</strong><br>
                    Acesse: {{ url('/validacao/baixa/' . $documento->hash_documento) }}<br>
                    Este código garante a autenticidade e integridade do documento
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-title">📱 Validação por QR Code</div>
                <div class="qr-placeholder">
                    QR CODE<br>
                    Para<br>
                    Validação
                </div>
                <div class="qr-url">{{ url('/validacao/baixa/' . $documento->hash_documento) }}</div>
            </div>

            <!-- Notes Section -->
            @if($documento->observacoes)
            <div class="notes-section">
                <div class="notes-title">📝 Observações Adicionais</div>
                <div class="notes-content">{{ $documento->observacoes }}</div>
            </div>
            @endif

            <!-- Declaration Section -->
            <div class="declaration-section">
                <div class="declaration-title">✅ Declaração para Imposto de Renda</div>
                <div class="declaration-content">
                    Este documento serve como <strong>comprovante oficial de baixa patrimonial</strong> 
                    e pode ser utilizado para fins de declaração de imposto de renda, conforme 
                    a legislação tributária vigente. O documento possui validade nacional e 
                    atende aos requisitos da Receita Federal do Brasil para comprovação de 
                    movimentação patrimonial de entidades religiosas.
                </div>
            </div>
        </div>

        <!-- Footer Premium -->
        <div class="document-footer">
            <div class="footer-main">
                Documento Oficial Gerado em {{ $dataGeracao }}
            </div>
            
            <div class="footer-details">
                Este é um comprovante oficial de baixa patrimonial da {{ $igrejaNome }}
            </div>
            
            @if($igrejaTelefone || $igrejaEmail || $igrejaEndereco)
            <div class="footer-details">
                @if($igrejaEndereco){{ $igrejaEndereco }}@endif
                @if($igrejaTelefone && $igrejaEndereco) • @endif
                @if($igrejaTelefone){{ $igrejaTelefone }}@endif
                @if($igrejaEmail && ($igrejaTelefone || $igrejaEndereco)) • @endif
                @if($igrejaEmail){{ $igrejaEmail }}@endif
            </div>
            @endif
            
            <div class="footer-system">
                Sistema CBAV - Controle de Biblioteca e Atividades Virtuais<br>
                Documento Digitalmente Protegido • Hash: {{ substr($documento->hash_documento, 0, 16) }}...
            </div>
        </div>
    </div>
</body>
</html> 