<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Declaração Anual - {{ $documento->protocolo_receita }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #1f2937;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1f2937;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        
        .header h2 {
            color: #6b7280;
            font-size: 18px;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-section {
            flex: 1;
            margin: 0 10px;
        }
        
        .info-section h3 {
            color: #1f2937;
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .info-item {
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .info-label {
            font-weight: bold;
            color: #6b7280;
        }
        
        .info-value {
            color: #1f2937;
        }
        
        .church-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #3b82f6;
        }
        
        .church-info h3 {
            color: #1f2937;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .financial-details {
            margin-bottom: 30px;
        }
        
        .financial-details h3 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
        }
        
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .financial-table th,
        .financial-table td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
            font-size: 12px;
        }
        
        .financial-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1f2937;
        }
        
        .financial-table td {
            color: #374151;
        }
        
        .total-row {
            background-color: #f9fafb;
            font-weight: bold;
        }
        
        .validation-section {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #0ea5e9;
        }
        
        .validation-section h3 {
            color: #0c4a6e;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .validation-item {
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        .validation-label {
            font-weight: bold;
            color: #0c4a6e;
        }
        
        .validation-value {
            color: #0369a1;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            page-break-inside: avoid;
        }
        
        .qr-code-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        
        .qr-code-section h4 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 14px;
        }
        
        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #6b7280;
            word-break: break-all;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-validado {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-pendente {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-vencido {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>DOCUMENTO DE DECLARAÇÃO ANUAL</h1>
        <h2>Receita Federal do Brasil</h2>
        <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 12px;">
            Protocolo: {{ $documento->protocolo_receita }}
        </p>
    </div>

    <!-- Informações do Documento -->
    <div class="document-info">
        <div class="info-section">
            <h3>Informações Gerais</h3>
            <div class="info-item">
                <span class="info-label">Tipo de Documento:</span>
                <span class="info-value">{{ \App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Número do Documento:</span>
                <span class="info-value">{{ $documento->numero_documento }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Ano de Exercício:</span>
                <span class="info-value">{{ $documento->ano_exercicio }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ strtolower($documento->status) }}">
                    {{ \App\Models\DocumentoDeclaracaoAnual::STATUS[$documento->status] ?? $documento->status }}
                </span>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Datas</h3>
            <div class="info-item">
                <span class="info-label">Data de Emissão:</span>
                <span class="info-value">{{ $documento->data_emissao ? $documento->data_emissao->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Data de Vencimento:</span>
                <span class="info-value">{{ $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : 'N/A' }}</span>
            </div>
            @if($documento->validado_em)
            <div class="info-item">
                <span class="info-label">Validado em:</span>
                <span class="info-value">{{ $documento->validado_em->format('d/m/Y H:i:s') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Informações da Igreja -->
    <div class="church-info">
        <h3>Informações da Entidade Religiosa</h3>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1;">
                <div class="info-item">
                    <span class="info-label">Nome:</span>
                    <span class="info-value">{{ $documento->igreja->nome ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">CNPJ:</span>
                    <span class="info-value">{{ $documento->igreja->cnpj_formatado ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Endereço:</span>
                    <span class="info-value">{{ $documento->igreja->endereco_completo ?? 'N/A' }}</span>
                </div>
            </div>
            <div style="flex: 1;">
                <div class="info-item">
                    <span class="info-label">Telefone:</span>
                    <span class="info-value">{{ $documento->igreja->telefone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $documento->igreja->email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Pastor Responsável:</span>
                    <span class="info-value">{{ $documento->igreja->pastor_responsavel ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes Financeiros -->
    <div class="financial-details">
        <h3>Detalhamento Financeiro</h3>
        <table class="financial-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Valor Total</td>
                    <td>R$ {{ number_format($documento->valor_total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Doações</td>
                    <td>R$ {{ number_format($documento->valor_doacoes, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Dízimos</td>
                    <td>R$ {{ number_format($documento->valor_dizimos, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Outros</td>
                    <td>R$ {{ number_format($documento->valor_outros, 2, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total Geral</strong></td>
                    <td><strong>R$ {{ number_format($documento->valor_total, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Seção de Validação -->
    <div class="validation-section">
        <h3>Informações de Validação e Segurança</h3>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1;">
                <div class="validation-item">
                    <span class="validation-label">Hash de Validação:</span>
                    <div class="validation-value">{{ $documento->hash_documento }}</div>
                </div>
                <div class="validation-item">
                    <span class="validation-label">Certificado Digital:</span>
                    <div class="validation-value">{{ $documento->certificado_digital }}</div>
                </div>
                <div class="validation-item">
                    <span class="validation-label">Assinatura Digital:</span>
                    <div class="validation-value">{{ $documento->assinatura_digital }}</div>
                </div>
            </div>
            <div style="flex: 1;">
                @if($documento->validadoPor)
                <div class="validation-item">
                    <span class="validation-label">Validado por:</span>
                    <div class="validation-value">{{ $documento->validadoPor->name }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QR Code e Código de Barras -->
    <div class="qr-code-section">
        <h4>QR Code para Validação</h4>
        <div class="qr-code-image" style="text-align: center; margin: 20px 0;">
            <img src="data:image/svg+xml;base64,{{ base64_encode($documento->gerarQRCodeSVG()) }}" 
                 style="max-width: 200px; height: auto;" 
                 alt="QR Code para validação">
        </div>
        <!-- Removido o texto do QR code para manter apenas a imagem -->
        <p style="margin: 10px 0 0 0; font-size: 10px; color: #6b7280;">
            Escaneie este QR Code para validar a autenticidade do documento
        </p>
    </div>

    <!-- Código de Barras para Validação -->
    <div class="barcode-section" style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f9fafb; border-radius: 8px;">
        <h4 style="margin: 0 0 15px 0; color: #1f2937; font-size: 14px;">Código de Barras para Validação</h4>
        <div class="barcode-image" style="margin: 15px 0;">
            <img src="data:image/svg+xml;base64,{{ base64_encode($documento->gerarCodigoBarrasSVG()) }}" 
                 style="max-width: 100%; height: auto;" 
                 alt="Código de barras para validação">
        </div>
        <div class="barcode-text" style="font-family: 'Courier New', monospace; font-size: 10px; color: #6b7280; word-break: break-all; margin-top: 10px;">
            {{ $documento->codigo_barras }}
        </div>
        <p style="margin: 10px 0 0 0; font-size: 10px; color: #6b7280;">
            Utilize este código de barras para validação na Receita Federal
        </p>
    </div>

    <!-- Container para Observações e Rodapé (mantém na mesma página) -->
    <div style="page-break-inside: avoid; margin-top: 30px;">
        <!-- Observações -->
        @if($documento->observacoes)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0;">Observações</h3>
            <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border-left: 4px solid #10b981;">
                <p style="margin: 0; font-size: 12px; color: #374151; line-height: 1.5;">
                    {{ $documento->observacoes }}
                </p>
            </div>
        </div>
        @endif

            <!-- Rodapé -->
    <div class="footer">
        <p>Este documento foi gerado automaticamente pelo sistema CBAV CRM Ministerial</p>
        <p>Documento válido para declaração anual na Receita Federal do Brasil</p>
        <p>Validar online: {{ url('/validacao/declaracao-anual/' . $documento->hash_documento) }}</p>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    </div>
</body>
</html> 