<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Membro - {{ $membro->nome }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: A4;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .page-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Cabeçalho */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #1e40af 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header p {
            font-size: 14px;
            margin: 2px 0;
            opacity: 0.9;
        }
        
        .header .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
        
        /* Conteúdo Principal */
        .content {
            padding: 30px;
        }
        
        /* Seção de Informações Pessoais */
        .section {
            margin-bottom: 30px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .section-header {
            background: #f8fafc;
            padding: 15px 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .section-header h2 {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-content {
            padding: 20px;
        }
        
        /* Grid de Informações */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
            padding: 8px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            min-height: 20px;
        }
        
        .info-full {
            grid-column: 1 / -1;
        }
        
        /* Foto e Informações Principais */
        .main-info {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 30px;
            align-items: start;
            margin-bottom: 30px;
        }
        
        .photo-section {
            text-align: center;
        }
        
        .photo-container {
            width: 180px;
            height: 220px;
            margin: 0 auto 15px auto;
            border: 3px solid #1e40af;
            border-radius: 8px;
            overflow: hidden;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            color: #6b7280;
            text-align: center;
            line-height: 1.3;
        }
        
        .member-number {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .member-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-ativo {
            background: #10b981;
            color: white;
        }
        
        .status-inativo {
            background: #6b7280;
            color: white;
        }
        
        .personal-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        /* Informações da Igreja */
        .church-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }
        
        .church-info h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .church-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        /* Rodapé */
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 2px solid #e5e7eb;
            margin-top: 30px;
        }
        
        .footer p {
            font-size: 12px;
            color: #6b7280;
            margin: 5px 0;
        }
        
        .signature-area {
            margin-top: 40px;
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            height: 2px;
            background: #d1d5db;
            margin: 0 auto 10px auto;
        }
        
        .signature-text {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        /* Responsividade */
        @media print {
            body {
                padding: 0;
            }
            
            .page-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .header {
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>{{ \App\Models\Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA') }}</h1>
            <p>{{ \App\Models\Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro') }}</p>
            <p>{{ \App\Models\Configuracao::get('igreja_cidade', 'São Paulo - SP') }}</p>
            <p>{{ \App\Models\Configuracao::get('igreja_telefone', '(11) 99999-9999') }} | {{ \App\Models\Configuracao::get('igreja_email', 'contato@cbav.com') }}</p>
            <div class="subtitle">Ficha de Cadastro de Membro</div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="content">
            <!-- Informações Principais -->
            <div class="main-info">
                <!-- Foto e Número -->
                <div class="photo-section">
                    <div class="photo-container">
                        @if($membro->foto)
                            <img src="{{ asset('storage/' . $membro->foto) }}" 
                                 alt="Foto de {{ $membro->nome }}" 
                                 class="photo">
                        @else
                            <div class="photo-placeholder">FOTO<br>NÃO<br>DISPONÍVEL</div>
                        @endif
                    </div>
                    <div class="member-number">Membro Nº {{ str_pad($membro->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div class="member-status {{ $membro->ativo ? 'status-ativo' : 'status-inativo' }}">
                        {{ $membro->ativo ? 'ATIVO' : 'INATIVO' }}
                    </div>
                </div>

                <!-- Informações Pessoais -->
                <div class="personal-info">
                    <div class="info-item">
                        <div class="info-label">{{ __('Nome Completo') }}</div>
                        <div class="info-value">{{ $membro->nome }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Data de Nascimento') }}</div>
                        <div class="info-value">{{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : __('Não informado') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Data de Batismo') }}</div>
                        <div class="info-value">{{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : __('Não informado') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Data de Ingresso') }}</div>
                        <div class="info-value">{{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : __('Não informado') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Profissão') }}</div>
                        <div class="info-value">{{ $membro->profissao ?: __('Não informado') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Telefone') }}</div>
                        <div class="info-value">{{ $membro->telefone ?: __('Não informado') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Email') }}</div>
                        <div class="info-value">{{ $membro->email ?: __('Não informado') }}</div>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="section">
                <div class="section-header">
                    <h2>{{ __('Endereço') }}</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item info-full">
                            <div class="info-label">{{ __('Endereço Completo') }}</div>
                            <div class="info-value">{{ $membro->endereco ?: __('Não informado') }}, {{ $membro->bairro ?: __('Não informado') }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('Cidade') }}</div>
                            <div class="info-value">{{ $membro->cidade ?: __('Não informado') }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('Estado') }}</div>
                            <div class="info-value">{{ $membro->estado ?: __('Não informado') }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('CEP') }}</div>
                            <div class="info-value">{{ $membro->cep ?: __('Não informado') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações da Igreja -->
            <div class="section">
                <div class="section-header">
                    <h2>{{ __('Informações Eclesiásticas') }}</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">{{ __('Cargo Atual') }}</div>
                            <div class="info-value">
                                @php
                                    $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
                                @endphp
                                @if($cargoAtivo)
                                    {{ $cargoAtivo->nome }}
                                @else
                                    {{ __('Sem cargo') }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('Departamento') }}</div>
                            <div class="info-value">
                                @if($cargoAtivo && $cargoAtivo->departamento)
                                    {{ $cargoAtivo->departamento->nome }}
                                @else
                                    {{ __('Sem departamento') }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('Ministério') }}</div>
                            <div class="info-value">
                                @if($cargoAtivo && $cargoAtivo->departamento && $cargoAtivo->departamento->ministerio)
                                    {{ $cargoAtivo->departamento->ministerio->nome }}
                                @else
                                    {{ __('Sem ministério') }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">{{ __('Data de Validade') }}</div>
                            <div class="info-value">{{ \Carbon\Carbon::now()->addYears(2)->format('m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área de Assinatura -->
            <div class="signature-area">
                <div class="signature-line"></div>
                <div class="signature-text">{{ __('Assinatura do Pastor Responsável') }}</div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <p><strong>{{ \App\Models\Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA') }}</strong></p>
            <p>{{ \App\Models\Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro') }}, {{ \App\Models\Configuracao::get('igreja_cidade', 'São Paulo - SP') }}</p>
            <p>{{ __('Telefone') }}: {{ \App\Models\Configuracao::get('igreja_telefone', '(11) 99999-9999') }} | {{ __('Email') }}: {{ \App\Models\Configuracao::get('igreja_email', 'contato@cbav.com') }}</p>
            <p>{{ __('Documento gerado em') }}: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto-print quando a página carregar
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 