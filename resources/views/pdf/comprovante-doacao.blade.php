<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Transação</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333333;
            line-height: 1.3;
            font-size: 11px;
        }
        
        .page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }
        
        /* Cabeçalho */
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 20px 30px;
            text-align: center;
            position: relative;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .subtitle {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        /* Conteúdo Principal */
        .content {
            padding: 25px 30px;
            position: relative;
        }
        
        /* Número do Comprovante */
        .comprovante-number {
            position: absolute;
            top: 15px;
            right: 30px;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: bold;
        }
        
        .comprovante-number span {
            color: #3b82f6;
        }
        
        /* Seções de Informações */
        .section {
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: bold;
            color: #374151;
            font-size: 13px;
            display: flex;
            align-items: center;
        }
        
        .section-header i {
            margin-right: 8px;
            color: #3b82f6;
        }
        
        .section-content {
            padding: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 11px;
        }
        
        .info-value {
            font-weight: 600;
            color: #111827;
            font-size: 11px;
            text-align: right;
        }
        
        .amount {
            font-size: 22px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 6px;
            border: 2px solid #bbf7d0;
            margin: 15px 0;
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #bbf7d0;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        /* Código de Verificação */
        .verification-code {
            background: #f8f9fa;
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        
        .verification-code h4 {
            margin: 0 0 8px 0;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
        }
        
        .code {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 2px;
        }
        
        .verification-url {
            font-size: 9px;
            color: #6b7280;
            margin-top: 8px;
        }
        
        /* Rodapé */
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 2px solid #3b82f6;
            margin-top: 25px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .footer-section h4 {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
        }
        
        .footer-section p {
            margin: 3px 0;
            color: #6b7280;
            font-size: 9px;
        }
        
        .footer-bottom {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            color: #6b7280;
            font-size: 9px;
        }
        
        /* Assinatura */
        .signature {
            margin-top: 25px;
            text-align: center;
        }
        
        .signature-line {
            width: 150px;
            height: 1px;
            background: #3b82f6;
            margin: 8px auto;
            border-radius: 1px;
        }
        
        .signature-text {
            font-size: 9px;
            color: #6b7280;
            font-style: italic;
        }
        
        /* Elementos Decorativos */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(59, 130, 246, 0.02);
            font-weight: bold;
            pointer-events: none;
            z-index: -1;
        }
        
        .corner-decoration {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid #3b82f6;
        }
        
        .corner-decoration.top-left {
            top: 15px;
            left: 15px;
            border-right: none;
            border-bottom: none;
        }
        
        .corner-decoration.top-right {
            top: 15px;
            right: 15px;
            border-left: none;
            border-bottom: none;
        }
        
        .corner-decoration.bottom-left {
            bottom: 15px;
            left: 15px;
            border-right: none;
            border-top: none;
        }
        
        .corner-decoration.bottom-right {
            bottom: 15px;
            right: 15px;
            border-left: none;
            border-top: none;
        }
        
        /* Responsividade */
        @media print {
            .page {
                width: 210mm;
                height: 297mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Decorações de Canto -->
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration top-right"></div>
        <div class="corner-decoration bottom-left"></div>
        <div class="corner-decoration bottom-right"></div>
        
        <!-- Marca d'água -->
        <div class="watermark">{{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</div>
        
        <!-- Cabeçalho -->
        <div class="header">
            <div class="header-content">
                <div class="logo">{{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</div>
                <div class="title">Comprovante de Transação</div>
                <div class="subtitle">{{ \App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial') }}</div>
            </div>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="content">
            <!-- Número do Comprovante -->
            <div class="comprovante-number">
                Comprovante #<span>{{ str_pad($transacao->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <!-- Informações da Transação -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-receipt"></i>
                    Informações da Transação
                </div>
                <div class="section-content">
                    <div class="amount">
                        {{ $transacao->tipo == 'entrada' ? '+' : '-' }}R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">ID da Transação:</span>
                            <span class="info-value">#{{ $transacao->id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Data da Transação:</span>
                            <span class="info-value">{{ $transacao->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tipo:</span>
                            <span class="info-value">{{ ucfirst($transacao->tipo) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="status status-{{ $transacao->status }}">
                                    {{ ucfirst($transacao->status) }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Descrição:</span>
                            <span class="info-value">{{ $transacao->descricao }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Método de Pagamento:</span>
                            <span class="info-value">{{ ucfirst($transacao->dados_extras['gateway'] ?? 'N/A') }}</span>
                        </div>
                        @if($transacao->campanha)
                        <div class="info-item">
                            <span class="info-label">Campanha:</span>
                            <span class="info-value">{{ $transacao->campanha->titulo }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Categoria:</span>
                            <span class="info-value">{{ ucfirst($transacao->categoria ?? 'Não definida') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informações do Doador -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    Informações do Doador
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nome:</span>
                            <span class="info-value">{{ $transacao->membro->user->name ?? 'Doador Anônimo' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $transacao->membro->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tipo de Doador:</span>
                            <span class="info-value">{{ ucfirst($transacao->dados_extras['tipo_doador'] ?? 'Membro') }}</span>
                        </div>
                        @if($transacao->membro)
                        <div class="info-item">
                            <span class="info-label">Membro ID:</span>
                            <span class="info-value">#{{ $transacao->membro->id }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Código de Verificação -->
            <div class="verification-code">
                <h4>Código de Verificação</h4>
                <div class="code">{{ strtoupper(substr(md5($transacao->id . $transacao->created_at), 0, 8)) }}</div>
                <div class="verification-url">
                    Verifique em: {{ url('/public/validacao/comprovante') }}
                </div>
            </div>
            
            <!-- Assinatura -->
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-text">Assinatura Digital - {{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</div>
            </div>
        </div>
        
        <!-- Rodapé -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Informações de Contato</h4>
                    @if(\App\Models\Configuracao::get('contact_email'))
                    <p><i class="fas fa-envelope"></i> {{ \App\Models\Configuracao::get('contact_email') }}</p>
                    @endif
                    @if(\App\Models\Configuracao::get('contact_phone'))
                    <p><i class="fas fa-phone"></i> {{ \App\Models\Configuracao::get('contact_phone') }}</p>
                    @endif
                    @if(\App\Models\Configuracao::get('address'))
                    <p><i class="fas fa-map-marker-alt"></i> {{ \App\Models\Configuracao::get('address') }}</p>
                    @endif
                </div>
                
                <div class="footer-section">
                    <h4>Informações do Sistema</h4>
                    <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
                    <p>Sistema: {{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</p>
                    <p>Versão: 1.0</p>
                </div>
                
                <div class="footer-section">
                    <h4>Observações</h4>
                    <p>Este documento é um comprovante oficial</p>
                    <p>Mantenha em local seguro</p>
                    <p>Válido para fins fiscais</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Configuracao::get('app_name', 'CBAV') }} - {{ \App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial') }}</p>
                <p>Este documento foi gerado automaticamente pelo sistema e não requer assinatura manual.</p>
            </div>
        </div>
    </div>
</body>
</html> 