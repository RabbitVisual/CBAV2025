<!-- Footer Profissional e Moderno -->
<footer class="bg-gray-900 text-white py-20 relative">
    <!-- Gradiente sutil de fundo -->
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900 via-gray-900 to-black opacity-90"></div>
    
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
        <!-- Seção Principal do Footer -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-16">
            <!-- Informações da Igreja -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center space-x-4">
                    <?php if(isset($configuracoes['logo']) && $configuracoes['logo']->valor): ?>
                        <img src="<?php echo e(Storage::url($configuracoes['logo']->valor)); ?>" 
                             alt="Logo" 
                             class="w-12 h-12 object-contain bg-white/10 rounded-xl p-2 border border-white/20">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center border border-white/20">
                            <i class="fas fa-church text-white text-xl"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-xl font-bold text-white">
                            <?php echo e($configuracoes['igreja_nome']->valor ?? 'Congregação Batista Avenida'); ?>

                        </h3>
                        <p class="text-gray-300 text-sm font-medium">
                            <?php echo e($configuracoes['igreja_slogan']->valor ?? 'Uma igreja comprometida com o amor de Cristo'); ?>

                        </p>
                    </div>
                </div>
                
                <p class="text-gray-300 leading-relaxed max-w-md">
                    <?php echo e($configuracoes['footer_descricao']->valor ?? 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família e crescer espiritualmente conosco.'); ?>

                </p>
                
                <!-- Redes Sociais -->
                <?php if(isset($configuracoes['footer_redes_sociais_ativa']) && $configuracoes['footer_redes_sociais_ativa']->valor == '1'): ?>
                <div class="flex space-x-4 pt-4">
                    <?php if(isset($configuracoes['igreja_facebook']) && $configuracoes['igreja_facebook']->valor): ?>
                        <a href="<?php echo e($configuracoes['igreja_facebook']->valor); ?>" 
                           target="_blank"
                           class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_instagram']) && $configuracoes['igreja_instagram']->valor): ?>
                        <a href="<?php echo e($configuracoes['igreja_instagram']->valor); ?>" 
                           target="_blank"
                           class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_youtube']) && $configuracoes['igreja_youtube']->valor): ?>
                        <a href="<?php echo e($configuracoes['igreja_youtube']->valor); ?>" 
                           target="_blank"
                           class="social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_whatsapp']) && $configuracoes['igreja_whatsapp']->valor): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $configuracoes['igreja_whatsapp']->valor)); ?>" 
                           target="_blank"
                           class="social-link">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Links Rápidos -->
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-white mb-6">
                    <?php echo e($configuracoes['footer_links_titulo']->valor ?? 'Links Rápidos'); ?>

                </h4>
                <ul class="space-y-4">
                    <li>
                        <a href="#sobre" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_sobre']->valor ?? 'Sobre'); ?>

                        </a>
                    </li>
                    <li>
                        <a href="#ministerios" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_ministerios']->valor ?? 'Ministérios'); ?>

                        </a>
                    </li>
                    <li>
                        <a href="#cultos" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_cultos']->valor ?? 'Cultos'); ?>

                        </a>
                    </li>
                    <li>
                        <a href="#eventos" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_eventos']->valor ?? 'Eventos'); ?>

                        </a>
                    </li>
                    <li>
                        <a href="#aniversariantes" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_aniversariantes']->valor ?? 'Aniversariantes'); ?>

                        </a>
                    </li>
                    <li>
                        <a href="#doacao" class="footer-nav-link">
                            <?php echo e($configuracoes['footer_link_doacao']->valor ?? 'Doação'); ?>

                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contato -->
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-white mb-6">
                    <?php echo e($configuracoes['footer_contato_titulo']->valor ?? 'Contato'); ?>

                </h4>
                <ul class="space-y-4">
                    <?php if(isset($configuracoes['igreja_endereco']) && $configuracoes['igreja_endereco']->valor): ?>
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-400 mt-1 flex-shrink-0"></i>
                        <span class="text-gray-300 text-sm leading-relaxed">
                            <?php echo e($configuracoes['igreja_endereco']->valor); ?>

                        </span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_telefone']) && $configuracoes['igreja_telefone']->valor): ?>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone text-green-400 flex-shrink-0"></i>
                        <a href="tel:<?php echo e($configuracoes['igreja_telefone']->valor); ?>" class="text-gray-300 text-sm hover:text-white transition-colors">
                            <?php echo e($configuracoes['igreja_telefone']->valor); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_email']) && $configuracoes['igreja_email']->valor): ?>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-purple-400 flex-shrink-0"></i>
                        <a href="mailto:<?php echo e($configuracoes['igreja_email']->valor); ?>" class="text-gray-300 text-sm hover:text-white transition-colors">
                            <?php echo e($configuracoes['igreja_email']->valor); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(isset($configuracoes['igreja_website']) && $configuracoes['igreja_website']->valor): ?>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-globe text-orange-400 flex-shrink-0"></i>
                        <a href="<?php echo e($configuracoes['igreja_website']->valor); ?>" 
                           target="_blank"
                           class="text-gray-300 text-sm hover:text-white transition-colors">
                            <?php echo e($configuracoes['igreja_website']->valor); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Linha de separação -->
        <div class="border-t border-gray-700 pt-8">
            <!-- Copyright e Links Legais -->
            <div class="flex flex-col lg:flex-row justify-between items-center space-y-6 lg:space-y-0">
                <!-- Copyright -->
                <div class="text-center lg:text-left">
                    <p class="text-gray-400 text-sm font-medium">
                        &copy; <?php echo e(date('Y')); ?> <?php echo e($configuracoes['igreja_nome']->valor ?? 'Congregação Batista Avenida'); ?>. 
                        <?php echo e($configuracoes['footer_copyright_texto']->valor ?? 'Todos os direitos reservados.'); ?>

                    </p>
                </div>

                <!-- Links Legais e Institucionais -->
                <div class="flex flex-wrap justify-center lg:justify-end items-center gap-6">
                    <!-- Links Legais -->
                    <div class="flex items-center space-x-6">
                        <a href="<?php echo e(route('termos-uso')); ?>" class="legal-link">
                            Termos de Uso
                        </a>
                        <a href="<?php echo e(route('politica-privacidade')); ?>" class="legal-link">
                            Privacidade
                        </a>
                        <a href="<?php echo e(route('politica-cookies')); ?>" class="legal-link">
                            Cookies
                        </a>
                    </div>
                    
                    <!-- Separador -->
                    <div class="hidden lg:block w-px h-4 bg-gray-600"></div>
                    
                    <!-- Links Institucionais -->
                    <div class="flex items-center space-x-6">
                        <?php if(isset($configuracoes['footer_link_creditos_ativa']) && $configuracoes['footer_link_creditos_ativa']->valor == '1'): ?>
                        <a href="<?php echo e(route('creditos')); ?>" class="institutional-link">
                            <?php echo e($configuracoes['footer_link_creditos_texto']->valor ?? 'Reinan Rodrigues'); ?>

                        </a>
                        <?php endif; ?>
                        
                        <?php if(isset($configuracoes['footer_link_vertex_ativa']) && $configuracoes['footer_link_vertex_ativa']->valor == '1'): ?>
                        <a href="#" 
                           target="_blank" 
                           class="institutional-link">
                            <?php echo e($configuracoes['footer_link_vertex_texto']->valor ?? 'Vertex Solutions'); ?>

                        </a>
                        <?php endif; ?>
                        
                        <!-- Link para Documentação -->
                        <a href="https://docs.rabbitvisual.com/" 
                           target="_blank" 
                           class="institutional-link">
                            <i class="fas fa-book mr-2"></i>Documentação
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Estilos modernos e profissionais -->
<style>
    /* Links de redes sociais */
    .social-link {
        @apply w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/30 hover:scale-110;
    }
    
    .social-link:hover {
        transform: translateY(-2px) scale(110%);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.15);
    }
    
    /* Links de navegação do footer */
    .footer-nav-link {
        @apply text-gray-300 hover:text-white transition-all duration-300 relative font-medium;
    }
    
    .footer-nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        transition: width 0.3s ease;
        border-radius: 1px;
    }
    
    .footer-nav-link:hover::after {
        width: 100%;
    }
    
    /* Links legais */
    .legal-link {
        @apply text-gray-400 hover:text-white transition-colors duration-300 text-sm font-medium;
    }
    
    /* Links institucionais */
    .institutional-link {
        @apply text-gray-400 hover:text-white transition-colors duration-300 text-sm font-medium flex items-center;
    }
    
    .institutional-link:hover {
        transform: translateY(-1px);
    }
    
    /* Efeitos de hover suaves */
    .footer-nav-link:hover,
    .legal-link:hover,
    .institutional-link:hover {
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
    }
    
    /* Responsividade melhorada */
    @media (max-width: 1024px) {
        .social-link {
            @apply w-12 h-12;
        }
    }
    
    /* Animações suaves */
    footer * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style> <?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/footer-welcome.blade.php ENDPATH**/ ?>