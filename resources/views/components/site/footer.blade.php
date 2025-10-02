@props(['configuracoes'])

<footer class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sobre a Igreja -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">{{ $configuracoes['igreja_nome'] ?? 'Congregação Batista' }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    {{ $configuracoes['footer_descricao'] ?? 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!' }}
                </p>
                <div class="flex space-x-4 mt-6">
                    @if(!empty($configuracoes['igreja_facebook']))
                        <a href="{{ $configuracoes['igreja_facebook'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook-f text-xl"></i></a>
                    @endif
                    @if(!empty($configuracoes['igreja_instagram']))
                        <a href="{{ $configuracoes['igreja_instagram'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                    @endif
                    @if(!empty($configuracoes['igreja_youtube']))
                        <a href="{{ $configuracoes['igreja_youtube'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-youtube text-xl"></i></a>
                    @endif
                     @if(!empty($configuracoes['igreja_whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $configuracoes['igreja_whatsapp']) }}" target="_blank" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-whatsapp text-xl"></i></a>
                    @endif
                </div>
            </div>

            <!-- Links Rápidos -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ $configuracoes['footer_links_titulo'] ?? 'Links Rápidos' }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#sobre" class="text-gray-400 hover:text-white transition-colors">Sobre Nós</a></li>
                    <li><a href="#ministerios" class="text-gray-400 hover:text-white transition-colors">Ministérios</a></li>
                    <li><a href="{{ route('public.events.index') }}" class="text-gray-400 hover:text-white transition-colors">Eventos</a></li>
                    <li><a href="{{ route('donation.index') }}" class="text-gray-400 hover:text-white transition-colors">Contribuir</a></li>
                    <li><a href="#contato" class="text-gray-400 hover:text-white transition-colors">Contato</a></li>
                </ul>
            </div>

            <!-- Contato -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ $configuracoes['footer_contato_titulo'] ?? 'Contato' }}</h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt w-4 mr-3 mt-1"></i>
                        <span>{{ $configuracoes['igreja_endereco'] ?? 'Endereço não informado' }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone w-4 mr-3 mt-1"></i>
                        <span>{{ $configuracoes['igreja_telefone'] ?? 'Telefone não informado' }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope w-4 mr-3 mt-1"></i>
                        <span>{{ $configuracoes['igreja_email'] ?? 'Email não informado' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} {{ $configuracoes['igreja_nome'] ?? 'Congregação Batista' }}. {{ $configuracoes['footer_copyright_texto'] ?? 'Todos os direitos reservados.' }}</p>
            <p class="mt-2">
                Desenvolvido com <i class="fas fa-heart text-red-500"></i> por
                <a href="{{ route('credits') }}" class="text-blue-400 hover:underline">Vertex Solutions</a>.
            </p>
        </div>
    </div>
</footer>