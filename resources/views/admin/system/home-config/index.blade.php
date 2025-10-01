@extends('layouts.admin')

@section('title', __('Configuração da Página Inicial'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Configuração da Página Inicial') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Personalize completamente a página inicial do sistema') }}</p>
            
            <!-- Guia de orientação -->
                                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-lightbulb text-green-600 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">
                                        Dicas de Personalização
                                    </h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p><strong>Básico:</strong> Informações da igreja, logo e contatos</p>
                                        <p><strong>Conteúdo:</strong> Títulos, descrições e botões da homepage</p>
                                        <p><strong>Design:</strong> Cores e identidade visual</p>
                                        <p><strong>Seções:</strong> Ativar/desativar seções da página</p>
                                        <p><strong>Horários:</strong> Horários de cultos, eventos e Escola Dominical</p>
                                        <p><strong>Doação:</strong> Textos e configurações da seção de doação</p>
                                        <p><strong>Contato:</strong> Configurações da seção de contato</p>
                                        <p><strong>SEO:</strong> Meta tags para otimização de busca</p>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
        <div class="flex space-x-3">
            <button onclick="previewConfiguracao()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-eye mr-2"></i>
                {{ __('Visualizar') }}
            </button>
            <a href="{{ route('admin.system.settings.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-cog mr-2"></i>Configurações Gerais
            </a>
        </div>
    </div>

    <form action="{{ route('admin.system.home-config.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Abas de Navegação -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="showTab('basico')" 
                            class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-cog mr-2"></i>{{ __('Básico') }}
                    </button>
                    <button type="button" onclick="showTab('conteudo')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-file-alt mr-2"></i>{{ __('Conteúdo') }}
                    </button>
                    <button type="button" onclick="showTab('design')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-palette mr-2"></i>{{ __('Design') }}
                    </button>
                    <button type="button" onclick="showTab('secoes')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-th-large mr-2"></i>{{ __('Seções') }}
                    </button>
                    <button type="button" onclick="showTab('horarios')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-clock mr-2"></i>{{ __('Horários') }}
                    </button>
                    <button type="button" onclick="showTab('doacao')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-heart mr-2"></i>{{ __('Doação') }}
                    </button>

                    <button type="button" onclick="showTab('contato')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-phone mr-2"></i>{{ __('Contato') }}
                    </button>
                    <button type="button" onclick="showTab('seo')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-search mr-2"></i>{{ __('SEO') }}
                    </button>
                    <button type="button" onclick="showTab('header')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-heading mr-2"></i>{{ __('Header') }}
                    </button>
                    <button type="button" onclick="showTab('footer')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-shoe-prints mr-2"></i>{{ __('Footer') }}
                    </button>
                </nav>
            </div>

            <!-- Conteúdo das Abas -->
            <div class="p-6">
                <!-- Aba Básico -->
                <div id="tab-basico" class="tab-content">
                    <div class="space-y-8">
                        <!-- Informações da Igreja -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-church text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-blue-900">Informações da Igreja</h4>
                                    <p class="text-sm text-blue-600">Configure as informações básicas da sua igreja</p>
                                </div>
                            </div>
                            
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                    <label for="igreja_nome" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-church mr-1"></i>{{ __('Nome da Igreja') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="igreja_nome" 
                                   name="igreja_nome" 
                                   value="{{ old('igreja_nome', $configuracoes['igreja_nome'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   required>
                        </div>

                        <div>
                                    <label for="igreja_slogan" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-quote-left mr-1"></i>{{ __('Slogan') }}
                            </label>
                            <input type="text" 
                                   id="igreja_slogan" 
                                   name="igreja_slogan" 
                                   value="{{ old('igreja_slogan', $configuracoes['igreja_slogan'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <div>
                                    <label for="igreja_telefone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-1"></i>{{ __('Telefone') }}
                            </label>
                            <input type="text" 
                                   id="igreja_telefone" 
                                   name="igreja_telefone" 
                                   value="{{ old('igreja_telefone', $configuracoes['igreja_telefone'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <div>
                                    <label for="igreja_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-1"></i>{{ __('Email') }}
                            </label>
                            <input type="email" 
                                   id="igreja_email" 
                                   name="igreja_email" 
                                   value="{{ old('igreja_email', $configuracoes['igreja_email'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <div>
                                    <label for="igreja_website" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-globe mr-1"></i>{{ __('Website') }}
                            </label>
                            <input type="text" 
                                   id="igreja_website" 
                                   name="igreja_website" 
                                   value="{{ old('igreja_website', $configuracoes['igreja_website'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="https://exemplo.com">
                        </div>

                        <div>
                                    <label for="igreja_facebook" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fab fa-facebook mr-1"></i>{{ __('Facebook') }}
                            </label>
                            <input type="text" 
                                   id="igreja_facebook" 
                                   name="igreja_facebook" 
                                   value="{{ old('igreja_facebook', $configuracoes['igreja_facebook'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="https://facebook.com/exemplo">
                        </div>

                        <div class="md:col-span-2">
                                    <label for="igreja_endereco" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ __('Endereço') }}
                            </label>
                            <textarea id="igreja_endereco" 
                                      name="igreja_endereco" 
                                      rows="3"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none">{{ old('igreja_endereco', $configuracoes['igreja_endereco'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Logo da Igreja -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-image text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-green-900">Logo da Igreja</h4>
                                    <p class="text-sm text-green-600">Faça upload do logo da sua igreja</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                            @if(isset($configuracoes['logo']) && $configuracoes['logo'])
                                    <div class="bg-white p-4 rounded-xl border border-green-200">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-image mr-1"></i>{{ __('Logo Atual') }}
                                        </label>
                                    <img src="{{ Storage::url($configuracoes['logo']) }}" 
                                         alt="Logo atual" 
                                         class="w-32 h-32 object-contain border border-gray-300 rounded-lg">
                                </div>
                            @endif
                                
                                <div>
                                    <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-upload mr-1"></i>{{ __('Upload do Logo') }}
                                    </label>
                            <input type="file" 
                                   id="logo" 
                                   name="logo" 
                                   accept="image/*"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Conteúdo -->
                <div id="tab-conteudo" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações do Hero -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-heading text-white text-xl"></i>
                                </div>
                        <div>
                                    <h4 class="text-xl font-bold text-blue-900">Configurações do Hero</h4>
                                    <p class="text-sm text-blue-600">Personalize a seção principal da página inicial</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="titulo_principal" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título Principal') }}
                            </label>
                            <input type="text" 
                                   id="titulo_principal" 
                                   name="titulo_principal" 
                                   value="{{ old('titulo_principal', $configuracoes['titulo_principal'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHeroPreview()">
                        </div>

                        <div>
                                    <label for="subtitulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Subtítulo') }}
                            </label>
                            <input type="text" 
                                   id="subtitulo" 
                                   name="subtitulo" 
                                   value="{{ old('subtitulo', $configuracoes['subtitulo'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHeroPreview()">
                        </div>

                                <div class="md:col-span-2">
                                    <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-justify mr-1"></i>{{ __('Descrição') }}
                            </label>
                            <textarea id="descricao" 
                                      name="descricao" 
                                      rows="4"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                              placeholder="{{ __('Descrição da igreja e sua missão...') }}"
                                              oninput="updateHeroPreview()">{{ old('descricao', $configuracoes['descricao'] ?? '') }}</textarea>
                        </div>

                            <div>
                                    <label for="texto_botao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-mouse-pointer mr-1"></i>{{ __('Texto do Botão Principal') }}
                                </label>
                                <input type="text" 
                                       id="texto_botao" 
                                       name="texto_botao" 
                                       value="{{ old('texto_botao', $configuracoes['texto_botao'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHeroPreview()">
                            </div>

                            <div>
                                    <label for="url_botao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-link mr-1"></i>{{ __('URL do Botão Principal') }}
                                </label>
                                <input type="text" 
                                       id="url_botao" 
                                       name="url_botao" 
                                       value="{{ old('url_botao', $configuracoes['url_botao'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           placeholder="#sobre ou https://exemplo.com"
                                           oninput="updateHeroPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Preview do Hero -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview do Hero</h4>
                                    <p class="text-sm text-purple-600">Visualize como a seção principal aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="hero-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="text-center">
                                    <h1 id="preview-titulo" class="text-3xl font-bold text-gray-900 mb-4">
                                        {{ $configuracoes['titulo_principal'] ?? 'Bem-vindo à Nossa Igreja' }}
                                    </h1>
                                    <p id="preview-subtitulo" class="text-xl text-gray-600 mb-6">
                                        {{ $configuracoes['subtitulo'] ?? 'Uma comunidade de fé, amor e esperança' }}
                                    </p>
                                    <p id="preview-descricao" class="text-gray-700 mb-8">
                                        {{ $configuracoes['descricao'] ?? 'Descrição da igreja e sua missão...' }}
                                    </p>
                                    <button id="preview-botao" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                                        {{ $configuracoes['texto_botao'] ?? 'Conheça Nossa Igreja' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações de Aniversariantes -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-birthday-cake text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-green-900">Configurações de Aniversariantes</h4>
                                    <p class="text-sm text-green-600">Personalize a seção de aniversariantes</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="aniversariantes_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título da Seção Aniversariantes') }}
                                    </label>
                                    <input type="text" 
                                           id="aniversariantes_titulo" 
                                           name="aniversariantes_titulo" 
                                           value="{{ old('aniversariantes_titulo', $configuracoes['aniversariantes_titulo'] ?? 'Aniversariantes do Mês') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Aniversariantes do Mês"
                                           oninput="updateAniversariantesPreview()">
                                </div>

                                <div>
                                    <label for="aniversariantes_subtitulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Subtítulo da Seção Aniversariantes') }}
                                    </label>
                                    <input type="text" 
                                           id="aniversariantes_subtitulo" 
                                           name="aniversariantes_subtitulo" 
                                           value="{{ old('aniversariantes_subtitulo', $configuracoes['aniversariantes_subtitulo'] ?? 'Celebrando a vida dos nossos membros') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Celebrando a vida dos nossos membros"
                                           oninput="updateAniversariantesPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Preview dos Aniversariantes -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview dos Aniversariantes</h4>
                                    <p class="text-sm text-purple-600">Visualize como a seção de aniversariantes aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="aniversariantes-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="text-center">
                                    <h2 id="preview-aniversariantes-titulo" class="text-2xl font-bold text-gray-900 mb-2">
                                        {{ $configuracoes['aniversariantes_titulo'] ?? 'Aniversariantes do Mês' }}
                                    </h2>
                                    <p id="preview-aniversariantes-subtitulo" class="text-gray-600 mb-6">
                                        {{ $configuracoes['aniversariantes_subtitulo'] ?? 'Celebrando a vida dos nossos membros' }}
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-gradient-to-br from-pink-50 to-red-50 p-4 rounded-lg border border-pink-200">
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-red-500 rounded-full mx-auto mb-3 flex items-center justify-center">
                                                    <i class="fas fa-birthday-cake text-white text-xl"></i>
                                                </div>
                                                <h3 class="font-semibold text-gray-900">João Silva</h3>
                                                <p class="text-sm text-gray-600">15 de Janeiro</p>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full mx-auto mb-3 flex items-center justify-center">
                                                    <i class="fas fa-birthday-cake text-white text-xl"></i>
                                                </div>
                                                <h3 class="font-semibold text-gray-900">Maria Santos</h3>
                                                <p class="text-sm text-gray-600">22 de Janeiro</p>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full mx-auto mb-3 flex items-center justify-center">
                                                    <i class="fas fa-birthday-cake text-white text-xl"></i>
                                                </div>
                                                <h3 class="font-semibold text-gray-900">Pedro Costa</h3>
                                                <p class="text-sm text-gray-600">28 de Janeiro</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Design -->
                <div id="tab-design" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações de Cores -->
                        <div class="border border-orange-200 rounded-lg p-6 bg-gradient-to-br from-orange-50 to-red-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-palette text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-orange-900">Configurações de Cores</h4>
                                    <p class="text-sm text-orange-600">Personalize as cores da identidade visual</p>
                                </div>
                            </div>
                            
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                    <label for="cor_primaria" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-circle mr-1"></i>{{ __('Cor Primária') }}
                            </label>
                            <input type="color" 
                                   id="cor_primaria" 
                                   name="cor_primaria" 
                                   value="{{ old('cor_primaria', $configuracoes['cor_primaria'] ?? '#1e40af') }}"
                                           class="w-full h-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                           onchange="updateColorPreview()">
                        </div>

                        <div>
                                    <label for="cor_secundaria" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-circle mr-1"></i>{{ __('Cor Secundária') }}
                            </label>
                            <input type="color" 
                                   id="cor_secundaria" 
                                   name="cor_secundaria" 
                                   value="{{ old('cor_secundaria', $configuracoes['cor_secundaria'] ?? '#3b82f6') }}"
                                           class="w-full h-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                           onchange="updateColorPreview()">
                        </div>

                        <div>
                                    <label for="cor_destaque" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-circle mr-1"></i>{{ __('Cor de Destaque') }}
                            </label>
                            <input type="color" 
                                   id="cor_destaque" 
                                   name="cor_destaque" 
                                   value="{{ old('cor_destaque', $configuracoes['cor_destaque'] ?? '#10b981') }}"
                                           class="w-full h-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                           onchange="updateColorPreview()">
                        </div>

                        <div>
                                    <label for="cor_texto" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-circle mr-1"></i>{{ __('Cor do Texto') }}
                            </label>
                            <input type="color" 
                                   id="cor_texto" 
                                   name="cor_texto" 
                                   value="{{ old('cor_texto', $configuracoes['cor_texto'] ?? '#1f2937') }}"
                                           class="w-full h-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                           onchange="updateColorPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Configuração de Foto de Fundo do Hero -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-image text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-blue-900">Foto de Fundo do Hero</h4>
                                    <p class="text-sm text-blue-600">Configure uma imagem de fundo para a seção principal da página</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Ativar/Desativar Foto de Fundo -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="hero_foto_fundo_ativa" 
                                               value="1" 
                                               {{ old('hero_foto_fundo_ativa', $configuracoes['hero_foto_fundo_ativa'] ?? '0') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-toggle-on mr-1"></i>Ativar foto de fundo no hero
                                        </span>
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1">Quando ativado, a foto de fundo será exibida com uma sobreposição para melhor legibilidade</p>
                                </div>

                                <!-- Upload da Foto -->
                                <div>
                                    <label for="hero_foto_fundo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-upload mr-1"></i>Foto de Fundo
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" 
                                               id="hero_foto_fundo" 
                                               name="hero_foto_fundo" 
                                               accept="image/*"
                                               class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        
                                        @if(isset($configuracoes['hero_foto_fundo']) && $configuracoes['hero_foto_fundo']->valor)
                                            <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-gray-200">
                                                <img src="{{ Storage::url($configuracoes['hero_foto_fundo']->valor) }}" 
                                                     alt="Foto de fundo atual" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Recomendado: 1920x1080px ou maior. Formatos: JPG, PNG, WebP
                                    </p>
                                </div>

                                <!-- Configurações da Sobreposição -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="hero_overlay_opacidade" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-adjust mr-1"></i>Opacidade da Sobreposição
                                        </label>
                                        <input type="range" 
                                               id="hero_overlay_opacidade" 
                                               name="hero_overlay_opacidade" 
                                               min="0.1" 
                                               max="0.9" 
                                               step="0.1"
                                               value="{{ old('hero_overlay_opacidade', $configuracoes['hero_overlay_opacidade'] ?? '0.6') }}"
                                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                               oninput="updateOverlayPreview()">
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span>Transparente</span>
                                            <span id="overlay-value">60%</span>
                                            <span>Opaco</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="hero_overlay_cor" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-palette mr-1"></i>Cor da Sobreposição
                                        </label>
                                        <input type="color" 
                                               id="hero_overlay_cor" 
                                               name="hero_overlay_cor" 
                                               value="{{ old('hero_overlay_cor', $configuracoes['hero_overlay_cor'] ?? '#1e40af') }}"
                                               class="w-full h-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               onchange="updateOverlayPreview()">
                                    </div>
                                </div>

                                <!-- Preview da Sobreposição -->
                                <div class="bg-white rounded-xl p-4 border border-gray-200">
                                    <h5 class="font-semibold text-gray-900 mb-3">Preview da Sobreposição</h5>
                                    <div id="overlay-preview" class="relative w-full h-32 rounded-lg overflow-hidden border border-gray-300">
                                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><defs><pattern id=%22grain%22 width=%22100%22 height=%22100%22 patternUnits=%22userSpaceOnUse%22><circle cx=%2250%22 cy=%2250%22 r=%221%22 fill=%22%23e5e7eb%22/><circle cx=%2220%22 cy=%2280%22 r=%221%22 fill=%22%23d1d5db%22/><circle cx=%2280%22 cy=%2220%22 r=%221%22 fill=%22%23f3f4f6%22/></pattern></defs><rect width=%22100%22 height=%22100%22 fill=%22url(%23grain)%22/></svg>');"></div>
                                        <div id="overlay-layer" class="absolute inset-0" style="background-color: {{ $configuracoes['hero_overlay_cor'] ?? '#1e40af' }}; opacity: {{ $configuracoes['hero_overlay_opacidade'] ?? '0.6' }};"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <h3 class="text-lg font-bold mb-1">Texto de Exemplo</h3>
                                                <p class="text-sm opacity-90">Legibilidade testada</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview das Cores -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview das Cores</h4>
                                    <p class="text-sm text-purple-600">Visualize como as cores aparecerão na página</p>
                                </div>
                            </div>
                            
                            <div id="color-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <div id="preview-cor-primaria" class="w-20 h-20 rounded-xl mx-auto mb-3 border-2 border-gray-200" style="background-color: {{ $configuracoes['cor_primaria'] ?? '#1e40af' }};"></div>
                                        <h3 class="font-semibold text-gray-900">Primária</h3>
                                        <p id="preview-hex-primaria" class="text-sm text-gray-600 font-mono">{{ $configuracoes['cor_primaria'] ?? '#1e40af' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <div id="preview-cor-secundaria" class="w-20 h-20 rounded-xl mx-auto mb-3 border-2 border-gray-200" style="background-color: {{ $configuracoes['cor_secundaria'] ?? '#3b82f6' }};"></div>
                                        <h3 class="font-semibold text-gray-900">Secundária</h3>
                                        <p id="preview-hex-secundaria" class="text-sm text-gray-600 font-mono">{{ $configuracoes['cor_secundaria'] ?? '#3b82f6' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <div id="preview-cor-destaque" class="w-20 h-20 rounded-xl mx-auto mb-3 border-2 border-gray-200" style="background-color: {{ $configuracoes['cor_destaque'] ?? '#10b981' }};"></div>
                                        <h3 class="font-semibold text-gray-900">Destaque</h3>
                                        <p id="preview-hex-destaque" class="text-sm text-gray-600 font-mono">{{ $configuracoes['cor_destaque'] ?? '#10b981' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <div id="preview-cor-texto" class="w-20 h-20 rounded-xl mx-auto mb-3 border-2 border-gray-200" style="background-color: {{ $configuracoes['cor_texto'] ?? '#1f2937' }};"></div>
                                        <h3 class="font-semibold text-gray-900">Texto</h3>
                                        <p id="preview-hex-texto" class="text-sm text-gray-600 font-mono">{{ $configuracoes['cor_texto'] ?? '#1f2937' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Exemplo de aplicação das cores -->
                                <div class="mt-8 p-6 rounded-xl border-2 border-gray-200">
                                    <h3 class="text-lg font-bold mb-4" style="color: {{ $configuracoes['cor_primaria'] ?? '#1e40af' }};">Exemplo de Aplicação</h3>
                                    <p class="mb-4" style="color: {{ $configuracoes['cor_texto'] ?? '#1f2937' }};">Este é um exemplo de como o texto aparecerá com a cor selecionada.</p>
                                    <button class="px-6 py-2 rounded-lg font-semibold transition duration-200" 
                                            style="background-color: {{ $configuracoes['cor_secundaria'] ?? '#3b82f6' }}; color: white;">
                                        Botão Secundário
                                    </button>
                                    <button class="px-6 py-2 rounded-lg font-semibold transition duration-200 ml-3" 
                                            style="background-color: {{ $configuracoes['cor_destaque'] ?? '#10b981' }}; color: white;">
                                        Botão Destaque
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Seções -->
                <div id="tab-secoes" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações Gerais das Seções -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-th-large text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-blue-900">Configurações das Seções</h4>
                                    <p class="text-sm text-blue-600">Ative ou desative as seções da página inicial</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="mostrar_sobre" 
                                   name="secoes[]" 
                                   value="sobre"
                                   {{ ($configuracoes['secoes']['sobre'] ?? false) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="mostrar_sobre" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-info-circle mr-2"></i>{{ __('Mostrar seção "Sobre Nós"') }}
                            </label>
                        </div>

                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="mostrar_servicos" 
                                   name="secoes[]" 
                                   value="servicos"
                                   {{ ($configuracoes['secoes']['servicos'] ?? false) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="mostrar_servicos" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-hands-helping mr-2"></i>{{ __('Mostrar seção "Nossos Serviços"') }}
                            </label>
                        </div>

                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="mostrar_eventos" 
                                   name="secoes[]" 
                                   value="eventos"
                                   {{ ($configuracoes['secoes']['eventos'] ?? false) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="mostrar_eventos" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-calendar-alt mr-2"></i>{{ __('Mostrar seção "Eventos"') }}
                            </label>
                        </div>

                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="mostrar_contato" 
                                   name="secoes[]" 
                                   value="contato"
                                   {{ ($configuracoes['secoes']['contato'] ?? false) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="mostrar_contato" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-phone mr-2"></i>{{ __('Mostrar seção "Contato"') }}
                            </label>
                        </div>

                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="mostrar_doacao" 
                                   name="secoes[]" 
                                   value="doacao"
                                   {{ ($configuracoes['secoes']['doacao'] ?? false) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="mostrar_doacao" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-heart mr-2"></i>{{ __('Mostrar seção "Doação Online"') }}
                            </label>
                        </div>

                                <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                            <input type="checkbox" 
                                   id="secao_aniversariantes_ativa" 
                                   name="secao_aniversariantes_ativa" 
                                   value="1"
                                   {{ ($configuracoes['secao_aniversariantes_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updatePreview()">
                                    <label for="secao_aniversariantes_ativa" class="ml-3 text-sm font-medium text-blue-900">
                                        <i class="fas fa-birthday-cake mr-2"></i>{{ __('Mostrar seção "Aniversariantes do Mês"') }}
                            </label>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações de Aniversariantes -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-birthday-cake text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-green-900">Configurações de Aniversariantes</h4>
                                    <p class="text-sm text-green-600">Controle a exibição dos aniversariantes</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-white rounded-xl border border-green-200">
                            <input type="checkbox" 
                                   id="aniversariantes_mostrar" 
                                   name="aniversariantes_mostrar" 
                                   value="1"
                                   {{ ($configuracoes['aniversariantes_mostrar'] ?? '1') == '1' ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="updatePreview()">
                                <label for="aniversariantes_mostrar" class="ml-3 text-sm font-medium text-green-900">
                                    <i class="fas fa-toggle-on mr-2"></i>{{ __('Ativar exibição de aniversariantes') }}
                            </label>
                            </div>
                        </div>

                        <!-- Preview das Seções -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview das Seções</h4>
                                    <p class="text-sm text-purple-600">Visualize como as seções aparecerão na página inicial</p>
                                </div>
                            </div>
                            
                            <div id="secoes-preview" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Preview será gerado dinamicamente via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Horários -->
                <div id="tab-horarios" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Culto Domingo Manhã -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-sun text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-blue-900">Culto de Domingo - Manhã</h4>
                                    <p class="text-sm text-blue-600">Configure o culto matutino de domingo</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="culto_domingo_manha_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_titulo" 
                                           name="culto_domingo_manha_titulo" 
                                           value="{{ old('culto_domingo_manha_titulo', $configuracoes['culto_domingo_manha_titulo'] ?? 'Culto de Domingo - Manhã') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_manha_horario" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>{{ __('Horário') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_horario" 
                                           name="culto_domingo_manha_horario" 
                                           value="{{ old('culto_domingo_manha_horario', $configuracoes['culto_domingo_manha_horario'] ?? '09:00h') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="culto_domingo_manha_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_descricao" 
                                           name="culto_domingo_manha_descricao" 
                                           value="{{ old('culto_domingo_manha_descricao', $configuracoes['culto_domingo_manha_descricao'] ?? 'Culto de adoração e pregação da Palavra de Deus') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_manha_item1" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 1') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_item1" 
                                           name="culto_domingo_manha_item1" 
                                           value="{{ old('culto_domingo_manha_item1', $configuracoes['culto_domingo_manha_item1'] ?? 'Louvor e Adoração') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_manha_item2" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 2') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_item2" 
                                           name="culto_domingo_manha_item2" 
                                           value="{{ old('culto_domingo_manha_item2', $configuracoes['culto_domingo_manha_item2'] ?? 'Pregação da Palavra') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_manha_item3" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 3') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_manha_item3" 
                                           name="culto_domingo_manha_item3" 
                                           value="{{ old('culto_domingo_manha_item3', $configuracoes['culto_domingo_manha_item3'] ?? 'Oração e Intercessão') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Culto Domingo Noite -->
                        <div class="border border-indigo-200 rounded-lg p-6 bg-gradient-to-br from-indigo-50 to-purple-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-moon text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-indigo-900">Culto de Domingo - Noite</h4>
                                    <p class="text-sm text-indigo-600">Configure o culto noturno de domingo</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="culto_domingo_noite_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_titulo" 
                                           name="culto_domingo_noite_titulo" 
                                           value="{{ old('culto_domingo_noite_titulo', $configuracoes['culto_domingo_noite_titulo'] ?? 'Culto de Domingo - Noite') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_noite_horario" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>{{ __('Horário') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_horario" 
                                           name="culto_domingo_noite_horario" 
                                           value="{{ old('culto_domingo_noite_horario', $configuracoes['culto_domingo_noite_horario'] ?? '18:00h') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="culto_domingo_noite_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_descricao" 
                                           name="culto_domingo_noite_descricao" 
                                           value="{{ old('culto_domingo_noite_descricao', $configuracoes['culto_domingo_noite_descricao'] ?? 'Culto de celebração e edificação espiritual') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_noite_item1" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 1') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_item1" 
                                           name="culto_domingo_noite_item1" 
                                           value="{{ old('culto_domingo_noite_item1', $configuracoes['culto_domingo_noite_item1'] ?? 'Louvor e Adoração') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_noite_item2" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 2') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_item2" 
                                           name="culto_domingo_noite_item2" 
                                           value="{{ old('culto_domingo_noite_item2', $configuracoes['culto_domingo_noite_item2'] ?? 'Pregação da Palavra') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_domingo_noite_item3" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 3') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_domingo_noite_item3" 
                                           name="culto_domingo_noite_item3" 
                                           value="{{ old('culto_domingo_noite_item3', $configuracoes['culto_domingo_noite_item3'] ?? 'Oração e Intercessão') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Culto Quarta -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-pray text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-green-900">Culto de Quarta-feira</h4>
                                    <p class="text-sm text-green-600">Configure o culto de oração de quarta-feira</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="culto_quarta_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_titulo" 
                                           name="culto_quarta_titulo" 
                                           value="{{ old('culto_quarta_titulo', $configuracoes['culto_quarta_titulo'] ?? 'Culto de Quarta-feira') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_quarta_horario" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>{{ __('Horário') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_horario" 
                                           name="culto_quarta_horario" 
                                           value="{{ old('culto_quarta_horario', $configuracoes['culto_quarta_horario'] ?? '19:30h') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="culto_quarta_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_descricao" 
                                           name="culto_quarta_descricao" 
                                           value="{{ old('culto_quarta_descricao', $configuracoes['culto_quarta_descricao'] ?? 'Culto de oração e estudo bíblico') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_quarta_item1" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 1') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_item1" 
                                           name="culto_quarta_item1" 
                                           value="{{ old('culto_quarta_item1', $configuracoes['culto_quarta_item1'] ?? 'Oração e Intercessão') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_quarta_item2" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 2') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_item2" 
                                           name="culto_quarta_item2" 
                                           value="{{ old('culto_quarta_item2', $configuracoes['culto_quarta_item2'] ?? 'Estudo Bíblico') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                                <div>
                                    <label for="culto_quarta_item3" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1"></i>{{ __('Item 3') }}
                                    </label>
                                    <input type="text" 
                                           id="culto_quarta_item3" 
                                           name="culto_quarta_item3" 
                                           value="{{ old('culto_quarta_item3', $configuracoes['culto_quarta_item3'] ?? 'Comunhão') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           oninput="updateHorariosPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Escola Dominical -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Escola Dominical</h4>
                                    <p class="text-sm text-purple-600">Configure os horários e informações da Escola Dominical</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="escola_dominical_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título da Escola Dominical') }}
                                    </label>
                                    <input type="text" 
                                           id="escola_dominical_titulo" 
                                           name="escola_dominical_titulo" 
                                           value="{{ old('escola_dominical_titulo', $configuracoes['escola_dominical_titulo'] ?? 'Escola Dominical') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="Ex: Escola Dominical">
                                </div>
                                <div>
                                    <label for="escola_dominical_horario" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>{{ __('Horário da Escola Dominical') }}
                                    </label>
                                    <input type="text" 
                                           id="escola_dominical_horario" 
                                           name="escola_dominical_horario" 
                                           value="{{ old('escola_dominical_horario', $configuracoes['escola_dominical_horario'] ?? 'Domingo às 08:00h') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="Ex: Domingo às 08:00h">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="escola_dominical_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Descrição da Escola Dominical') }}
                                    </label>
                                    <textarea id="escola_dominical_descricao" 
                                              name="escola_dominical_descricao" 
                                              rows="4"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 resize-none"
                                              placeholder="Descrição detalhada sobre a Escola Dominical...">{{ old('escola_dominical_descricao', $configuracoes['escola_dominical_descricao'] ?? 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.') }}</textarea>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-users mr-1"></i>{{ __('Classes da Escola Dominical') }}
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="escola_dominical_classe1" class="block text-xs font-medium text-gray-600 mb-2">
                                                {{ __('Classe 1') }}
                                            </label>
                                            <input type="text" 
                                                   id="escola_dominical_classe1" 
                                                   name="escola_dominical_classe1" 
                                                   value="{{ old('escola_dominical_classe1', $configuracoes['escola_dominical_classe1'] ?? 'Classes Infantis') }}"
                                                   class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   placeholder="Ex: Classes Infantis">
                                        </div>
                                        <div>
                                            <label for="escola_dominical_classe2" class="block text-xs font-medium text-gray-600 mb-2">
                                                {{ __('Classe 2') }}
                                            </label>
                                            <input type="text" 
                                                   id="escola_dominical_classe2" 
                                                   name="escola_dominical_classe2" 
                                                   value="{{ old('escola_dominical_classe2', $configuracoes['escola_dominical_classe2'] ?? 'Classes de Jovens') }}"
                                                   class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   placeholder="Ex: Classes de Jovens">
                                        </div>
                                        <div>
                                            <label for="escola_dominical_classe3" class="block text-xs font-medium text-gray-600 mb-2">
                                                {{ __('Classe 3') }}
                                            </label>
                                            <input type="text" 
                                                   id="escola_dominical_classe3" 
                                                   name="escola_dominical_classe3" 
                                                   value="{{ old('escola_dominical_classe3', $configuracoes['escola_dominical_classe3'] ?? 'Classes de Adultos') }}"
                                                   class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   placeholder="Ex: Classes de Adultos">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <div class="flex items-center p-4 bg-purple-50 rounded-xl border border-purple-200">
                                        <input type="checkbox" 
                                               id="escola_dominical_ativa" 
                                               name="escola_dominical_ativa" 
                                               value="1"
                                               {{ ($configuracoes['escola_dominical_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <label for="escola_dominical_ativa" class="ml-3 text-sm font-medium text-purple-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Ativar seção da Escola Dominical na página inicial') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Doação -->
                <div id="tab-doacao" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Informações Importantes -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-info-circle text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-blue-900">Informações Importantes</h4>
                                    <p class="text-sm text-blue-700">
                                        As configurações técnicas de doação (gateways, valores, ativação) estão disponíveis em 
                                    <a href="{{ route('admin.system.settings.index') }}#tab-pagamento" class="underline font-semibold">Configurações do Sistema > Aba Pagamento</a>
                                </p>
                            </div>
                        </div>
                        </div>

                        <!-- Configurações de Texto da Doação -->
                        <div class="border border-red-200 rounded-lg p-6 bg-gradient-to-br from-red-50 to-pink-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-heart text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-red-900">Configurações de Texto da Doação</h4>
                                    <p class="text-sm text-red-600">Personalize os textos da seção de doação</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                    <label for="doacao_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título da Seção Doação') }}
                                </label>
                                <input type="text" 
                                       id="doacao_titulo" 
                                       name="doacao_titulo" 
                                       value="{{ old('doacao_titulo', $configuracoes['doacao_titulo'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                           placeholder="{{ __('Faça sua Doação') }}"
                                           oninput="updateDoacaoPreview()">
                            </div>

                            <div>
                                    <label for="doacao_subtitulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Subtítulo da Seção Doação') }}
                                </label>
                                <input type="text" 
                                       id="doacao_subtitulo" 
                                       name="doacao_subtitulo" 
                                       value="{{ old('doacao_subtitulo', $configuracoes['doacao_subtitulo'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                           placeholder="{{ __('Contribua para a obra de Deus') }}"
                                           oninput="updateDoacaoPreview()">
                            </div>

                            <div>
                                    <label for="doacao_dica_seguranca" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-shield-alt mr-1"></i>{{ __('Dica sobre Segurança') }}
                                </label>
                                <input type="text" 
                                       id="doacao_dica_seguranca" 
                                       name="doacao_dica_seguranca" 
                                       value="{{ old('doacao_dica_seguranca', $configuracoes['doacao_dica_seguranca'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                           placeholder="{{ __('Seus dados estão protegidos com criptografia SSL') }}"
                                           oninput="updateDoacaoPreview()">
                            </div>

                            <div>
                                    <label for="doacao_dica_comprovante" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-receipt mr-1"></i>{{ __('Dica sobre Comprovante') }}
                                </label>
                                <input type="text" 
                                       id="doacao_dica_comprovante" 
                                       name="doacao_dica_comprovante" 
                                       value="{{ old('doacao_dica_comprovante', $configuracoes['doacao_dica_comprovante'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                           placeholder="{{ __('Receba um comprovante por email após a confirmação') }}"
                                           oninput="updateDoacaoPreview()">
                            </div>

                                <div class="md:col-span-2">
                                    <label for="doacao_dica_transparencia" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-eye mr-1"></i>{{ __('Dica sobre Transparência') }}
                                </label>
                                <input type="text" 
                                       id="doacao_dica_transparencia" 
                                       name="doacao_dica_transparencia" 
                                       value="{{ old('doacao_dica_transparencia', $configuracoes['doacao_dica_transparencia'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                           placeholder="{{ __('Todas as doações são registradas e auditadas') }}"
                                           oninput="updateDoacaoPreview()">
                                </div>
                            </div>
                        </div>

                        <!-- Configurações dos Cards de Doação -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-donate text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-green-900">Cards de Tipos de Doação</h4>
                                    <p class="text-sm text-green-600">Configure os três cards de tipos de doação</p>
                                </div>
                            </div>

                            <!-- Card Dízimo -->
                            <div class="bg-white rounded-xl p-6 mb-6 border border-green-200">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-church text-green-600 mr-2"></i>
                                    Card Dízimo
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="doacao_dizimo_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_dizimo_titulo" 
                                               name="doacao_dizimo_titulo" 
                                               value="{{ old('doacao_dizimo_titulo', $configuracoes['doacao_dizimo_titulo'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="{{ __('Dízimo') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_dizimo_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_dizimo_descricao" 
                                               name="doacao_dizimo_descricao" 
                                               value="{{ old('doacao_dizimo_descricao', $configuracoes['doacao_dizimo_descricao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="{{ __('Contribua com o dízimo para a manutenção da igreja.') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_dizimo_botao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-mouse-pointer mr-1"></i>{{ __('Texto do Botão') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_dizimo_botao" 
                                               name="doacao_dizimo_botao" 
                                               value="{{ old('doacao_dizimo_botao', $configuracoes['doacao_dizimo_botao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="{{ __('Doar Dízimo') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                </div>
                            </div>

                            <!-- Card Oferta -->
                            <div class="bg-white rounded-xl p-6 mb-6 border border-blue-200">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-heart text-blue-600 mr-2"></i>
                                    Card Oferta
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="doacao_oferta_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_oferta_titulo" 
                                               name="doacao_oferta_titulo" 
                                               value="{{ old('doacao_oferta_titulo', $configuracoes['doacao_oferta_titulo'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               placeholder="{{ __('Oferta') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_oferta_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_oferta_descricao" 
                                               name="doacao_oferta_descricao" 
                                               value="{{ old('doacao_oferta_descricao', $configuracoes['doacao_oferta_descricao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               placeholder="{{ __('Ofereça com amor para as necessidades da igreja.') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_oferta_botao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-mouse-pointer mr-1"></i>{{ __('Texto do Botão') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_oferta_botao" 
                                               name="doacao_oferta_botao" 
                                               value="{{ old('doacao_oferta_botao', $configuracoes['doacao_oferta_botao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               placeholder="{{ __('Fazer Oferta') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                </div>
                            </div>

                            <!-- Card Campanhas -->
                            <div class="bg-white rounded-xl p-6 border border-purple-200">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-hands-helping text-purple-600 mr-2"></i>
                                    Card Campanhas
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="doacao_campanhas_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-heading mr-1"></i>{{ __('Título') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_campanhas_titulo" 
                                               name="doacao_campanhas_titulo" 
                                               value="{{ old('doacao_campanhas_titulo', $configuracoes['doacao_campanhas_titulo'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                               placeholder="{{ __('Campanhas') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_campanhas_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-align-left mr-1"></i>{{ __('Descrição') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_campanhas_descricao" 
                                               name="doacao_campanhas_descricao" 
                                               value="{{ old('doacao_campanhas_descricao', $configuracoes['doacao_campanhas_descricao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                               placeholder="{{ __('Participe de nossas campanhas especiais.') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                    <div>
                                        <label for="doacao_campanhas_botao" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-mouse-pointer mr-1"></i>{{ __('Texto do Botão') }}
                                        </label>
                                        <input type="text" 
                                               id="doacao_campanhas_botao" 
                                               name="doacao_campanhas_botao" 
                                               value="{{ old('doacao_campanhas_botao', $configuracoes['doacao_campanhas_botao'] ?? '') }}"
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                               placeholder="{{ __('Ver Campanhas') }}"
                                               oninput="updateDoacaoCardsPreview()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview da Doação -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview da Doação</h4>
                                    <p class="text-sm text-purple-600">Visualize como a seção de doação aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="doacao-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-heart text-white text-2xl"></i>
                                    </div>
                                    <h2 id="preview-doacao-titulo" class="text-2xl font-bold text-gray-900 mb-2">
                                        {{ $configuracoes['doacao_titulo'] ?? 'Faça sua Doação' }}
                                    </h2>
                                    <p id="preview-doacao-subtitulo" class="text-gray-600 mb-6">
                                        {{ $configuracoes['doacao_subtitulo'] ?? 'Contribua para a obra de Deus' }}
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-shield-alt text-white"></i>
                                            </div>
                                            <p id="preview-doacao-seguranca" class="text-sm text-gray-700">
                                                {{ $configuracoes['doacao_dica_seguranca'] ?? 'Seus dados estão protegidos com criptografia SSL' }}
                                            </p>
                                        </div>
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-receipt text-white"></i>
                                            </div>
                                            <p id="preview-doacao-comprovante" class="text-sm text-gray-700">
                                                {{ $configuracoes['doacao_dica_comprovante'] ?? 'Receba um comprovante por email após a confirmação' }}
                                            </p>
                                        </div>
                                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-4 rounded-lg border border-purple-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-eye text-white"></i>
                                            </div>
                                            <p id="preview-doacao-transparencia" class="text-sm text-gray-700">
                                                {{ $configuracoes['doacao_dica_transparencia'] ?? 'Todas as doações são registradas e auditadas' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <button class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                                        Fazer Doação
                                    </button>
                                    
                                    <!-- Preview dos Cards -->
                                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Card Dízimo -->
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
                                            <div class="w-12 h-12 bg-green-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                                <i class="fas fa-church text-white"></i>
                                            </div>
                                            <h3 id="preview-dizimo-titulo" class="text-lg font-semibold text-gray-900 mb-2 text-center">
                                                {{ $configuracoes['doacao_dizimo_titulo'] ?? 'Dízimo' }}
                                            </h3>
                                            <p id="preview-dizimo-descricao" class="text-sm text-gray-600 mb-4 text-center">
                                                {{ $configuracoes['doacao_dizimo_descricao'] ?? 'Contribua com o dízimo para a manutenção da igreja.' }}
                                            </p>
                                            <button id="preview-dizimo-botao" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                {{ $configuracoes['doacao_dizimo_botao'] ?? 'Doar Dízimo' }}
                                            </button>
                                        </div>
                                        
                                        <!-- Card Oferta -->
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                                            <div class="w-12 h-12 bg-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                                <i class="fas fa-heart text-white"></i>
                                            </div>
                                            <h3 id="preview-oferta-titulo" class="text-lg font-semibold text-gray-900 mb-2 text-center">
                                                {{ $configuracoes['doacao_oferta_titulo'] ?? 'Oferta' }}
                                            </h3>
                                            <p id="preview-oferta-descricao" class="text-sm text-gray-600 mb-4 text-center">
                                                {{ $configuracoes['doacao_oferta_descricao'] ?? 'Ofereça com amor para as necessidades da igreja.' }}
                                            </p>
                                            <button id="preview-oferta-botao" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                {{ $configuracoes['doacao_oferta_botao'] ?? 'Fazer Oferta' }}
                                            </button>
                                        </div>
                                        
                                        <!-- Card Campanhas -->
                                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-lg border border-purple-200">
                                            <div class="w-12 h-12 bg-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                                <i class="fas fa-hands-helping text-white"></i>
                                            </div>
                                            <h3 id="preview-campanhas-titulo" class="text-lg font-semibold text-gray-900 mb-2 text-center">
                                                {{ $configuracoes['doacao_campanhas_titulo'] ?? 'Campanhas' }}
                                            </h3>
                                            <p id="preview-campanhas-descricao" class="text-sm text-gray-600 mb-4 text-center">
                                                {{ $configuracoes['doacao_campanhas_descricao'] ?? 'Participe de nossas campanhas especiais.' }}
                                            </p>
                                            <button id="preview-campanhas-botao" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                {{ $configuracoes['doacao_campanhas_botao'] ?? 'Ver Campanhas' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Contato -->
                <div id="tab-contato" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações de Contato -->
                        <div class="border border-teal-200 rounded-lg p-6 bg-gradient-to-br from-teal-50 to-cyan-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-teal-900">Configurações de Contato</h4>
                                    <p class="text-sm text-teal-600">Personalize a seção de contato da página inicial</p>
                                </div>
                            </div>
                            
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                    <label for="contato_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Título da Seção Contato') }}
                            </label>
                            <input type="text" 
                                   id="contato_titulo" 
                                   name="contato_titulo" 
                                   value="{{ old('contato_titulo', $configuracoes['contato_titulo'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                           placeholder="Ex: Entre em Contato"
                                           oninput="updateContatoPreview()">
                        </div>

                        <div>
                                    <label for="contato_subtitulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Subtítulo da Seção Contato') }}
                            </label>
                            <input type="text" 
                                   id="contato_subtitulo" 
                                   name="contato_subtitulo" 
                                   value="{{ old('contato_subtitulo', $configuracoes['contato_subtitulo'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                           placeholder="Ex: Estamos aqui para você"
                                           oninput="updateContatoPreview()">
                        </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-teal-200">
                            <input type="checkbox" 
                                   id="contato_ativa" 
                                   name="contato_ativa" 
                                   value="1"
                                   {{ ($configuracoes['contato_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-teal-600 focus:ring-teal-500"
                                               onchange="updateContatoPreview()">
                                        <label for="contato_ativa" class="ml-3 text-sm font-medium text-teal-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Ativar seção de contato') }}
                            </label>
                                    </div>
                        </div>
                    </div>
                </div>

                        <!-- Preview do Contato -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview do Contato</h4>
                                    <p class="text-sm text-purple-600">Visualize como a seção de contato aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="contato-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-phone text-white text-2xl"></i>
                                    </div>
                                    <h2 id="preview-contato-titulo" class="text-2xl font-bold text-gray-900 mb-2">
                                        {{ $configuracoes['contato_titulo'] ?? 'Entre em Contato' }}
                                    </h2>
                                    <p id="preview-contato-subtitulo" class="text-gray-600 mb-6">
                                        {{ $configuracoes['contato_subtitulo'] ?? 'Estamos aqui para você' }}
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-phone text-white"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 mb-1">Telefone</h3>
                                            <p class="text-sm text-gray-600">(11) 99999-9999</p>
                                        </div>
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-envelope text-white"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                            <p class="text-sm text-gray-600">contato@igreja.com</p>
                                        </div>
                                        <div class="bg-gradient-to-br from-orange-50 to-red-50 p-4 rounded-lg border border-orange-200">
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 mb-1">Endereço</h3>
                                            <p class="text-sm text-gray-600">Rua da Igreja, 123</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <button class="bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                                            Enviar Mensagem
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Aba Header -->
                <div id="tab-header" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações Gerais do Header -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-heading text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-blue-900">Configurações do Header</h4>
                                    <p class="text-sm text-blue-600">Personalize o cabeçalho da página inicial</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="header_logo_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-image mr-1"></i>{{ __('Exibir Logo no Header') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                                        <input type="checkbox" 
                                               id="header_logo_ativa" 
                                               name="header_logo_ativa" 
                                               value="1"
                                               {{ ($configuracoes['header_logo_ativa']->valor ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="header_logo_ativa" class="ml-3 text-sm font-medium text-blue-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar logo no cabeçalho') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="header_nome_igreja_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-church mr-1"></i>{{ __('Exibir Nome da Igreja') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                                        <input type="checkbox" 
                                               id="header_nome_igreja_ativa" 
                                               name="header_nome_igreja_ativa" 
                                               value="1"
                                               {{ ($configuracoes['header_nome_igreja_ativa']->valor ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="header_nome_igreja_ativa" class="ml-3 text-sm font-medium text-blue-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar nome da igreja no header') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="header_slogan_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-quote-left mr-1"></i>{{ __('Exibir Slogan no Header') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                                        <input type="checkbox" 
                                               id="header_slogan_ativa" 
                                               name="header_slogan_ativa" 
                                               value="1"
                                               {{ ($configuracoes['header_slogan_ativa']->valor ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="header_slogan_ativa" class="ml-3 text-sm font-medium text-blue-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar slogan no header') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="header_menu_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-bars mr-1"></i>{{ __('Exibir Menu de Navegação') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-blue-200">
                                        <input type="checkbox" 
                                               id="header_menu_ativa" 
                                               name="header_menu_ativa" 
                                               value="1"
                                               {{ ($configuracoes['header_menu_ativa']->valor ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="header_menu_ativa" class="ml-3 text-sm font-medium text-blue-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar menu de navegação') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Links do Menu -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <h4 class="text-lg font-bold text-green-900 mb-4">
                                <i class="fas fa-link mr-2"></i>{{ __('Links do Menu de Navegação') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="header_link_sobre" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Sobre"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_sobre" 
                                           name="header_link_sobre" 
                                           value="{{ old('header_link_sobre', $configuracoes['header_link_sobre']->valor ?? 'Sobre') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Sobre">
                                </div>
                                
                                <div>
                                    <label for="header_link_ministerios" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Ministérios"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_ministerios" 
                                           name="header_link_ministerios" 
                                           value="{{ old('header_link_ministerios', $configuracoes['header_link_ministerios']->valor ?? 'Ministérios') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Ministérios">
                                </div>
                                
                                <div>
                                    <label for="header_link_cultos" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Cultos"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_cultos" 
                                           name="header_link_cultos" 
                                           value="{{ old('header_link_cultos', $configuracoes['header_link_cultos']->valor ?? 'Cultos') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Cultos">
                                </div>
                                
                                <div>
                                    <label for="header_link_aniversariantes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Aniversariantes"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_aniversariantes" 
                                           name="header_link_aniversariantes" 
                                           value="{{ old('header_link_aniversariantes', $configuracoes['header_link_aniversariantes']->valor ?? 'Aniversariantes') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Aniversariantes">
                                </div>
                                
                                <div>
                                    <label for="header_link_eventos" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Eventos"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_eventos" 
                                           name="header_link_eventos" 
                                           value="{{ old('header_link_eventos', $configuracoes['header_link_eventos']->valor ?? 'Eventos') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Eventos">
                                </div>
                                
                                <div>
                                    <label for="header_link_doacao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Doação"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_doacao" 
                                           name="header_link_doacao" 
                                           value="{{ old('header_link_doacao', $configuracoes['header_link_doacao']->valor ?? 'Doação') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Doação">
                                </div>
                                
                                <div>
                                    <label for="header_link_contato" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Contato"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_link_contato" 
                                           name="header_link_contato" 
                                           value="{{ old('header_link_contato', $configuracoes['header_link_contato']->valor ?? 'Contato') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Contato">
                                </div>
                            </div>
                        </div>

                        <!-- Área do Usuário -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <h4 class="text-lg font-bold text-purple-900 mb-4">
                                <i class="fas fa-user mr-2"></i>{{ __('Área do Usuário no Header') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="header_area_usuario_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Exibir Área do Usuário') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-purple-200">
                                        <input type="checkbox" 
                                               id="header_area_usuario_ativa" 
                                               name="header_area_usuario_ativa" 
                                               value="1"
                                               {{ ($configuracoes['header_area_usuario_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <label for="header_area_usuario_ativa" class="ml-3 text-sm font-medium text-purple-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar área do usuário no header') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="header_texto_area_membro" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Texto do Botão "Área do Membro"') }}
                                    </label>
                                    <input type="text" 
                                           id="header_texto_area_membro" 
                                           name="header_texto_area_membro" 
                                           value="{{ old('header_texto_area_membro', $configuracoes['header_texto_area_membro']->valor ?? 'Área do Membro') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="Ex: Área do Membro">
                                </div>
                            </div>
                        </div>

                        <!-- Preview do Header -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview do Header</h4>
                                    <p class="text-sm text-purple-600">Visualize como o cabeçalho aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="header-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg mr-3 flex items-center justify-center">
                                            <i class="fas fa-church text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">Nome da Igreja</h3>
                                            <p class="text-sm text-gray-600">Slogan da Igreja</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Sobre</a>
                                        <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Ministérios</a>
                                        <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Cultos</a>
                                        <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Eventos</a>
                                        <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Contato</a>
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Área do Membro</button>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-2">Configurações Ativas:</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Logo</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Nome da Igreja</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Slogan</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Menu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Footer -->
                <div id="tab-footer" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações Gerais do Footer -->
                        <div class="border border-orange-200 rounded-lg p-6 bg-gradient-to-br from-orange-50 to-red-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-shoe-prints text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-orange-900">Configurações do Footer</h4>
                                    <p class="text-sm text-orange-600">Personalize o rodapé da página inicial</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-toggle-on mr-1"></i>{{ __('Ativar Footer') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-orange-200">
                                        <input type="checkbox" 
                                               id="footer_ativa" 
                                               name="footer_ativa" 
                                               value="1"
                                               {{ ($configuracoes['footer_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                        <label for="footer_ativa" class="ml-3 text-sm font-medium text-orange-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Exibir footer na página inicial') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="footer_descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Descrição do Footer') }}
                                    </label>
                                    <textarea id="footer_descricao" 
                                              name="footer_descricao" 
                                              rows="3"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                                              placeholder="Descrição da igreja para o footer...">{{ old('footer_descricao', $configuracoes['footer_descricao'] ?? 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Seções do Footer -->
                        <div class="border border-blue-200 rounded-lg p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <h4 class="text-lg font-bold text-blue-900 mb-4">
                                <i class="fas fa-th-large mr-2"></i>{{ __('Seções do Footer') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_links_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Título da Seção "Links Rápidos"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_links_titulo" 
                                           name="footer_links_titulo" 
                                           value="{{ old('footer_links_titulo', $configuracoes['footer_links_titulo'] ?? 'Links Rápidos') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           placeholder="Ex: Links Rápidos">
                                </div>
                                
                                <div>
                                    <label for="footer_contato_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Título da Seção "Contato"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_contato_titulo" 
                                           name="footer_contato_titulo" 
                                           value="{{ old('footer_contato_titulo', $configuracoes['footer_contato_titulo'] ?? 'Contato') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           placeholder="Ex: Contato">
                                </div>
                                
                                <div>
                                    <label for="footer_horarios_titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Título da Seção "Horários"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_horarios_titulo" 
                                           name="footer_horarios_titulo" 
                                           value="{{ old('footer_horarios_titulo', $configuracoes['footer_horarios_titulo'] ?? 'Horários') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           placeholder="Ex: Horários">
                                </div>
                                
                                <div>
                                    <label for="footer_copyright_texto" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Texto do Copyright') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_copyright_texto" 
                                           name="footer_copyright_texto" 
                                           value="{{ old('footer_copyright_texto', $configuracoes['footer_copyright_texto'] ?? 'Todos os direitos reservados.') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           placeholder="Ex: Todos os direitos reservados.">
                                </div>
                            </div>
                        </div>

                        <!-- Links do Footer -->
                        <div class="border border-green-200 rounded-lg p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                            <h4 class="text-lg font-bold text-green-900 mb-4">
                                <i class="fas fa-link mr-2"></i>{{ __('Links do Footer') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_link_sobre" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Sobre"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_sobre" 
                                           name="footer_link_sobre" 
                                           value="{{ old('footer_link_sobre', $configuracoes['footer_link_sobre'] ?? 'Sobre') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Sobre">
                                </div>
                                
                                <div>
                                    <label for="footer_link_ministerios" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Ministérios"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_ministerios" 
                                           name="footer_link_ministerios" 
                                           value="{{ old('footer_link_ministerios', $configuracoes['footer_link_ministerios'] ?? 'Ministérios') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Ministérios">
                                </div>
                                
                                <div>
                                    <label for="footer_link_cultos" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Cultos"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_cultos" 
                                           name="footer_link_cultos" 
                                           value="{{ old('footer_link_cultos', $configuracoes['footer_link_cultos'] ?? 'Cultos') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Cultos">
                                </div>
                                
                                <div>
                                    <label for="footer_link_eventos" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Eventos"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_eventos" 
                                           name="footer_link_eventos" 
                                           value="{{ old('footer_link_eventos', $configuracoes['footer_link_eventos'] ?? 'Eventos') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Eventos">
                                </div>
                                
                                <div>
                                    <label for="footer_link_aniversariantes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Aniversariantes"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_aniversariantes" 
                                           name="footer_link_aniversariantes" 
                                           value="{{ old('footer_link_aniversariantes', $configuracoes['footer_link_aniversariantes'] ?? 'Aniversariantes') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Aniversariantes">
                                </div>
                                
                                <div>
                                    <label for="footer_link_doacao" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Link "Doação"') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_doacao" 
                                           name="footer_link_doacao" 
                                           value="{{ old('footer_link_doacao', $configuracoes['footer_link_doacao'] ?? 'Doação') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                           placeholder="Ex: Doação">
                                </div>
                            </div>
                        </div>

                        <!-- Redes Sociais -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <h4 class="text-lg font-bold text-purple-900 mb-4">
                                <i class="fas fa-share-alt mr-2"></i>{{ __('Redes Sociais') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_redes_sociais_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Exibir Redes Sociais no Footer') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-purple-200">
                                        <input type="checkbox" 
                                               id="footer_redes_sociais_ativa" 
                                               name="footer_redes_sociais_ativa" 
                                               value="1"
                                               {{ ($configuracoes['footer_redes_sociais_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <label for="footer_redes_sociais_ativa" class="ml-3 text-sm font-medium text-purple-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar redes sociais no footer') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="igreja_instagram" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fab fa-instagram mr-1"></i>{{ __('Instagram') }}
                                    </label>
                                    <input type="text" 
                                           id="igreja_instagram" 
                                           name="igreja_instagram" 
                                           value="{{ old('igreja_instagram', $configuracoes['igreja_instagram'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="https://instagram.com/suaigreja">
                                </div>
                                
                                <div>
                                    <label for="igreja_youtube" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fab fa-youtube mr-1"></i>{{ __('YouTube') }}
                                    </label>
                                    <input type="text" 
                                           id="igreja_youtube" 
                                           name="igreja_youtube" 
                                           value="{{ old('igreja_youtube', $configuracoes['igreja_youtube'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="https://youtube.com/suaigreja">
                                </div>
                                
                                <div>
                                    <label for="igreja_whatsapp" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fab fa-whatsapp mr-1"></i>{{ __('WhatsApp') }}
                                    </label>
                                    <input type="text" 
                                           id="igreja_whatsapp" 
                                           name="igreja_whatsapp" 
                                           value="{{ old('igreja_whatsapp', $configuracoes['igreja_whatsapp'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                           placeholder="(11) 99999-9999">
                                </div>
                            </div>
                        </div>

                        <!-- Links do Sistema -->
                        <div class="border border-gray-200 rounded-lg p-6 bg-gradient-to-br from-gray-50 to-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-cog mr-2"></i>{{ __('Links do Sistema') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_link_creditos_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Exibir Link dos Créditos') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-gray-200">
                                        <input type="checkbox" 
                                               id="footer_link_creditos_ativa" 
                                               name="footer_link_creditos_ativa" 
                                               value="1"
                                               {{ ($configuracoes['footer_link_creditos_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-gray-600 focus:ring-gray-500">
                                        <label for="footer_link_creditos_ativa" class="ml-3 text-sm font-medium text-gray-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar link dos créditos') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="footer_link_creditos_texto" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Texto do Link dos Créditos') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_creditos_texto" 
                                           name="footer_link_creditos_texto" 
                                           value="{{ old('footer_link_creditos_texto', $configuracoes['footer_link_creditos_texto'] ?? 'Reinan Rodrigues') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200"
                                           placeholder="Ex: Reinan Rodrigues">
                                </div>
                                
                                <div>
                                    <label for="footer_link_vertex_ativa" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Exibir Link da Vertex Solutions') }}
                                    </label>
                                    <div class="flex items-center p-4 bg-white rounded-xl border border-gray-200">
                                        <input type="checkbox" 
                                               id="footer_link_vertex_ativa" 
                                               name="footer_link_vertex_ativa" 
                                               value="1"
                                               {{ ($configuracoes['footer_link_vertex_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-gray-600 focus:ring-gray-500">
                                        <label for="footer_link_vertex_ativa" class="ml-3 text-sm font-medium text-gray-900">
                                            <i class="fas fa-toggle-on mr-1"></i>{{ __('Mostrar link da Vertex Solutions') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="footer_link_vertex_texto" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('Texto do Link da Vertex Solutions') }}
                                    </label>
                                    <input type="text" 
                                           id="footer_link_vertex_texto" 
                                           name="footer_link_vertex_texto" 
                                           value="{{ old('footer_link_vertex_texto', $configuracoes['footer_link_vertex_texto'] ?? 'Vertex Solutions') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200"
                                           placeholder="Ex: Vertex Solutions">
                                </div>
                            </div>
                        </div>

                        <!-- Preview do Footer -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview do Footer</h4>
                                    <p class="text-sm text-purple-600">Visualize como o rodapé aparecerá</p>
                                </div>
                            </div>
                            
                            <div id="footer-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-3">Sobre Nossa Igreja</h3>
                                        <p class="text-sm text-gray-600">
                                            Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-3">Links Rápidos</h3>
                                        <ul class="space-y-2 text-sm">
                                            <li><a href="#" class="text-gray-600 hover:text-blue-600">Sobre</a></li>
                                            <li><a href="#" class="text-gray-600 hover:text-blue-600">Ministérios</a></li>
                                            <li><a href="#" class="text-gray-600 hover:text-blue-600">Cultos</a></li>
                                            <li><a href="#" class="text-gray-600 hover:text-blue-600">Eventos</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-3">Contato</h3>
                                        <ul class="space-y-2 text-sm">
                                            <li class="text-gray-600">(11) 99999-9999</li>
                                            <li class="text-gray-600">contato@igreja.com</li>
                                            <li class="text-gray-600">Rua da Igreja, 123</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-3">Redes Sociais</h3>
                                        <div class="flex space-x-3">
                                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                                <i class="fab fa-facebook text-xl"></i>
                                            </a>
                                            <a href="#" class="text-pink-600 hover:text-pink-800">
                                                <i class="fab fa-instagram text-xl"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-800">
                                                <i class="fab fa-youtube text-xl"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center text-sm text-gray-600">
                                        <p>&copy; 2024 Nossa Igreja. Todos os direitos reservados.</p>
                                        <div class="flex space-x-4">
                                            <a href="#" class="hover:text-blue-600">Reinan Rodrigues</a>
                                            <a href="#" class="hover:text-blue-600">Vertex Solutions</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg mt-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Configurações Ativas:</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Footer Ativo</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Redes Sociais</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Links do Sistema</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-gray-600">Descrição</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba SEO -->
                <div id="tab-seo" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Configurações de SEO -->
                        <div class="border border-yellow-200 rounded-lg p-6 bg-gradient-to-br from-yellow-50 to-orange-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-search text-white text-xl"></i>
                                </div>
                        <div>
                                    <h4 class="text-xl font-bold text-yellow-900">Configurações de SEO</h4>
                                    <p class="text-sm text-yellow-600">Otimize sua página para mecanismos de busca</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="meta_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>{{ __('Meta Title') }}
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title', $configuracoes['meta_title'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                                           placeholder="Ex: Igreja - Uma comunidade de fé e amor"
                                           oninput="updateSeoPreview()">
                                    <p class="text-xs text-gray-500 mt-1">Máximo 60 caracteres recomendado</p>
                        </div>

                        <div>
                                    <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1"></i>{{ __('Meta Description') }}
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      rows="3"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 resize-none"
                                              placeholder="Descrição da sua igreja para mecanismos de busca..."
                                              oninput="updateSeoPreview()">{{ old('meta_description', $configuracoes['meta_description'] ?? '') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Máximo 160 caracteres recomendado</p>
                        </div>

                        <div>
                                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tags mr-1"></i>{{ __('Meta Keywords') }}
                            </label>
                            <input type="text" 
                                   id="meta_keywords" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords', $configuracoes['meta_keywords'] ?? '') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                                           placeholder="{{ __('igreja, comunidade, fé, amor, culto') }}"
                                           oninput="updateSeoPreview()">
                                    <p class="text-xs text-gray-500 mt-1">Separe as palavras-chave com vírgulas</p>
                        </div>
                    </div>
                </div>

                        <!-- Preview do SEO -->
                        <div class="border border-purple-200 rounded-lg p-6 bg-gradient-to-br from-purple-50 to-violet-50">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-purple-900">Preview do SEO</h4>
                                    <p class="text-sm text-purple-600">Visualize como aparecerá nos resultados de busca</p>
                                </div>
                            </div>
                            
                            <div id="seo-preview" class="bg-white rounded-xl p-6 shadow-lg border border-purple-200">
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-500">Resultado de busca do Google</span>
                                    </div>
                                    <div class="border-l-4 border-green-500 pl-4">
                                        <h3 id="preview-seo-title" class="text-blue-600 text-lg font-medium mb-1 hover:underline cursor-pointer">
                                            {{ $configuracoes['meta_title'] ?? 'Igreja - Uma comunidade de fé e amor' }}
                                        </h3>
                                        <p class="text-green-600 text-sm mb-1">https://suaigreja.com</p>
                                        <p id="preview-seo-description" class="text-gray-600 text-sm">
                                            {{ $configuracoes['meta_description'] ?? 'Descrição da sua igreja para mecanismos de busca...' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-2">Informações Técnicas:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Meta Title:</span>
                                            <span id="preview-seo-title-count" class="text-gray-600 ml-2">
                                                {{ strlen($configuracoes['meta_title'] ?? '') }}/60 caracteres
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Meta Description:</span>
                                            <span id="preview-seo-description-count" class="text-gray-600 ml-2">
                                                {{ strlen($configuracoes['meta_description'] ?? '') }}/160 caracteres
                                            </span>
                                        </div>
                                        <div class="md:col-span-2">
                                            <span class="font-medium text-gray-700">Meta Keywords:</span>
                                            <span id="preview-seo-keywords" class="text-gray-600 ml-2">
                                                {{ $configuracoes['meta_keywords'] ?? 'Nenhuma palavra-chave definida' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" 
                    onclick="resetarConfiguracao()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                {{ __('Resetar') }}
            </button>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Salvar Configuração') }}
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    /* Estilos para o slider de opacidade */
    .slider {
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        outline: none;
        transition: all 0.3s ease;
    }
    
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .slider::-webkit-slider-thumb:hover {
        background: #2563eb;
        transform: scale(1.1);
    }
    
    .slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .slider::-moz-range-thumb:hover {
        background: #2563eb;
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
function showTab(tabName) {
    // Ocultar todas as abas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remover classe active de todos os botões
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Mostrar aba selecionada
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    
    // Ativar botão selecionado
    event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}

// Função para atualizar preview do Hero
function updateHeroPreview() {
    // Verificar se estamos na aba de conteúdo
    const heroPreview = document.getElementById('hero-preview');
    if (!heroPreview) return;
    
    const titulo = document.getElementById('titulo_principal')?.value || 'Bem-vindo à Nossa Igreja';
    const subtitulo = document.getElementById('subtitulo')?.value || 'Uma comunidade de fé, amor e esperança';
    const descricao = document.getElementById('descricao')?.value || 'Descrição da igreja e sua missão...';
    const botao = document.getElementById('texto_botao')?.value || 'Conheça Nossa Igreja';
    
    const previewTitulo = document.getElementById('preview-titulo');
    const previewSubtitulo = document.getElementById('preview-subtitulo');
    const previewDescricao = document.getElementById('preview-descricao');
    const previewBotao = document.getElementById('preview-botao');
    
    if (previewTitulo) previewTitulo.textContent = titulo;
    if (previewSubtitulo) previewSubtitulo.textContent = subtitulo;
    if (previewDescricao) previewDescricao.textContent = descricao;
    if (previewBotao) previewBotao.textContent = botao;
}

// Função para atualizar preview dos Aniversariantes
function updateAniversariantesPreview() {
    // Verificar se estamos na aba de conteúdo
    const aniversariantesPreview = document.getElementById('aniversariantes-preview');
    if (!aniversariantesPreview) return;
    
    const titulo = document.getElementById('aniversariantes_titulo')?.value || 'Aniversariantes do Mês';
    const subtitulo = document.getElementById('aniversariantes_subtitulo')?.value || 'Celebrando a vida dos nossos membros';
    
    const previewTitulo = document.getElementById('preview-aniversariantes-titulo');
    const previewSubtitulo = document.getElementById('preview-aniversariantes-subtitulo');
    
    if (previewTitulo) previewTitulo.textContent = titulo;
    if (previewSubtitulo) previewSubtitulo.textContent = subtitulo;
}

// Função para atualizar preview das cores
function updateColorPreview() {
    // Verificar se estamos na aba de design
    const colorPreview = document.getElementById('color-preview');
    if (!colorPreview) return;
    
    const corPrimaria = document.getElementById('cor_primaria')?.value || '#1e40af';
    const corSecundaria = document.getElementById('cor_secundaria')?.value || '#3b82f6';
    const corDestaque = document.getElementById('cor_destaque')?.value || '#10b981';
    const corTexto = document.getElementById('cor_texto')?.value || '#1f2937';
    
    // Atualizar preview das cores
    const previewCorPrimaria = document.getElementById('preview-cor-primaria');
    const previewHexPrimaria = document.getElementById('preview-hex-primaria');
    const previewCorSecundaria = document.getElementById('preview-cor-secundaria');
    const previewHexSecundaria = document.getElementById('preview-hex-secundaria');
    const previewCorDestaque = document.getElementById('preview-cor-destaque');
    const previewHexDestaque = document.getElementById('preview-hex-destaque');
    const previewCorTexto = document.getElementById('preview-cor-texto');
    const previewHexTexto = document.getElementById('preview-hex-texto');
    
    if (previewCorPrimaria) previewCorPrimaria.style.backgroundColor = corPrimaria;
    if (previewHexPrimaria) previewHexPrimaria.textContent = corPrimaria;
    
    if (previewCorSecundaria) previewCorSecundaria.style.backgroundColor = corSecundaria;
    if (previewHexSecundaria) previewHexSecundaria.textContent = corSecundaria;
    
    if (previewCorDestaque) previewCorDestaque.style.backgroundColor = corDestaque;
    if (previewHexDestaque) previewHexDestaque.textContent = corDestaque;
    
    if (previewCorTexto) previewCorTexto.style.backgroundColor = corTexto;
    if (previewHexTexto) previewHexTexto.textContent = corTexto;
    
    // Atualizar exemplo de aplicação
    const exemploTitulo = document.querySelector('#color-preview h3');
    const exemploTexto = document.querySelector('#color-preview p');
    const botoes = document.querySelectorAll('#color-preview button');
    
    if (exemploTitulo) exemploTitulo.style.color = corPrimaria;
    if (exemploTexto) exemploTexto.style.color = corTexto;
    if (botoes[0]) botoes[0].style.backgroundColor = corSecundaria;
    if (botoes[1]) botoes[1].style.backgroundColor = corDestaque;
}

// Função para atualizar preview da sobreposição
function updateOverlayPreview() {
    const overlayPreview = document.getElementById('overlay-preview');
    if (!overlayPreview) return;
    
    const opacidade = document.getElementById('hero_overlay_opacidade')?.value || '0.6';
    const cor = document.getElementById('hero_overlay_cor')?.value || '#1e40af';
    const overlayLayer = document.getElementById('overlay-layer');
    const overlayValue = document.getElementById('overlay-value');
    
    if (overlayLayer) {
        overlayLayer.style.backgroundColor = cor;
        overlayLayer.style.opacity = opacidade;
    }
    
    if (overlayValue) {
        overlayValue.textContent = Math.round(opacidade * 100) + '%';
    }
}

// Função para atualizar preview dos horários
function updateHorariosPreview() {
    // Verificar se estamos na aba de horários
    const horariosPreview = document.getElementById('horarios-preview');
    if (!horariosPreview) return;
    
    // Culto Domingo Manhã
    const cultoManhaTitulo = document.getElementById('culto_domingo_manha_titulo')?.value || 'Culto de Domingo - Manhã';
    const cultoManhaHorario = document.getElementById('culto_domingo_manha_horario')?.value || '09:00h';
    const cultoManhaDescricao = document.getElementById('culto_domingo_manha_descricao')?.value || 'Culto de adoração e pregação da Palavra de Deus';
    const cultoManhaItem1 = document.getElementById('culto_domingo_manha_item1')?.value || 'Louvor e Adoração';
    const cultoManhaItem2 = document.getElementById('culto_domingo_manha_item2')?.value || 'Pregação da Palavra';
    const cultoManhaItem3 = document.getElementById('culto_domingo_manha_item3')?.value || 'Oração e Intercessão';
    
    const previewManhaTitulo = document.getElementById('preview-culto-manha-titulo');
    const previewManhaHorario = document.getElementById('preview-culto-manha-horario');
    const previewManhaDescricao = document.getElementById('preview-culto-manha-descricao');
    const previewManhaItem1 = document.getElementById('preview-culto-manha-item1');
    const previewManhaItem2 = document.getElementById('preview-culto-manha-item2');
    const previewManhaItem3 = document.getElementById('preview-culto-manha-item3');
    
    if (previewManhaTitulo) previewManhaTitulo.textContent = cultoManhaTitulo;
    if (previewManhaHorario) previewManhaHorario.textContent = cultoManhaHorario;
    if (previewManhaDescricao) previewManhaDescricao.textContent = cultoManhaDescricao;
    if (previewManhaItem1) previewManhaItem1.textContent = '• ' + cultoManhaItem1;
    if (previewManhaItem2) previewManhaItem2.textContent = '• ' + cultoManhaItem2;
    if (previewManhaItem3) previewManhaItem3.textContent = '• ' + cultoManhaItem3;
    
    // Culto Domingo Noite
    const cultoNoiteTitulo = document.getElementById('culto_domingo_noite_titulo')?.value || 'Culto de Domingo - Noite';
    const cultoNoiteHorario = document.getElementById('culto_domingo_noite_horario')?.value || '18:00h';
    const cultoNoiteDescricao = document.getElementById('culto_domingo_noite_descricao')?.value || 'Culto de celebração e edificação espiritual';
    const cultoNoiteItem1 = document.getElementById('culto_domingo_noite_item1')?.value || 'Louvor e Adoração';
    const cultoNoiteItem2 = document.getElementById('culto_domingo_noite_item2')?.value || 'Pregação da Palavra';
    const cultoNoiteItem3 = document.getElementById('culto_domingo_noite_item3')?.value || 'Oração e Intercessão';
    
    const previewNoiteTitulo = document.getElementById('preview-culto-noite-titulo');
    const previewNoiteHorario = document.getElementById('preview-culto-noite-horario');
    const previewNoiteDescricao = document.getElementById('preview-culto-noite-descricao');
    const previewNoiteItem1 = document.getElementById('preview-culto-noite-item1');
    const previewNoiteItem2 = document.getElementById('preview-culto-noite-item2');
    const previewNoiteItem3 = document.getElementById('preview-culto-noite-item3');
    
    if (previewNoiteTitulo) previewNoiteTitulo.textContent = cultoNoiteTitulo;
    if (previewNoiteHorario) previewNoiteHorario.textContent = cultoNoiteHorario;
    if (previewNoiteDescricao) previewNoiteDescricao.textContent = cultoNoiteDescricao;
    if (previewNoiteItem1) previewNoiteItem1.textContent = '• ' + cultoNoiteItem1;
    if (previewNoiteItem2) previewNoiteItem2.textContent = '• ' + cultoNoiteItem2;
    if (previewNoiteItem3) previewNoiteItem3.textContent = '• ' + cultoNoiteItem3;
    
    // Culto Quarta
    const cultoQuartaTitulo = document.getElementById('culto_quarta_titulo')?.value || 'Culto de Quarta-feira';
    const cultoQuartaHorario = document.getElementById('culto_quarta_horario')?.value || '19:30h';
    const cultoQuartaDescricao = document.getElementById('culto_quarta_descricao')?.value || 'Culto de oração e estudo bíblico';
    const cultoQuartaItem1 = document.getElementById('culto_quarta_item1')?.value || 'Oração e Intercessão';
    const cultoQuartaItem2 = document.getElementById('culto_quarta_item2')?.value || 'Estudo Bíblico';
    const cultoQuartaItem3 = document.getElementById('culto_quarta_item3')?.value || 'Comunhão';
    
    const previewQuartaTitulo = document.getElementById('preview-culto-quarta-titulo');
    const previewQuartaHorario = document.getElementById('preview-culto-quarta-horario');
    const previewQuartaDescricao = document.getElementById('preview-culto-quarta-descricao');
    const previewQuartaItem1 = document.getElementById('preview-culto-quarta-item1');
    const previewQuartaItem2 = document.getElementById('preview-culto-quarta-item2');
    const previewQuartaItem3 = document.getElementById('preview-culto-quarta-item3');
    
    if (previewQuartaTitulo) previewQuartaTitulo.textContent = cultoQuartaTitulo;
    if (previewQuartaHorario) previewQuartaHorario.textContent = cultoQuartaHorario;
    if (previewQuartaDescricao) previewQuartaDescricao.textContent = cultoQuartaDescricao;
    if (previewQuartaItem1) previewQuartaItem1.textContent = '• ' + cultoQuartaItem1;
    if (previewQuartaItem2) previewQuartaItem2.textContent = '• ' + cultoQuartaItem2;
    if (previewQuartaItem3) previewQuartaItem3.textContent = '• ' + cultoQuartaItem3;
    
    // Escola Dominical
    const escolaTitulo = document.getElementById('escola_dominical_titulo')?.value || 'Escola Dominical';
    const escolaHorario = document.getElementById('escola_dominical_horario')?.value || 'Domingo às 08:00h';
    const escolaDescricao = document.getElementById('escola_dominical_descricao')?.value || 'Venha estudar a Bíblia conosco!';
    const escolaClasse1 = document.getElementById('escola_dominical_classe1')?.value || 'Classes Infantis';
    const escolaClasse2 = document.getElementById('escola_dominical_classe2')?.value || 'Classes de Jovens';
    const escolaClasse3 = document.getElementById('escola_dominical_classe3')?.value || 'Classes de Adultos';
    
    const previewEscolaTitulo = document.getElementById('preview-escola-titulo');
    const previewEscolaHorario = document.getElementById('preview-escola-horario');
    const previewEscolaDescricao = document.getElementById('preview-escola-descricao');
    const previewEscolaClasse1 = document.getElementById('preview-escola-classe1');
    const previewEscolaClasse2 = document.getElementById('preview-escola-classe2');
    const previewEscolaClasse3 = document.getElementById('preview-escola-classe3');
    
    if (previewEscolaTitulo) previewEscolaTitulo.textContent = escolaTitulo;
    if (previewEscolaHorario) previewEscolaHorario.textContent = escolaHorario;
    if (previewEscolaDescricao) previewEscolaDescricao.textContent = escolaDescricao;
    if (previewEscolaClasse1) previewEscolaClasse1.textContent = '• ' + escolaClasse1;
    if (previewEscolaClasse2) previewEscolaClasse2.textContent = '• ' + escolaClasse2;
    if (previewEscolaClasse3) previewEscolaClasse3.textContent = '• ' + escolaClasse3;
}

// Função para atualizar preview da doação
function updateDoacaoPreview() {
    // Verificar se estamos na aba de doação
    const doacaoPreview = document.getElementById('doacao-preview');
    if (!doacaoPreview) return;
    
    const titulo = document.getElementById('doacao_titulo')?.value || 'Faça sua Doação';
    const subtitulo = document.getElementById('doacao_subtitulo')?.value || 'Contribua para a obra de Deus';
    const seguranca = document.getElementById('doacao_dica_seguranca')?.value || 'Seus dados estão protegidos com criptografia SSL';
    const comprovante = document.getElementById('doacao_dica_comprovante')?.value || 'Receba um comprovante por email após a confirmação';
    const transparencia = document.getElementById('doacao_dica_transparencia')?.value || 'Todas as doações são registradas e auditadas';
    
    const previewTitulo = document.getElementById('preview-doacao-titulo');
    const previewSubtitulo = document.getElementById('preview-doacao-subtitulo');
    const previewSeguranca = document.getElementById('preview-doacao-seguranca');
    const previewComprovante = document.getElementById('preview-doacao-comprovante');
    const previewTransparencia = document.getElementById('preview-doacao-transparencia');
    
    if (previewTitulo) previewTitulo.textContent = titulo;
    if (previewSubtitulo) previewSubtitulo.textContent = subtitulo;
    if (previewSeguranca) previewSeguranca.textContent = seguranca;
    if (previewComprovante) previewComprovante.textContent = comprovante;
    if (previewTransparencia) previewTransparencia.textContent = transparencia;
}

// Função para atualizar preview dos cards de doação
function updateDoacaoCardsPreview() {
    // Card Dízimo
    const dizimoTitulo = document.getElementById('doacao_dizimo_titulo')?.value || 'Dízimo';
    const dizimoDescricao = document.getElementById('doacao_dizimo_descricao')?.value || 'Contribua com o dízimo para a manutenção da igreja.';
    const dzimoBotao = document.getElementById('doacao_dizimo_botao')?.value || 'Doar Dízimo';
    
    const previewDizimoTitulo = document.getElementById('preview-dizimo-titulo');
    const previewDizimoDescricao = document.getElementById('preview-dizimo-descricao');
    const previewDizimoBotao = document.getElementById('preview-dizimo-botao');
    
    if (previewDizimoTitulo) previewDizimoTitulo.textContent = dizimoTitulo;
    if (previewDizimoDescricao) previewDizimoDescricao.textContent = dizimoDescricao;
    if (previewDizimoBotao) previewDizimoBotao.textContent = dzimoBotao;
    
    // Card Oferta
    const ofertaTitulo = document.getElementById('doacao_oferta_titulo')?.value || 'Oferta';
    const ofertaDescricao = document.getElementById('doacao_oferta_descricao')?.value || 'Ofereça com amor para as necessidades da igreja.';
    const ofertaBotao = document.getElementById('doacao_oferta_botao')?.value || 'Fazer Oferta';
    
    const previewOfertaTitulo = document.getElementById('preview-oferta-titulo');
    const previewOfertaDescricao = document.getElementById('preview-oferta-descricao');
    const previewOfertaBotao = document.getElementById('preview-oferta-botao');
    
    if (previewOfertaTitulo) previewOfertaTitulo.textContent = ofertaTitulo;
    if (previewOfertaDescricao) previewOfertaDescricao.textContent = ofertaDescricao;
    if (previewOfertaBotao) previewOfertaBotao.textContent = ofertaBotao;
    
    // Card Campanhas
    const campanhasTitulo = document.getElementById('doacao_campanhas_titulo')?.value || 'Campanhas';
    const campanhasDescricao = document.getElementById('doacao_campanhas_descricao')?.value || 'Participe de nossas campanhas especiais.';
    const campanhasBotao = document.getElementById('doacao_campanhas_botao')?.value || 'Ver Campanhas';
    
    const previewCampanhasTitulo = document.getElementById('preview-campanhas-titulo');
    const previewCampanhasDescricao = document.getElementById('preview-campanhas-descricao');
    const previewCampanhasBotao = document.getElementById('preview-campanhas-botao');
    
    if (previewCampanhasTitulo) previewCampanhasTitulo.textContent = campanhasTitulo;
    if (previewCampanhasDescricao) previewCampanhasDescricao.textContent = campanhasDescricao;
    if (previewCampanhasBotao) previewCampanhasBotao.textContent = campanhasBotao;
}

// Função para atualizar preview do contato
function updateContatoPreview() {
    // Verificar se estamos na aba de contato
    const contatoPreview = document.getElementById('contato-preview');
    if (!contatoPreview) return;
    
    const titulo = document.getElementById('contato_titulo')?.value || 'Entre em Contato';
    const subtitulo = document.getElementById('contato_subtitulo')?.value || 'Estamos aqui para você';
    
    const previewTitulo = document.getElementById('preview-contato-titulo');
    const previewSubtitulo = document.getElementById('preview-contato-subtitulo');
    
    if (previewTitulo) previewTitulo.textContent = titulo;
    if (previewSubtitulo) previewSubtitulo.textContent = subtitulo;
}

// Função para atualizar preview do SEO
function updateSeoPreview() {
    // Verificar se estamos na aba de SEO
    const seoPreview = document.getElementById('seo-preview');
    if (!seoPreview) return;
    
    const title = document.getElementById('meta_title')?.value || 'Igreja - Uma comunidade de fé e amor';
    const description = document.getElementById('meta_description')?.value || 'Descrição da sua igreja para mecanismos de busca...';
    const keywords = document.getElementById('meta_keywords')?.value || 'Nenhuma palavra-chave definida';
    
    const previewTitle = document.getElementById('preview-seo-title');
    const previewDescription = document.getElementById('preview-seo-description');
    const previewKeywords = document.getElementById('preview-seo-keywords');
    const titleCount = document.getElementById('preview-seo-title-count');
    const descCount = document.getElementById('preview-seo-description-count');
    
    if (previewTitle) previewTitle.textContent = title;
    if (previewDescription) previewDescription.textContent = description;
    if (previewKeywords) previewKeywords.textContent = keywords;
    
    // Atualizar contadores
    if (titleCount) titleCount.textContent = title.length + '/60 caracteres';
    if (descCount) descCount.textContent = description.length + '/160 caracteres';
    
    // Mudar cor do contador se exceder o limite
    if (titleCount) {
        if (title.length > 60) {
            titleCount.className = 'text-red-600 ml-2';
        } else {
            titleCount.className = 'text-gray-600 ml-2';
        }
    }
    
    if (descCount) {
        if (description.length > 160) {
            descCount.className = 'text-red-600 ml-2';
        } else {
            descCount.className = 'text-gray-600 ml-2';
        }
    }
}

// Função para atualizar preview das seções
function updatePreview() {
    const secoes = {
        'sobre': document.getElementById('mostrar_sobre')?.checked || false,
        'servicos': document.getElementById('mostrar_servicos')?.checked || false,
        'eventos': document.getElementById('mostrar_eventos')?.checked || false,
        'contato': document.getElementById('mostrar_contato')?.checked || false,
        'doacao': document.getElementById('mostrar_doacao')?.checked || false,
        'aniversariantes': document.getElementById('secao_aniversariantes_ativa')?.checked || false
    };
    
    const previewContainer = document.getElementById('secoes-preview');
    if (!previewContainer) return;
    
    previewContainer.innerHTML = '';
    
    const secoesConfig = [
        { id: 'sobre', nome: 'Sobre Nós', icon: 'fas fa-info-circle', color: 'blue' },
        { id: 'servicos', nome: 'Nossos Serviços', icon: 'fas fa-hands-helping', color: 'green' },
        { id: 'eventos', nome: 'Eventos', icon: 'fas fa-calendar-alt', color: 'purple' },
        { id: 'contato', nome: 'Contato', icon: 'fas fa-phone', color: 'orange' },
        { id: 'doacao', nome: 'Doação Online', icon: 'fas fa-heart', color: 'red' },
        { id: 'aniversariantes', nome: 'Aniversariantes', icon: 'fas fa-birthday-cake', color: 'pink' }
    ];
    
    secoesConfig.forEach(secao => {
        const isActive = secoes[secao.id];
        const card = document.createElement('div');
        card.className = `p-4 rounded-xl border-2 transition-all duration-200 ${
            isActive 
                ? `bg-gradient-to-br from-${secao.color}-50 to-${secao.color}-100 border-${secao.color}-200` 
                : 'bg-gray-50 border-gray-200 opacity-50'
        }`;
        
        card.innerHTML = `
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 ${
                    isActive ? `bg-${secao.color}-500 text-white` : 'bg-gray-300 text-gray-500'
                }">
                    <i class="${secao.icon}"></i>
                </div>
                <div>
                    <h3 class="font-semibold ${isActive ? 'text-gray-900' : 'text-gray-500'}">${secao.nome}</h3>
                    <p class="text-sm ${isActive ? 'text-gray-600' : 'text-gray-400'}">
                        ${isActive ? 'Ativa' : 'Inativa'}
                    </p>
                </div>
                <div class="ml-auto">
                    <div class="w-4 h-4 rounded-full ${isActive ? 'bg-green-500' : 'bg-gray-300'}"></div>
                </div>
            </div>
        `;
        
        previewContainer.appendChild(card);
    });
}

// Inicializar previews quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    updateHeroPreview();
    updateAniversariantesPreview();
    updateColorPreview();
    updateOverlayPreview();
    updateHorariosPreview();
    updateDoacaoPreview();
    updateContatoPreview();
    updateSeoPreview();
    updatePreview();
});

function salvarConfiguracao() {
    document.querySelector('form').submit();
}

function previewConfiguracao() {
    window.open('{{ route("home") }}?preview=1', '_blank');
}

function resetarConfiguracao() {
    if (confirm('{{ __("Deseja resetar todas as configurações para os valores padrão?") }}')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.system.home-config.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection 