<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isRecordeGlobal ? '🏆 Novo Recorde Global!' : '🎉 Novo Recorde Pessoal!' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .recorde-badge {
            display: inline-block;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 30px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }
        
        .user-greeting {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .recorde-details {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .recorde-value {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            text-align: center;
            margin: 15px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }
        
        .stat-item {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        
        .ranking-info {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }
        
        .ranking-position {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .next-challenge {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .next-challenge h3 {
            color: #92400e;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .next-challenge p {
            color: #92400e;
            margin: 0;
            font-size: 14px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 25px 0;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
        }
        
        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            margin: 5px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $isRecordeGlobal ? '🏆 Novo Recorde Global!' : '🎉 Novo Recorde Pessoal!' }}</h1>
            <p>Sistema CBAV - Quiz Bíblico</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="user-greeting">
                Olá, {{ $user->name }}!
            </div>
            
            <!-- Recorde Badge -->
            <div class="recorde-badge">
                {{ $isRecordeGlobal ? '🏆 RECORDE GLOBAL' : '🎉 RECORDE PESSOAL' }}
            </div>
            
            <!-- Mensagem -->
            <p style="font-size: 16px; color: #374151; margin-bottom: 25px;">
                @if($isRecordeGlobal)
                    Parabéns! Você estabeleceu um <strong>novo recorde global</strong> no Quiz Bíblico!
                @else
                    Parabéns! Você estabeleceu um <strong>novo recorde pessoal</strong> no Quiz Bíblico!
                @endif
            </p>
            
            <!-- Detalhes do Recorde -->
            <div class="recorde-details">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; text-align: center;">
                    {{ $tipoRecorde }}
                </h3>
                <div class="recorde-value">
                    {{ $valorRecorde }}
                </div>
            </div>
            
            <!-- Estatísticas da Sessão -->
            <h3 style="color: #1f2937; margin: 25px 0 15px 0;">📊 Detalhes da Sessão</h3>
            <div class="stats-grid">
                @foreach($detalhesSessao as $label => $value)
                <div class="stat-item">
                    <div class="stat-label">{{ $label }}</div>
                    <div class="stat-value">{{ $value }}</div>
                </div>
                @endforeach
            </div>
            
            <!-- Ranking (se aplicável) -->
            @if($rankingPosition)
            <div class="ranking-info">
                <div class="ranking-position">#{{ $rankingPosition }}</div>
                <div>Posição no Ranking Global</div>
            </div>
            @endif
            
            <!-- Próximo Desafio -->
            <div class="next-challenge">
                <h3>🎯 Próximo Desafio</h3>
                <p>{{ $proximoDesafio['descricao'] }}</p>
            </div>
            
            <!-- Call to Action -->
            <div style="text-align: center;">
                <a href="{{ $proximoDesafio['url'] }}" class="cta-button">
                    🎮 Jogar Novamente
                </a>
            </div>
            
            <!-- Mensagem motivacional -->
            <div style="background-color: #f0f9ff; border-left: 4px solid #2563eb; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                <p style="margin: 0; color: #1e40af; font-style: italic;">
                    "Continue estudando a Palavra de Deus e testando seus conhecimentos. 
                    Cada sessão é uma oportunidade de crescimento espiritual!"
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="logo">CBAV</div>
            <p>Igreja Batista - Sistema de Gestão Ministerial</p>
            <p>Este e-mail foi enviado automaticamente pelo sistema.</p>
            <p style="font-size: 12px; color: #94a3b8;">
                © {{ date('Y') }} CBAV. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html> 