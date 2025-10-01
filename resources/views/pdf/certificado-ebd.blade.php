<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado EBD - {{ $certificado->titulo }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .certificado {
            background: white;
            border: 3px solid #2c3e50;
            border-radius: 15px;
            padding: 40px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .certificado::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23f0f0f0"/><circle cx="75" cy="75" r="1" fill="%23f0f0f0"/><circle cx="50" cy="10" r="0.5" fill="%23f0f0f0"/><circle cx="10" cy="60" r="0.5" fill="%23f0f0f0"/><circle cx="90" cy="40" r="0.5" fill="%23f0f0f0"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
            pointer-events: none;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        
        .igreja-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #2c3e50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .igreja-nome {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .igreja-info {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .certificado-titulo {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin: 40px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .certificado-conteudo {
            font-size: 18px;
            line-height: 1.8;
            text-align: center;
            margin: 40px 0;
            color: #34495e;
        }
        
        .aluno-nome {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .certificado-detalhes {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .assinaturas {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            position: relative;
            z-index: 1;
        }
        
        .assinatura {
            text-align: center;
            flex: 1;
            margin: 0 20px;
        }
        
        .linha-assinatura {
            border-top: 2px solid #2c3e50;
            margin-top: 50px;
            padding-top: 10px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .codigo-verificacao {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #95a5a6;
            position: relative;
            z-index: 1;
        }
        
        .data-emissao {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
            position: relative;
            z-index: 1;
        }
        
        .validade {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #e74c3c;
            position: relative;
            z-index: 1;
        }
        
        .carga-horaria {
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
            color: #27ae60;
            font-weight: bold;
        }
        
        .nota-final {
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
            color: #f39c12;
            font-weight: bold;
        }
        
        .tipo-certificado {
            text-align: center;
            margin: 20px 0;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(44, 62, 80, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="certificado">
        <div class="watermark">CERTIFICADO</div>
        
        <div class="header">
            <div class="igreja-logo">
                {{ substr($igrejaNome, 0, 2) }}
            </div>
            <div class="igreja-nome">{{ $igrejaNome }}</div>
            <div class="igreja-info">
                {{ $igrejaEndereco }}<br>
                {{ $igrejaTelefone }} | {{ $igrejaEmail }}
            </div>
        </div>
        
        <div class="certificado-titulo">{{ $certificado->titulo }}</div>
        
        <div class="tipo-certificado">{{ $certificado->tipo_formatado }}</div>
        
        <div class="certificado-conteudo">
            {!! nl2br(e($certificado->conteudo)) !!}
        </div>
        
        <div class="aluno-nome">{{ $certificado->aluno->membro->nome ?? 'N/A' }}</div>
        
        @if($certificado->carga_horaria)
            <div class="carga-horaria">
                Carga Horária: {{ $certificado->carga_horaria }} horas
            </div>
        @endif
        
        @if($certificado->nota_final)
            <div class="nota-final">
                Nota Final: {{ $certificado->nota_final }} pontos
            </div>
        @endif
        
        <div class="certificado-detalhes">
            <div>
                <strong>Turma:</strong> {{ $certificado->aluno->turma->nome ?? 'N/A' }}
            </div>
            <div>
                <strong>Data de Emissão:</strong> {{ $certificado->data_emissao->format('d/m/Y') }}
            </div>
        </div>
        
        <div class="assinaturas">
            @if($certificado->assinatura_coordenador)
                <div class="assinatura">
                    <div class="linha-assinatura">{{ $certificado->assinatura_coordenador }}</div>
                    <div>Coordenador EBD</div>
                </div>
            @endif
            
            @if($certificado->assinatura_pastor)
                <div class="assinatura">
                    <div class="linha-assinatura">{{ $certificado->assinatura_pastor }}</div>
                    <div>Pastor</div>
                </div>
            @endif
        </div>
        
        <div class="codigo-verificacao">
            Código de Verificação: {{ $certificado->codigo }}
        </div>
        
        <div class="data-emissao">
            Emitido em {{ $certificado->data_emissao->format('d/m/Y') }}
        </div>
        
        @if($certificado->data_validade)
            <div class="validade">
                Válido até {{ $certificado->data_validade->format('d/m/Y') }}
            </div>
        @endif
    </div>
</body>
</html> 