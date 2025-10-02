<x-site-layout :configuracoes="$configuracoes">

    <!-- Hero Section -->
    <section class="relative py-20 text-white"
             style="background: linear-gradient(135deg, {{ $configuracoes['cor_primaria'] ?? '#1E40AF' }} 0%, {{ $configuracoes['cor_secundaria'] ?? '#3B82F6' }} 100%);">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">{{ $configuracoes['hero_titulo'] ?? 'Bem-vindo à Nossa Igreja' }}</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-8">{{ $configuracoes['hero_subtitulo'] ?? 'Uma comunidade de fé, amor e esperança.' }}</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ $configuracoes['home_botao_link'] ?? '#sobre' }}" class="btn-primary-light">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ $configuracoes['home_botao_texto'] ?? 'Conheça-nos' }}
                </a>
                @if($configuracoes['doacao_ativa'] ?? false)
                <a href="{{ route('donation.index') }}" class="btn-secondary-outline">
                    <i class="fas fa-heart mr-2"></i>
                    Contribuir
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Seção Sobre (Princípios) -->
    @if($configuracoes['secao_sobre_ativa'] ?? false)
    <section id="sobre" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Nossos Princípios</h2>
                <p class="mt-4 text-lg text-gray-600">Os pilares que sustentam nossa fé e comunidade.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <x-site.feature-card icon="fa-bible" title="Sola Scriptura" description="A Bíblia como única regra de fé e prática." />
                <x-site.feature-card icon="fa-user-check" title="Sacerdócio Universal" description="Todo crente tem acesso direto a Deus por meio de Jesus." />
                <x-site.feature-card icon="fa-water" title="Batismo por Imersão" description="Para crentes professos, como símbolo de fé." />
                <x-site.feature-card icon="fa-users" title="Autonomia Local" description="Cada igreja é independente e governada por seus membros." />
            </div>
        </div>
    </section>
    @endif

    <!-- Seção Cultos -->
    @if($configuracoes['secao_cultos_ativa'] ?? false)
    <section id="cultos" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Nossos Cultos</h2>
                <p class="mt-4 text-lg text-gray-600">Junte-se a nós em adoração e aprendizado da Palavra.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-site.service-card
                    :title="$configuracoes['culto_domingo_manha_titulo']"
                    :time="$configuracoes['culto_domingo_manha_horario']"
                    :description="$configuracoes['culto_domingo_manha_descricao']"
                    icon="fa-sun"
                />
                <x-site.service-card
                    :title="$configuracoes['culto_domingo_noite_titulo']"
                    :time="$configuracoes['culto_domingo_noite_horario']"
                    :description="$configuracoes['culto_domingo_noite_descricao']"
                    icon="fa-moon"
                />
                <x-site.service-card
                    :title="$configuracoes['culto_quarta_titulo']"
                    :time="$configuracoes['culto_quarta_horario']"
                    :description="$configuracoes['culto_quarta_descricao']"
                    icon="fa-calendar-week"
                />
            </div>
        </div>
    </section>
    @endif

    <!-- Seção Ministérios -->
    @if(($configuracoes['secao_ministerios_ativa'] ?? false) && $ministerios->isNotEmpty())
    <section id="ministerios" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Nossos Ministérios</h2>
                <p class="mt-4 text-lg text-gray-600">Encontre um lugar para servir e crescer na fé.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($ministerios as $ministerio)
                    <x-site.ministry-card :ministerio="$ministerio" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Seção Aniversariantes -->
    @if(($configuracoes['secao_aniversariantes_ativa'] ?? false) && ($configuracoes['aniversariantes_mostrar'] ?? false) && $aniversariantes->isNotEmpty())
    <section id="aniversariantes" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Aniversariantes do Mês</h2>
                <p class="mt-4 text-lg text-gray-600">Celebrando a vida de nossos queridos irmãos.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                @foreach($aniversariantes as $aniversariante)
                    <x-site.birthday-card :user="$aniversariante" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

</x-site-layout>

<style>
    .btn-primary-light { @apply bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold text-base hover:bg-blue-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center; }
    .btn-secondary-outline { @apply bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold text-base hover:bg-white hover:text-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center; }
</style>