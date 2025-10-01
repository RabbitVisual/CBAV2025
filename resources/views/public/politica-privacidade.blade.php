@extends('layouts.public')

@section('title', 'Política de Privacidade - ' . \App\Models\Configuracao::get('app_name', 'CBAV CRM'))

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Política de Privacidade</h1>
            <p class="text-lg text-gray-600">Última atualização: {{ date('d/m/Y') }}</p>
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                Conforme a LGPD (Lei 13.709/2018)
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="bg-white rounded-lg shadow-lg p-8 space-y-8">
            <!-- Introdução -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Introdução</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    A {{ \App\Models\Configuracao::get('igreja_nome', 'Congregação Batista Avenida') }} está comprometida em proteger a privacidade e os dados pessoais de todos os usuários do {{ \App\Models\Configuracao::get('app_name', 'CBAV CRM') }}.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Esta Política de Privacidade descreve como coletamos, usamos, armazenamos e protegemos suas informações pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei 13.709/2018) e outras legislações aplicáveis.
                </p>
            </section>

            <!-- Definições -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Definições Importantes</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div>
                            <strong class="text-gray-900">Dados Pessoais:</strong>
                            <span class="text-gray-700 text-sm block">Informações que identificam ou podem identificar uma pessoa natural.</span>
                        </div>
                        <div>
                            <strong class="text-gray-900">Titular:</strong>
                            <span class="text-gray-700 text-sm block">Pessoa natural a quem se referem os dados pessoais.</span>
                        </div>
                        <div>
                            <strong class="text-gray-900">Controlador:</strong>
                            <span class="text-gray-700 text-sm block">{{ \App\Models\Configuracao::get('igreja_nome', 'Congregação Batista Avenida') }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <strong class="text-gray-900">Tratamento:</strong>
                            <span class="text-gray-700 text-sm block">Qualquer operação com dados pessoais (coleta, armazenamento, uso, etc.).</span>
                        </div>
                        <div>
                            <strong class="text-gray-900">Consentimento:</strong>
                            <span class="text-gray-700 text-sm block">Autorização livre, informada e inequívoca do titular.</span>
                        </div>
                        <div>
                            <strong class="text-gray-900">Anonimização:</strong>
                            <span class="text-gray-700 text-sm block">Processo que torna impossível a identificação do titular.</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Dados Coletados -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Dados Pessoais Coletados</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">3.1 Dados de Identificação</h3>
                        <ul class="list-disc pl-6 space-y-1 text-gray-700">
                            <li>Nome completo</li>
                            <li>Email</li>
                            <li>Telefone</li>
                            <li>Endereço</li>
                            <li>Data de nascimento</li>
                            <li>Documento de identidade (CPF/RG)</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">3.2 Dados Ministeriais</h3>
                        <ul class="list-disc pl-6 space-y-1 text-gray-700">
                            <li>Cargo na igreja</li>
                            <li>Ministérios participantes</li>
                            <li>Histórico de participação</li>
                            <li>Certificações e cursos</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">3.3 Dados Financeiros</h3>
                        <ul class="list-disc pl-6 space-y-1 text-gray-700">
                            <li>Histórico de doações</li>
                            <li>Informações de pagamento (quando aplicável)</li>
                            <li>Dados para emissão de recibos</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">3.4 Dados Técnicos</h3>
                        <ul class="list-disc pl-6 space-y-1 text-gray-700">
                            <li>Endereço IP</li>
                            <li>Dados de navegação</li>
                            <li>Logs de acesso</li>
                            <li>Informações do dispositivo</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Finalidades -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Finalidades do Tratamento</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-3">Gestão Ministerial</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Cadastro e controle de membros</li>
                            <li>• Organização de ministérios</li>
                            <li>• Agendamento de eventos</li>
                            <li>• Comunicação interna</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-green-900 mb-3">Administração</h3>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• Controle financeiro</li>
                            <li>• Emissão de relatórios</li>
                            <li>• Gestão de documentos</li>
                            <li>• Auditoria e compliance</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-900 mb-3">Comunicação</h3>
                        <ul class="text-sm text-purple-800 space-y-1">
                            <li>• Envio de notificações</li>
                            <li>• Lembretes de eventos</li>
                            <li>• Comunicados oficiais</li>
                            <li>• Suporte técnico</li>
                        </ul>
                    </div>
                    
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-orange-900 mb-3">Segurança</h3>
                        <ul class="text-sm text-orange-800 space-y-1">
                            <li>• Autenticação de usuários</li>
                            <li>• Prevenção de fraudes</li>
                            <li>• Monitoramento de segurança</li>
                            <li>• Backup de dados</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Base Legal -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Base Legal para Tratamento</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h4 class="font-medium text-gray-900">Consentimento (Art. 7º, I da LGPD)</h4>
                        <p class="text-gray-700 text-sm">Para envio de comunicações não essenciais e personalização de experiência.</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <h4 class="font-medium text-gray-900">Legítimo Interesse (Art. 7º, IX da LGPD)</h4>
                        <p class="text-gray-700 text-sm">Para gestão ministerial, segurança do sistema e melhoria dos serviços.</p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h4 class="font-medium text-gray-900">Execução de Contrato (Art. 7º, V da LGPD)</h4>
                        <p class="text-gray-700 text-sm">Para cumprimento de obrigações relacionadas aos serviços prestados.</p>
                    </div>
                </div>
            </section>

            <!-- Compartilhamento -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Compartilhamento de Dados</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Seus dados pessoais não são vendidos, alugados ou cedidos a terceiros para fins comerciais. O compartilhamento ocorre apenas nas seguintes situações:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-gray-700">
                    <li><strong>Prestadores de serviço:</strong> Empresas que auxiliam na operação do sistema (hospedagem, backup, etc.)</li>
                    <li><strong>Obrigações legais:</strong> Quando exigido por lei ou ordem judicial</li>
                    <li><strong>Proteção de direitos:</strong> Para proteger nossos direitos, propriedade ou segurança</li>
                    <li><strong>Autoridades competentes:</strong> Em caso de investigações ou processos legais</li>
                </ul>
            </section>

            <!-- Segurança -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Segurança dos Dados</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-red-50 p-4 rounded-lg text-center">
                        <i class="fas fa-shield-alt text-3xl text-red-600 mb-2"></i>
                        <h4 class="font-medium text-red-900">Criptografia</h4>
                        <p class="text-sm text-red-700">Dados protegidos com criptografia avançada</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <i class="fas fa-lock text-3xl text-blue-600 mb-2"></i>
                        <h4 class="font-medium text-blue-900">Acesso Restrito</h4>
                        <p class="text-sm text-blue-700">Controle rigoroso de acesso aos dados</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <i class="fas fa-server text-3xl text-green-600 mb-2"></i>
                        <h4 class="font-medium text-green-900">Backup Seguro</h4>
                        <p class="text-sm text-green-700">Backups regulares e seguros</p>
                    </div>
                </div>
            </section>

            <!-- Direitos do Titular -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Seus Direitos (LGPD)</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-eye text-blue-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Acesso</strong>
                                <p class="text-gray-700 text-sm">Consultar seus dados pessoais</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-edit text-green-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Correção</strong>
                                <p class="text-gray-700 text-sm">Corrigir dados incompletos ou inexatos</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-trash text-red-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Eliminação</strong>
                                <p class="text-gray-700 text-sm">Excluir dados desnecessários</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-download text-purple-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Portabilidade</strong>
                                <p class="text-gray-700 text-sm">Transferir dados para outro fornecedor</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-ban text-orange-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Oposição</strong>
                                <p class="text-gray-700 text-sm">Opor-se ao tratamento</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-times-circle text-gray-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Revogação</strong>
                                <p class="text-gray-700 text-sm">Retirar consentimento</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-indigo-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Informação</strong>
                                <p class="text-gray-700 text-sm">Saber com quem compartilhamos</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-gavel text-yellow-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Revisão</strong>
                                <p class="text-gray-700 text-sm">Revisar decisões automatizadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Retenção -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Tempo de Retenção</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Mantemos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades descritas, observando:
                </p>
                <ul class="list-disc pl-6 space-y-1 text-gray-700">
                    <li>Período de participação ativa na igreja</li>
                    <li>Obrigações legais e contratuais</li>
                    <li>Necessidades administrativas legítimas</li>
                    <li>Prazos de prescrição legal</li>
                </ul>
            </section>

            <!-- Cookies -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Cookies</h2>
                <p class="text-gray-700 leading-relaxed">
                    Utilizamos cookies para melhorar sua experiência no sistema. Para informações detalhadas, consulte nossa 
                    <a href="{{ route('politica-cookies') }}" class="text-blue-600 hover:text-blue-700 underline">Política de Cookies</a>.
                </p>
            </section>

            <!-- Alterações -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Alterações na Política</h2>
                <p class="text-gray-700 leading-relaxed">
                    Esta política pode ser atualizada periodicamente. Notificaremos sobre mudanças significativas através do sistema ou por email.
                </p>
            </section>

            <!-- Contato -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Canal de Comunicação</h2>
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">Para exercer seus direitos ou esclarecer dúvidas:</h3>
                    <div class="space-y-2">
                        <p class="text-blue-800"><strong>Email:</strong> {{ \App\Models\Configuracao::get('contact_email', 'privacidade@cbav.com') }}</p>
                        @if(\App\Models\Configuracao::get('contact_phone'))
                            <p class="text-blue-800"><strong>Telefone:</strong> {{ \App\Models\Configuracao::get('contact_phone') }}</p>
                        @endif
                        @if(\App\Models\Configuracao::get('address'))
                            <p class="text-blue-800"><strong>Endereço:</strong> {{ \App\Models\Configuracao::get('address') }}</p>
                        @endif
                    </div>
                    <p class="text-blue-700 text-sm mt-4">
                        <i class="fas fa-clock mr-1"></i>
                        Prazo de resposta: até 15 dias úteis conforme a LGPD
                    </p>
                </div>
            </section>
        </div>

        <!-- Links relacionados -->
        <div class="mt-8 text-center">
            <div class="space-x-4">
                <a href="{{ route('termos-uso') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Termos de Uso
                </a>
                <a href="{{ route('politica-cookies') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Política de Cookies
                </a>
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 