@extends('layouts.public')

@section('title', 'Política de Cookies - ' . \App\Models\Configuracao::get('app_name', 'CBAV CRM'))

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Política de Cookies</h1>
            <p class="text-lg text-gray-600">Última atualização: {{ date('d/m/Y') }}</p>
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm">
                <i class="fas fa-cookie-bite mr-2"></i>
                Transparência no uso de cookies
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="bg-white rounded-lg shadow-lg p-8 space-y-8">
            <!-- Introdução -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. O que são Cookies?</h2>
                <div class="bg-blue-50 p-6 rounded-lg mb-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-blue-900 font-medium mb-2">Definição</p>
                            <p class="text-blue-800 leading-relaxed">
                                Cookies são pequenos arquivos de texto que são armazenados no seu navegador quando você visita um site. 
                                Eles permitem que o site reconheça seu dispositivo e lembre de suas preferências e ações anteriores.
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    No {{ \App\Models\Configuracao::get('app_name', 'CBAV CRM') }}, utilizamos cookies para melhorar sua experiência, 
                    garantir o funcionamento adequado do sistema e fornecer recursos personalizados.
                </p>
            </section>

            <!-- Tipos de Cookies -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">2. Tipos de Cookies que Utilizamos</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Cookies Essenciais -->
                    <div class="border border-red-200 rounded-lg p-6 bg-red-50">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-shield-alt text-red-600 text-2xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-red-900">Cookies Essenciais</h3>
                        </div>
                        <p class="text-red-800 text-sm mb-3">
                            <strong>Necessários:</strong> Não podem ser desabilitados
                        </p>
                        <ul class="space-y-2 text-red-700 text-sm">
                            <li>• Autenticação de usuário</li>
                            <li>• Segurança da sessão</li>
                            <li>• Proteção CSRF</li>
                            <li>• Funcionalidades básicas</li>
                        </ul>
                        <div class="mt-4 p-3 bg-red-100 rounded">
                            <p class="text-red-800 text-xs">
                                <strong>Duração:</strong> Até o fechamento do navegador ou logout
                            </p>
                        </div>
                    </div>

                    <!-- Cookies de Funcionalidade -->
                    <div class="border border-blue-200 rounded-lg p-6 bg-blue-50">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-cogs text-blue-600 text-2xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-blue-900">Cookies de Funcionalidade</h3>
                        </div>
                        <p class="text-blue-800 text-sm mb-3">
                            <strong>Opcionais:</strong> Melhoram a experiência do usuário
                        </p>
                        <ul class="space-y-2 text-blue-700 text-sm">
                            <li>• Preferências de idioma</li>
                            <li>• Configurações de tema</li>
                            <li>• Lembrar credenciais</li>
                            <li>• Personalização da interface</li>
                        </ul>
                        <div class="mt-4 p-3 bg-blue-100 rounded">
                            <p class="text-blue-800 text-xs">
                                <strong>Duração:</strong> 30 dias a 1 ano
                            </p>
                        </div>
                    </div>

                    <!-- Cookies de Performance -->
                    <div class="border border-green-200 rounded-lg p-6 bg-green-50">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-chart-line text-green-600 text-2xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-green-900">Cookies de Performance</h3>
                        </div>
                        <p class="text-green-800 text-sm mb-3">
                            <strong>Opcionais:</strong> Ajudam a melhorar o sistema
                        </p>
                        <ul class="space-y-2 text-green-700 text-sm">
                            <li>• Análise de uso do sistema</li>
                            <li>• Tempo de carregamento</li>
                            <li>• Detecção de erros</li>
                            <li>• Otimização de performance</li>
                        </ul>
                        <div class="mt-4 p-3 bg-green-100 rounded">
                            <p class="text-green-800 text-xs">
                                <strong>Duração:</strong> 6 meses a 2 anos
                            </p>
                        </div>
                    </div>

                    <!-- Cookies de Segurança -->
                    <div class="border border-purple-200 rounded-lg p-6 bg-purple-50">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-lock text-purple-600 text-2xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-purple-900">Cookies de Segurança</h3>
                        </div>
                        <p class="text-purple-800 text-sm mb-3">
                            <strong>Essenciais:</strong> Protegem contra ataques
                        </p>
                        <ul class="space-y-2 text-purple-700 text-sm">
                            <li>• Prevenção de ataques</li>
                            <li>• Verificação de integridade</li>
                            <li>• Controle de acesso</li>
                            <li>• Monitoramento de segurança</li>
                        </ul>
                        <div class="mt-4 p-3 bg-purple-100 rounded">
                            <p class="text-purple-800 text-xs">
                                <strong>Duração:</strong> Variável conforme necessidade
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cookies Específicos -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Cookies Específicos Utilizados</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-900">Nome</th>
                                <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-900">Finalidade</th>
                                <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-900">Duração</th>
                                <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-900">Tipo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-mono">laravel_session</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Identificação da sessão do usuário</td>
                                <td class="px-4 py-3 text-sm text-gray-700">2 horas</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Essencial</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-mono">XSRF-TOKEN</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Proteção contra ataques CSRF</td>
                                <td class="px-4 py-3 text-sm text-gray-700">2 horas</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Essencial</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-mono">remember_token</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Lembrar login do usuário</td>
                                <td class="px-4 py-3 text-sm text-gray-700">30 dias</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Funcional</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-mono">user_preferences</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Configurações personalizadas</td>
                                <td class="px-4 py-3 text-sm text-gray-700">1 ano</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Funcional</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-mono">analytics_consent</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Consentimento para análises</td>
                                <td class="px-4 py-3 text-sm text-gray-700">1 ano</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Performance</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Gerenciamento de Cookies -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Como Gerenciar Cookies</h2>
                <div class="space-y-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-amber-900 mb-4">
                            <i class="fas fa-cog mr-2"></i>Configurações do Navegador
                        </h3>
                        <p class="text-amber-800 mb-4">
                            Você pode controlar e/ou excluir cookies conforme desejar através das configurações do seu navegador:
                        </p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-amber-900 mb-2">Navegadores Populares:</h4>
                                <ul class="space-y-1 text-amber-700 text-sm">
                                    <li>• <strong>Chrome:</strong> Configurações > Privacidade > Cookies</li>
                                    <li>• <strong>Firefox:</strong> Opções > Privacidade > Cookies</li>
                                    <li>• <strong>Safari:</strong> Preferências > Privacidade > Cookies</li>
                                    <li>• <strong>Edge:</strong> Configurações > Privacidade > Cookies</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-amber-900 mb-2">Ações Disponíveis:</h4>
                                <ul class="space-y-1 text-amber-700 text-sm">
                                    <li>• Bloquear todos os cookies</li>
                                    <li>• Bloquear cookies de terceiros</li>
                                    <li>• Excluir cookies existentes</li>
                                    <li>• Configurar notificações</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Importante Saber
                        </h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <span>Desabilitar cookies essenciais pode afetar o funcionamento do sistema</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <span>Você pode escolher quais tipos de cookies aceitar</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <span>As configurações podem ser alteradas a qualquer momento</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Cookies de Terceiros -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Cookies de Terceiros</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Alguns serviços integrados ao nosso sistema podem definir seus próprios cookies:
                </p>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Serviços de Pagamento</h4>
                        <p class="text-gray-700 text-sm">
                            Gateways de pagamento (Stripe, MercadoPago) podem usar cookies para processar transações com segurança.
                        </p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">CDNs e Recursos Externos</h4>
                        <p class="text-gray-700 text-sm">
                            Serviços como Google Fonts, FontAwesome e Tailwind CSS podem definir cookies para otimização.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Seus Direitos -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Seus Direitos Relacionados a Cookies</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Consentimento Informado</strong>
                                <p class="text-gray-700 text-sm">Direito de ser informado sobre todos os cookies utilizados</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-times-circle text-red-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Recusar Cookies</strong>
                                <p class="text-gray-700 text-sm">Direito de recusar cookies não essenciais</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-trash text-orange-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Excluir Cookies</strong>
                                <p class="text-gray-700 text-sm">Direito de excluir cookies a qualquer momento</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-cog text-blue-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Alterar Preferências</strong>
                                <p class="text-gray-700 text-sm">Direito de modificar configurações de cookies</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info text-purple-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Informações Detalhadas</strong>
                                <p class="text-gray-700 text-sm">Direito de obter informações específicas sobre cookies</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-undo text-gray-600 mt-1"></i>
                            <div>
                                <strong class="text-gray-900">Retirar Consentimento</strong>
                                <p class="text-gray-700 text-sm">Direito de retirar consentimento a qualquer momento</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Alterações -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Alterações nesta Política</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Podemos atualizar esta Política de Cookies periodicamente para refletir mudanças em nossa prática ou por outras razões operacionais, legais ou regulamentares.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">
                        <i class="fas fa-bell mr-2"></i>
                        <strong>Notificações:</strong> Alterações significativas serão comunicadas através de aviso no sistema ou por email.
                    </p>
                </div>
            </section>

            <!-- Contato -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Contato sobre Cookies</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        Se você tiver dúvidas sobre nossa política de cookies ou quiser exercer seus direitos, entre em contato:
                    </p>
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong>Email:</strong> {{ \App\Models\Configuracao::get('contact_email', 'cookies@cbav.com') }}</p>
                        @if(\App\Models\Configuracao::get('contact_phone'))
                            <p class="text-gray-700"><strong>Telefone:</strong> {{ \App\Models\Configuracao::get('contact_phone') }}</p>
                        @endif
                        <p class="text-gray-700"><strong>Assunto:</strong> "Política de Cookies - Solicitação"</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Links relacionados -->
        <div class="mt-8 text-center">
            <div class="space-x-4">
                <a href="{{ route('termos-uso') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Termos de Uso
                </a>
                <a href="{{ route('politica-privacidade') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Política de Privacidade
                </a>
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 underline">
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 