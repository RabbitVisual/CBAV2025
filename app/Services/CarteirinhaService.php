<?php

namespace App\Services;

use App\Models\Membro;
use App\Models\Configuracao;
use Barryvdh\Snappy\Facades\SnappyPdf;

class CarteirinhaService
{
    public function gerarCarteirinhaProfissional(Membro $membro)
    {
        $configuracoes = $this->obterConfiguracoes();
        $dadosMembro = $this->prepararDadosMembro($membro);
        $fotoBase64 = $this->obterFotoBase64($dadosMembro);
        
        $html = $this->gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64);
        
        // Usar Snappy para melhor suporte a CSS moderno
        $pdf = SnappyPdf::loadHTML($html);
        $pdf->setOption('page-size', 'A4');
        $pdf->setOption('margin-top', '10mm');
        $pdf->setOption('margin-bottom', '10mm');
        $pdf->setOption('margin-left', '10mm');
        $pdf->setOption('margin-right', '10mm');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('print-media-type', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('no-stop-slow-scripts', true);
        
        return $pdf;
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
    
    private function obterTemplateSVG($tipo)
    {
        $caminho = storage_path('app/public/template/' . $tipo . '.svg');
        if (file_exists($caminho)) {
            return file_get_contents($caminho);
        }
        return '';
    }
    
    private function gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64)
    {
        $templateFrente = $this->obterTemplateSVG('frente');
        $templateFundo = $this->obterTemplateSVG('fundo');
        
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Carteirinha de Membro - ' . $dadosMembro['nome'] . '</title>
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
                    font-size: 8px;
                    line-height: 1.2;
                }
                
                .page-container {
                    width: 210mm;
                    height: 297mm;
                    position: relative;
                    margin: 0 auto;
                }
                
                /* CRACHÁ - Tamanho padrão 86mm x 54mm */
                .cracha {
                    width: 86mm;
                    height: 54mm;
                    position: relative;
                    margin: 0 auto 20px auto;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                }
                
                /* FRENTE DO CRACHÁ - Usando template SVG */
                .frente {
                    width: 100%;
                    height: 100%;
                    position: relative;
                    border-radius: 8px;
                    overflow: hidden;
                }
                
                .frente svg {
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    top: 0;
                    left: 0;
                }
                
                /* Informações sobrepostas na frente */
                .info-frente {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    padding: 8px;
                    color: white;
                    font-weight: bold;
                }
                
                .numero-membro {
                    position: absolute;
                    top: 8px;
                    right: 8px;
                    background: rgba(255,255,255,0.2);
                    padding: 3px 6px;
                    border-radius: 10px;
                    font-size: 8px;
                    font-weight: bold;
                    backdrop-filter: blur(5px);
                    border: 1px solid rgba(255,255,255,0.3);
                }
                
                .status-ativo {
                    position: absolute;
                    bottom: 8px;
                    left: 8px;
                    background: #10b981;
                    padding: 2px 6px;
                    border-radius: 10px;
                    font-size: 6px;
                    font-weight: bold;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }
                
                .status-inativo {
                    position: absolute;
                    bottom: 8px;
                    left: 8px;
                    background: #6b7280;
                    padding: 2px 6px;
                    border-radius: 10px;
                    font-size: 6px;
                    font-weight: bold;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }
                
                .data-validade {
                    position: absolute;
                    bottom: 8px;
                    right: 8px;
                    background: rgba(255,255,255,0.2);
                    padding: 2px 6px;
                    border-radius: 10px;
                    font-size: 6px;
                    backdrop-filter: blur(5px);
                    border: 1px solid rgba(255,255,255,0.3);
                }
                
                .foto-container {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 5;
                }
                
                .foto-membro {
                    width: 24mm;
                    height: 28mm;
                    object-fit: cover;
                    border: 2px solid white;
                    border-radius: 4px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                }
                
                .foto-placeholder {
                    width: 24mm;
                    height: 28mm;
                    background: rgba(255,255,255,0.2);
                    border: 2px solid white;
                    border-radius: 4px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 6px;
                    font-weight: bold;
                    text-align: center;
                    line-height: 1.2;
                    color: white;
                }
                
                .nome-membro {
                    position: absolute;
                    bottom: 25px;
                    left: 8px;
                    right: 8px;
                    text-align: center;
                    font-size: 9px;
                    font-weight: bold;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
                    color: white;
                }
                
                /* VERSO DO CRACHÁ - Usando template SVG */
                .verso {
                    width: 100%;
                    height: 100%;
                    position: relative;
                    border-radius: 8px;
                    overflow: hidden;
                }
                
                .verso svg {
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    top: 0;
                    left: 0;
                }
                
                /* Informações sobrepostas no verso */
                .info-verso {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    padding: 8px;
                    color: #1f2937;
                }
                
                .header-verso {
                    text-align: center;
                    margin-bottom: 8px;
                    padding-bottom: 6px;
                    border-bottom: 1px solid rgba(255,255,255,0.3);
                }
                
                .titulo-verso {
                    font-size: 9px;
                    font-weight: bold;
                    color: white;
                    margin-bottom: 2px;
                    text-transform: uppercase;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
                }
                
                .subtitulo-verso {
                    font-size: 6px;
                    color: rgba(255,255,255,0.9);
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 6px;
                    margin-bottom: 8px;
                }
                
                .info-item {
                    margin-bottom: 4px;
                }
                
                .info-label {
                    font-size: 5px;
                    font-weight: bold;
                    color: rgba(255,255,255,0.8);
                    text-transform: uppercase;
                    margin-bottom: 1px;
                }
                
                .info-value {
                    font-size: 6px;
                    color: white;
                    font-weight: 500;
                    line-height: 1.1;
                    text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
                }
                
                .info-full {
                    grid-column: 1 / -1;
                }
                
                .assinatura-area {
                    margin-top: 8px;
                    text-align: center;
                }
                
                .assinatura-line {
                    width: 40mm;
                    height: 1px;
                    background: rgba(255,255,255,0.5);
                    margin: 0 auto 2px auto;
                }
                
                .assinatura-text {
                    font-size: 5px;
                    color: rgba(255,255,255,0.8);
                    text-transform: uppercase;
                }
                
                /* LAYOUT DA PÁGINA A4 */
                .cracha-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                    gap: 20px;
                }
                
                .cracha-label {
                    font-size: 12px;
                    font-weight: bold;
                    color: #1e40af;
                    text-align: center;
                    margin-bottom: 10px;
                    text-transform: uppercase;
                }
            </style>
        </head>
        <body>
            <div class="page-container">
                <div class="cracha-container">
                    <!-- Frente do Crachá -->
                    <div class="cracha-label">FRENTE</div>
                    <div class="cracha">
                        <div class="frente">
                            ' . $templateFrente . '
                            
                            <!-- Informações sobrepostas -->
                            <div class="info-frente">
                                <!-- Número do Membro -->
                                <div class="numero-membro">
                                    Nº ' . $dadosMembro['numeroMembro'] . '
                                </div>
                                
                                <!-- Status -->
                                <div class="' . ($dadosMembro['ativo'] ? 'status-ativo' : 'status-inativo') . '">
                                    ' . ($dadosMembro['ativo'] ? 'ATIVO' : 'INATIVO') . '
                                </div>
                                
                                <!-- Data de Validade -->
                                <div class="data-validade">
                                    Válida até: ' . $dadosMembro['dataValidade'] . '
                                </div>
                                
                                <!-- Foto do Membro -->
                                <div class="foto-container">
                                    ' . ($fotoBase64 ? '<img src="data:image/jpeg;base64,' . $fotoBase64 . '" alt="Foto de ' . $dadosMembro['nome'] . '" class="foto-membro">' : '<div class="foto-placeholder">FOTO<br>NÃO<br>DISPONÍVEL</div>') . '
                                </div>
                                
                                <!-- Nome do Membro -->
                                <div class="nome-membro">' . $dadosMembro['nome'] . '</div>
                            </div>
                        </div>
                    </div>

                    <!-- Verso do Crachá -->
                    <div class="cracha-label">VERSO</div>
                    <div class="cracha">
                        <div class="verso">
                            ' . $templateFundo . '
                            
                            <!-- Informações sobrepostas -->
                            <div class="info-verso">
                                <!-- Cabeçalho -->
                                <div class="header-verso">
                                    <h2 class="titulo-verso">' . $configuracoes['nome'] . '</h2>
                                    <p class="subtitulo-verso">Informações do Membro</p>
                                </div>
                                
                                <!-- Informações -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Data de Nascimento</div>
                                        <div class="info-value">' . $dadosMembro['dataNascimento'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Data de Batismo</div>
                                        <div class="info-value">' . $dadosMembro['dataBatismo'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Data de Membro</div>
                                        <div class="info-value">' . $dadosMembro['dataMembro'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Profissão</div>
                                        <div class="info-value">' . $dadosMembro['profissao'] . '</div>
                                    </div>
                                    
                                    <div class="info-item info-full">
                                        <div class="info-label">Endereço</div>
                                        <div class="info-value">' . $dadosMembro['endereco'] . ', ' . $dadosMembro['bairro'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Cidade/Estado</div>
                                        <div class="info-value">' . $dadosMembro['cidade'] . ' - ' . $dadosMembro['estado'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Telefone</div>
                                        <div class="info-value">' . $dadosMembro['telefone'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">' . $dadosMembro['email'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Cargo</div>
                                        <div class="info-value">' . $dadosMembro['cargo'] . '</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Departamento</div>
                                        <div class="info-value">' . $dadosMembro['departamento'] . '</div>
                                    </div>
                                    
                                    <div class="info-item info-full">
                                        <div class="info-label">Ministério</div>
                                        <div class="info-value">' . $dadosMembro['ministerio'] . '</div>
                                    </div>
                                </div>
                                
                                <!-- Área de Assinatura -->
                                <div class="assinatura-area">
                                    <div class="assinatura-line"></div>
                                    <div class="assinatura-text">Assinatura do Pastor</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
} 