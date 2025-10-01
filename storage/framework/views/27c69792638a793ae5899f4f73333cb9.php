

<?php $__env->startSection('title', 'Termos de Uso - ' . \App\Models\Configuracao::get('app_name', 'CBAV CRM')); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Termos de Uso</h1>
            <p class="text-lg text-gray-600">Última atualização: <?php echo e(date('d/m/Y')); ?></p>
        </div>

        <!-- Conteúdo -->
        <div class="bg-white rounded-lg shadow-lg p-8 space-y-8">
            <!-- Introdução -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Introdução</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Bem-vindo ao <?php echo e(\App\Models\Configuracao::get('app_name', 'CBAV CRM')); ?>. Estes Termos de Uso regem o uso do nosso sistema de gestão ministerial e estabelecem um acordo legal entre você e nossa organização.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Ao acessar e usar este sistema, você aceita estar vinculado a estes termos. Se você não concorda com qualquer parte destes termos, não deve usar nosso sistema.
                </p>
            </section>

            <!-- Definições -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Definições</h2>
                <div class="space-y-3">
                    <div>
                        <strong class="text-gray-900">"Sistema":</strong>
                        <span class="text-gray-700">Refere-se ao <?php echo e(\App\Models\Configuracao::get('app_name', 'CBAV CRM')); ?> e todos os seus recursos e funcionalidades.</span>
                    </div>
                    <div>
                        <strong class="text-gray-900">"Usuário":</strong>
                        <span class="text-gray-700">Qualquer pessoa autorizada que acesse o sistema.</span>
                    </div>
                    <div>
                        <strong class="text-gray-900">"Dados":</strong>
                        <span class="text-gray-700">Todas as informações inseridas, processadas ou armazenadas no sistema.</span>
                    </div>
                </div>
            </section>

            <!-- Uso Aceitável -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Uso Aceitável</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Você concorda em usar o sistema apenas para fins legítimos relacionados à gestão ministerial e atividades da igreja. É proibido:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-gray-700">
                    <li>Usar o sistema para atividades ilegais ou não autorizadas</li>
                    <li>Tentar acessar áreas não autorizadas do sistema</li>
                    <li>Interferir no funcionamento normal do sistema</li>
                    <li>Compartilhar credenciais de acesso com terceiros não autorizados</li>
                    <li>Usar o sistema para enviar spam ou conteúdo malicioso</li>
                    <li>Violar direitos de propriedade intelectual</li>
                </ul>
            </section>

            <!-- Responsabilidades do Usuário -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Responsabilidades do Usuário</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">4.1 Segurança da Conta</h3>
                        <p class="text-gray-700">
                            Você é responsável por manter a confidencialidade de suas credenciais de acesso e por todas as atividades realizadas em sua conta.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">4.2 Veracidade das Informações</h3>
                        <p class="text-gray-700">
                            Você deve fornecer informações precisas, atuais e completas durante o uso do sistema.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">4.3 Notificação de Problemas</h3>
                        <p class="text-gray-700">
                            Você deve notificar imediatamente sobre qualquer uso não autorizado de sua conta ou violação de segurança.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Privacidade e Proteção de Dados -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Privacidade e Proteção de Dados</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Respeitamos sua privacidade e estamos comprometidos em proteger seus dados pessoais de acordo com a Lei Geral de Proteção de Dados (LGPD) e outras legislações aplicáveis.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Para informações detalhadas sobre como coletamos, usamos e protegemos seus dados, consulte nossa 
                    <a href="<?php echo e(route('politica-privacidade')); ?>" class="text-blue-600 hover:text-blue-700 underline">Política de Privacidade</a>.
                </p>
            </section>

            <!-- Propriedade Intelectual -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Propriedade Intelectual</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Todo o conteúdo do sistema, incluindo mas não limitado a textos, gráficos, logotipos, ícones, imagens, clipes de áudio, downloads digitais e compilações de dados, é propriedade da organização ou de seus fornecedores de conteúdo.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    É concedida uma licença limitada e não exclusiva para usar o sistema conforme estes termos.
                </p>
            </section>

            <!-- Limitação de Responsabilidade -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Limitação de Responsabilidade</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    O sistema é fornecido "como está" e "conforme disponível". Não garantimos que o sistema será ininterrupto, seguro ou livre de erros.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Em nenhuma circunstância seremos responsáveis por danos diretos, indiretos, incidentais, especiais ou consequenciais decorrentes do uso ou incapacidade de usar o sistema.
                </p>
            </section>

            <!-- Modificações -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Modificações dos Termos</h2>
                <p class="text-gray-700 leading-relaxed">
                    Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação. É sua responsabilidade revisar periodicamente estes termos.
                </p>
            </section>

            <!-- Encerramento -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Encerramento</h2>
                <p class="text-gray-700 leading-relaxed">
                    Podemos encerrar ou suspender seu acesso ao sistema imediatamente, sem aviso prévio, por qualquer motivo, incluindo violação destes termos.
                </p>
            </section>

            <!-- Lei Aplicável -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Lei Aplicável</h2>
                <p class="text-gray-700 leading-relaxed">
                    Estes termos são regidos pelas leis brasileiras. Qualquer disputa será resolvida nos tribunais competentes do Brasil.
                </p>
            </section>

            <!-- Contato -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Contato</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Se você tiver dúvidas sobre estes Termos de Uso, entre em contato conosco:
                </p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700"><strong>Email:</strong> <?php echo e(\App\Models\Configuracao::get('contact_email', 'contato@cbav.com')); ?></p>
                    <?php if(\App\Models\Configuracao::get('contact_phone')): ?>
                        <p class="text-gray-700"><strong>Telefone:</strong> <?php echo e(\App\Models\Configuracao::get('contact_phone')); ?></p>
                    <?php endif; ?>
                    <?php if(\App\Models\Configuracao::get('address')): ?>
                        <p class="text-gray-700"><strong>Endereço:</strong> <?php echo e(\App\Models\Configuracao::get('address')); ?></p>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Links relacionados -->
        <div class="mt-8 text-center">
            <div class="space-x-4">
                <a href="<?php echo e(route('politica-privacidade')); ?>" class="text-blue-600 hover:text-blue-700 underline">
                    Política de Privacidade
                </a>
                <a href="<?php echo e(route('politica-cookies')); ?>" class="text-blue-600 hover:text-blue-700 underline">
                    Política de Cookies
                </a>
                <a href="<?php echo e(route('home')); ?>" class="text-blue-600 hover:text-blue-700 underline">
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/public/termos-uso.blade.php ENDPATH**/ ?>