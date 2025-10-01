<?php

namespace App\Services;

use App\Models\Membro;
use App\Models\Configuracao;
use Barryvdh\Snappy\Facades\SnappyPdf;

class FichaMembroService
{
    public function gerarFichaProfissional(Membro $membro)
    {
        $configuracoes = $this->obterConfiguracoes();
        $dadosMembro = $this->prepararDadosMembro($membro);
        $fotoBase64 = $this->obterFotoBase64($dadosMembro);
        
        $html = $this->gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64);
        
        // Tentar usar Snappy primeiro, se falhar usar DomPDF
        try {
            $pdf = SnappyPdf::loadHTML($html);
            $pdf->setOption('page-size', 'A4');
            $pdf->setOption('margin-top', '15mm');
            $pdf->setOption('margin-bottom', '15mm');
            $pdf->setOption('margin-left', '15mm');
            $pdf->setOption('margin-right', '15mm');
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setOption('print-media-type', true);
            $pdf->setOption('javascript-delay', 1000);
            $pdf->setOption('no-stop-slow-scripts', true);
            
            return $pdf;
        } catch (\Exception $e) {
            // Fallback para DomPDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->setOptions(new \Dompdf\Options([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]));
            $dompdf->render();
            
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="ficha-membro-' . $membro->id . '.pdf"'
            ]);
        }
    }
    
    private function obterConfiguracoes()
    {
        return [
            'nome' => Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA'),
            'endereco' => Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro'),
            'cidade' => Configuracao::get('igreja_cidade', 'São Paulo - SP'),
            'telefone' => Configuracao::get('igreja_telefone', '(11) 99999-9999'),
            'email' => Configuracao::get('igreja_email', 'contato@cbav.com'),
            'site' => Configuracao::get('igreja_site', 'www.cbav.com'),
        ];
    }
    
    private function prepararDadosMembro(Membro $membro)
    {
        $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
        $departamento = $cargoAtivo ? $cargoAtivo->departamento : null;
        $ministerio = $departamento ? $departamento->ministerio : null;
        
        return [
            'id' => $membro->id,
            'nome' => $membro->nome,
            'numeroMembro' => str_pad($membro->id, 4, '0', STR_PAD_LEFT),
            'ativo' => $membro->ativo,
            'dataNascimento' => $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado',
            'dataBatismo' => $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não informado',
            'dataMembro' => $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado',
            'profissao' => $membro->profissao ?: 'Não informado',
            'endereco' => $membro->endereco ?: 'Não informado',
            'bairro' => $membro->bairro ?: 'Não informado',
            'cidade' => $membro->cidade ?: 'Não informado',
            'estado' => $membro->estado ?: 'Não informado',
            'telefone' => $membro->telefone ?: 'Não informado',
            'email' => $membro->email ?: 'Não informado',
            'cargo' => $cargoAtivo ? $cargoAtivo->nome : 'Sem cargo',
            'departamento' => $departamento ? $departamento->nome : 'Sem departamento',
            'ministerio' => $ministerio ? $ministerio->nome : 'Sem ministério',
            'cep' => $membro->cep ?: 'Não informado',
            'dataValidade' => now()->addYears(2)->format('m/Y'),
            'fotoPath' => $membro->foto ? storage_path('app/public/' . $membro->foto) : null,
        ];
    }
    
    private function obterFotoBase64($dadosMembro)
    {
        $fotoBase64 = null;
        if ($dadosMembro['fotoPath'] && file_exists($dadosMembro['fotoPath'])) {
            $fotoBase64 = base64_encode(file_get_contents($dadosMembro['fotoPath']));
        }
        return $fotoBase64;
    }
    
    private function gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64)
    {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ficha de Membro - ' . $dadosMembro['nome'] . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4;
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
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-grid-row {
            display: table-row;
        }
        
        .info-grid-cell {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
            vertical-align: top;
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
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .photo-section {
            display: table-cell;
            width: 200px;
            vertical-align: top;
            padding-right: 30px;
        }
        
        .personal-info {
            display: table-cell;
            width: calc(100% - 200px);
            vertical-align: top;
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
                
                        .personal-info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .personal-info-row {
            display: table-row;
        }
        
        .personal-info-cell {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
            vertical-align: top;
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
                    <h1>' . $configuracoes['nome'] . '</h1>
                    <p>' . $configuracoes['endereco'] . '</p>
                    <p>' . $configuracoes['cidade'] . '</p>
                    <p>' . $configuracoes['telefone'] . ' | ' . $configuracoes['email'] . '</p>
                    <div class="subtitle">Ficha de Cadastro de Membro</div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="content">
                    <!-- Informações Principais -->
                    <div class="main-info">
                        <!-- Foto e Número -->
                        <div class="photo-section">
                            <div class="photo-container">
                                ' . ($fotoBase64 ? '<img src="data:image/jpeg;base64,' . $fotoBase64 . '" alt="Foto de ' . $dadosMembro['nome'] . '" class="photo">' : '<div class="photo-placeholder">FOTO<br>NÃO<br>DISPONÍVEL</div>') . '
                            </div>
                            <div class="member-number">Membro Nº ' . $dadosMembro['numeroMembro'] . '</div>
                            <div class="member-status ' . ($dadosMembro['ativo'] ? 'status-ativo' : 'status-inativo') . '">
                                ' . ($dadosMembro['ativo'] ? 'ATIVO' : 'INATIVO') . '
                            </div>
                        </div>

                        <!-- Informações Pessoais -->
                        <div class="personal-info">
                            <div class="personal-info-grid">
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Nome Completo</div>
                                            <div class="info-value">' . $dadosMembro['nome'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Nascimento</div>
                                            <div class="info-value">' . $dadosMembro['dataNascimento'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Batismo</div>
                                            <div class="info-value">' . $dadosMembro['dataBatismo'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Ingresso</div>
                                            <div class="info-value">' . $dadosMembro['dataMembro'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Profissão</div>
                                            <div class="info-value">' . $dadosMembro['profissao'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Telefone</div>
                                            <div class="info-value">' . $dadosMembro['telefone'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell" style="width: 100%;">
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">' . $dadosMembro['email'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="section">
                        <div class="section-header">
                            <h2>Endereço</h2>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-grid-row">
                                    <div class="info-grid-cell" style="width: 100%;">
                                        <div class="info-item">
                                            <div class="info-label">Endereço Completo</div>
                                            <div class="info-value">' . $dadosMembro['endereco'] . ', ' . $dadosMembro['bairro'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Cidade</div>
                                            <div class="info-value">' . $dadosMembro['cidade'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Estado</div>
                                            <div class="info-value">' . $dadosMembro['estado'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">CEP</div>
                                            <div class="info-value">' . $dadosMembro['cep'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações da Igreja -->
                    <div class="section">
                        <div class="section-header">
                            <h2>Informações Eclesiásticas</h2>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Cargo Atual</div>
                                            <div class="info-value">' . $dadosMembro['cargo'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Departamento</div>
                                            <div class="info-value">' . $dadosMembro['departamento'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Ministério</div>
                                            <div class="info-value">' . $dadosMembro['ministerio'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Validade</div>
                                            <div class="info-value">' . $dadosMembro['dataValidade'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de Assinatura -->
                    <div class="signature-area">
                        <div class="signature-line"></div>
                        <div class="signature-text">Assinatura do Pastor Responsável</div>
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="footer">
                    <p><strong>' . $configuracoes['nome'] . '</strong></p>
                    <p>' . $configuracoes['endereco'] . ', ' . $configuracoes['cidade'] . '</p>
                    <p>Telefone: ' . $configuracoes['telefone'] . ' | Email: ' . $configuracoes['email'] . '</p>
                    <p>Documento gerado em: ' . now()->format('d/m/Y H:i:s') . '</p>
                </div>
            </div>
        </body>
        </html>';
    }
} 