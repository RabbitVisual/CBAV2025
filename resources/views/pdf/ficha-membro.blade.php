<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Membro - {{ $membro->nome }}</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #2d3748;
            background-color: #fff;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            border-bottom: 4px solid #1e40af;
            padding-bottom: 25px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af, #3b82f6);
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin: 0 0 10px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header p {
            color: #4a5568;
            margin: 5px 0;
            font-size: 12px;
        }
        
        .logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 15px;
        }
        
        .document-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: 2px solid #1e40af;
            padding: 15px;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
        }
        
        .membro-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .foto-membro {
            width: 120px;
            height: 150px;
            border: 3px solid #1e40af;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 20px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
        
        .foto-placeholder {
            width: 120px;
            height: 150px;
            border: 3px solid #1e40af;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
        
        .membro-info-basic {
            flex: 1;
        }
        
        .membro-nome {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .membro-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .status-ativo {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
        }
        
        .status-inativo {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
        }
        
        .membro-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            font-size: 11px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #4a5568;
        }
        
        .info-value {
            color: #2d3748;
        }
        
        .section-title {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .dados-pessoais {
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        
        .dados-pessoais-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 1px;
        }
        
        .dados-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .dado-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .dado-label {
            font-weight: bold;
            color: #1e40af;
            font-size: 12px;
        }
        
        .dado-value {
            color: #2d3748;
            font-size: 12px;
        }
        
        .ministerios-section {
            margin-bottom: 25px;
        }
        
        .ministerio-item {
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            padding: 15px;
            margin-bottom: 12px;
            border-left: 5px solid #1e40af;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .ministerio-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        .cargo-item {
            margin-left: 20px;
            font-size: 11px;
            color: #4a5568;
            padding: 3px 0;
        }
        
        .historico-section {
            margin-bottom: 25px;
        }
        
        .historico-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .historico-table th {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .historico-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        .historico-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #4a5568;
            line-height: 1.2;
        }
        
        .assinatura {
            margin-top: 20px;
            text-align: center;
        }
        
        .assinatura-line {
            width: 200px;
            border-top: 2px solid #2d3748;
            margin: 0 auto 5px auto;
        }
        
        .assinatura-text {
            font-size: 10px;
            color: #4a5568;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .observacoes {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #f59e0b;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .observacoes-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .selo-oficial {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #1e40af;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
        }
        
        .selo-text {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .selo-subtext {
            font-size: 10px;
            color: #4a5568;
            margin-top: 5px;
        }
        
        .termo-transferencia {
            background: linear-gradient(135deg, #fef7ff, #f3e8ff);
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border: 2px solid #8b5cf6;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .termo-title {
            font-size: 16px;
            font-weight: bold;
            color: #7c3aed;
            text-align: center;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .termo-text {
            font-size: 11px;
            line-height: 1.6;
            color: #4c1d95;
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .termo-destaque {
            font-weight: bold;
            color: #7c3aed;
        }
        
        .igreja-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bae6fd;
        }
        
        .igreja-title {
            font-weight: bold;
            color: #0369a1;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 12px;
        }
        
        .igreja-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .igreja-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .igreja-label {
            font-weight: bold;
            color: #0369a1;
        }
        
        .igreja-value {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <!-- PRIMEIRA PÁGINA - CABEÇALHO E FOTO -->
    <!-- Cabeçalho -->
    <div class="header">
        @if($igrejaLogo)
            <img src="{{ public_path('storage/' . $igrejaLogo) }}" alt="Logo" class="logo">
        @endif
        <h1>{{ $igrejaNome }}</h1>
        @if($igrejaEndereco)
            <p>{{ $igrejaEndereco }}</p>
        @endif
        @if($igrejaTelefone || $igrejaEmail)
            <p>
                @if($igrejaTelefone) {{ $igrejaTelefone }} @endif
                @if($igrejaTelefone && $igrejaEmail) | @endif
                @if($igrejaEmail) {{ $igrejaEmail }} @endif
            </p>
        @endif
        @if($igrejaCNPJ)
            <p>CNPJ: {{ $igrejaCNPJ }}</p>
        @endif
    </div>

    <!-- Título do Documento -->
    <div class="document-title">
        Ficha Oficial de Membro da Igreja
    </div>

    <!-- Selo Oficial -->
    <div class="selo-oficial">
        <div class="selo-text">Documento Oficial</div>
        <div class="selo-subtext">Válido para transferência entre igrejas batistas</div>
    </div>

    <!-- Cabeçalho do Membro com Foto -->
    <div class="membro-header">
        @if($membro->foto)
            <img src="{{ public_path('storage/' . $membro->foto) }}" alt="Foto do Membro" class="foto-membro">
        @else
            <div class="foto-placeholder">
                FOTO<br>NÃO<br>DISPONÍVEL
            </div>
        @endif
        
        <div class="membro-info-basic">
            <div class="membro-nome">{{ $membro->nome }}</div>
            <div class="membro-status {{ $membro->ativo ? 'status-ativo' : 'status-inativo' }}">
                {{ $membro->ativo ? 'Membro Ativo' : 'Membro Inativo' }}
            </div>
            <div class="membro-info-grid">
                <div class="info-item">
                    <span class="info-label">Idade:</span>
                    <span class="info-value">{{ $idade ?? 'Não informado' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tempo de Membro:</span>
                    <span class="info-value">{{ $tempoMembro }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Data de Batismo:</span>
                    <span class="info-value">{{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não batizado' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Data de Ingresso:</span>
                    <span class="info-value">{{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Igreja -->
    <div class="igreja-info">
        <div class="igreja-title">Informações da Igreja</div>
        <div class="igreja-grid">
            <div class="igreja-item">
                <span class="igreja-label">Igreja:</span>
                <span class="igreja-value">{{ $igrejaNome }}</span>
            </div>
            @if($igrejaPastor)
            <div class="igreja-item">
                <span class="igreja-label">Pastor:</span>
                <span class="igreja-value">{{ $igrejaPastor }}</span>
            </div>
            @endif
            @if($igrejaEndereco)
            <div class="igreja-item">
                <span class="igreja-label">Endereço:</span>
                <span class="igreja-value">{{ $igrejaEndereco }}</span>
            </div>
            @endif
            @if($igrejaTelefone)
            <div class="igreja-item">
                <span class="igreja-label">Telefone:</span>
                <span class="igreja-value">{{ $igrejaTelefone }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- QUEBRA DE PÁGINA -->
    <div class="page-break"></div>

    <!-- SEGUNDA PÁGINA - DADOS PESSOAIS -->
    <!-- Cabeçalho da Segunda Página -->
    <div class="header">
        @if($igrejaLogo)
            <img src="{{ public_path('storage/' . $igrejaLogo) }}" alt="Logo" class="logo">
        @endif
        <h1>{{ $igrejaNome }}</h1>
        <p>Ficha de Membro - {{ $membro->nome }}</p>
    </div>

    <!-- Dados Pessoais Completos -->
    <div class="dados-pessoais">
        <div class="dados-pessoais-title">Dados Pessoais Completos</div>
        <div class="dados-grid">
            <div class="dado-item">
                <span class="dado-label">Nome Completo:</span>
                <span class="dado-value">{{ $membro->nome }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Email:</span>
                <span class="dado-value">{{ $membro->email }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Telefone:</span>
                <span class="dado-value">{{ $membro->telefone ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Data de Nascimento:</span>
                <span class="dado-value">{{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Sexo:</span>
                <span class="dado-value">{{ $membro->sexo == 'M' ? 'Masculino' : ($membro->sexo == 'F' ? 'Feminino' : 'Não informado') }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Estado Civil:</span>
                <span class="dado-value">{{ ucfirst($membro->estado_civil ?? 'Não informado') }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Profissão:</span>
                <span class="dado-value">{{ $membro->profissao ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Escolaridade:</span>
                <span class="dado-value">{{ $membro->escolaridade ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Endereço:</span>
                <span class="dado-value">{{ $membro->endereco ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Bairro:</span>
                <span class="dado-value">{{ $membro->bairro ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">Cidade/Estado:</span>
                <span class="dado-value">{{ $membro->cidade ?? 'Não informado' }}, {{ $membro->estado ?? 'Não informado' }}</span>
            </div>
            <div class="dado-item">
                <span class="dado-label">CEP:</span>
                <span class="dado-value">{{ $membro->cep ?? 'Não informado' }}</span>
            </div>
        </div>
    </div>

    <!-- QUEBRA DE PÁGINA -->
    <div class="page-break"></div>

    <!-- TERCEIRA PÁGINA - MINISTÉRIOS E TERMO -->
    <!-- Cabeçalho da Terceira Página -->
    <div class="header">
        @if($igrejaLogo)
            <img src="{{ public_path('storage/' . $igrejaLogo) }}" alt="Logo" class="logo">
        @endif
        <h1>{{ $igrejaNome }}</h1>
        <p>Ficha de Membro - {{ $membro->nome }}</p>
    </div>

    <!-- Ministérios e Cargos Ativos -->
    @if($ministeriosAtivos->count() > 0)
        <div class="ministerios-section">
            <div class="section-title">Ministérios e Cargos Ativos</div>
            @foreach($ministeriosAtivos as $ministerioNome => $cargos)
                <div class="ministerio-item">
                    <div class="ministerio-title">{{ $ministerioNome }}</div>
                    @foreach($cargos as $cargo)
                        <div class="cargo-item">• {{ $cargo->nome }} ({{ $cargo->departamento->nome }})</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

    <!-- Histórico de Cargos -->
    @if($historicoMinisterios->count() > 0)
        <div class="historico-section">
            <div class="section-title">Histórico de Ministérios e Cargos</div>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Ministério</th>
                        <th>Cargo</th>
                        <th>Departamento</th>
                        <th>Período</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historicoMinisterios as $ministerioNome => $cargos)
                        @foreach($cargos as $cargo)
                            <tr>
                                <td>{{ $ministerioNome }}</td>
                                <td>{{ $cargo->nome }}</td>
                                <td>{{ $cargo->departamento->nome }}</td>
                                <td>
                                    @if($cargo->pivot->data_inicio)
                                        {{ \Carbon\Carbon::parse($cargo->pivot->data_inicio)->format('d/m/Y') }}
                                        @if($cargo->pivot->data_fim)
                                            - {{ \Carbon\Carbon::parse($cargo->pivot->data_fim)->format('d/m/Y') }}
                                        @endif
                                    @else
                                        Período não informado
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Observações -->
    @if($membro->observacoes)
        <div class="observacoes">
            <div class="observacoes-title">Observações Especiais</div>
            <div>{{ $membro->observacoes }}</div>
        </div>
    @endif

    <!-- Termo de Transferência -->
    <div class="termo-transferencia">
        <div class="termo-title">Termo de Transferência e Recomendação</div>
        
        <div class="termo-text">
            <span class="termo-destaque">{{ $igrejaNome }}</span>, através de seu pastor e liderança, 
            <span class="termo-destaque">certifica e recomenda</span> o(a) irmão(ã) 
            <span class="termo-destaque">{{ $membro->nome }}</span> como membro em plena comunhão 
            desta igreja, tendo sido batizado(a) em <span class="termo-destaque">{{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'data não informada' }}</span> 
            e recebido(a) como membro em <span class="termo-destaque">{{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'data não informada' }}</span>.
        </div>
        
        <div class="termo-text">
            Durante seu período de membro desta igreja, o(a) irmão(ã) demonstrou 
            <span class="termo-destaque">conduta cristã exemplar</span>, participação ativa nos cultos 
            e ministérios da igreja, e fidelidade nas contribuições financeiras.
        </div>
        
        <div class="termo-text">
            <span class="termo-destaque">Recomendamos</span> este(a) irmão(ã) para recepção como membro 
            em qualquer igreja batista que siga os princípios doutrinários da Convenção Batista Brasileira, 
            conforme estabelecido no <span class="termo-destaque">Manual de Procedimentos Batistas</span>.
        </div>
        
        <div class="termo-text">
            Este documento é válido por <span class="termo-destaque">90 dias</span> a partir da data de emissão 
            e deve ser apresentado à igreja de destino para processamento da transferência de membro.
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p><strong>Documento gerado em {{ $dataGeracao }}</strong> | Este documento é oficial e contém informações confidenciais do membro da igreja.</p>
        <p>Documento válido para transferência entre igrejas batistas filiadas à Convenção Batista Brasileira.</p>
        <p>Conforme estabelecido no Manual de Procedimentos Batistas e Estatuto da CBB.</p>
    </div>

    <!-- Assinatura -->
    <div class="assinatura">
        <div class="assinatura-line"></div>
        <div class="assinatura-text">{{ $igrejaPastor ?: 'Pastor/Presidente da Igreja' }}</div>
        <div class="assinatura-text">{{ $igrejaNome }}</div>
        <div class="assinatura-text">Convenção Batista Brasileira</div>
    </div>
</body>
</html> 