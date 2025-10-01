{{-- Componente da Aba Geral - Configurações do Sistema --}}
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-cog mr-2 text-blue-600"></i>Configurações Gerais
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure as informações básicas da aplicação</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.system.settings.update') }}" method="POST" enctype="multipart/form-data" id="form-geral">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" value="geral">

                {{-- Informações Básicas --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Informações Básicas
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome da Aplicação *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('app_name') border-red-500 @enderror" 
                                   id="app_name" name="app_name" 
                                   value="{{ old('app_name', $configuracoes['app_name'] ?? config('app.name')) }}" 
                                   required>
                            @error('app_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="app_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('app_description') border-red-500 @enderror" 
                                   id="app_description" name="app_description" 
                                   value="{{ old('app_description', $configuracoes['app_description'] ?? '') }}">
                            @error('app_description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email de Contato *</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('contact_email') border-red-500 @enderror" 
                                   id="contact_email" name="contact_email" 
                                   value="{{ old('contact_email', $configuracoes['contact_email'] ?? '') }}" 
                                   required>
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefone de Contato</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('contact_phone') border-red-500 @enderror" 
                                   id="contact_phone" name="contact_phone" 
                                   value="{{ old('contact_phone', $configuracoes['contact_phone'] ?? '') }}">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $configuracoes['address'] ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fuso Horário *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('timezone') border-red-500 @enderror" 
                                    id="timezone" name="timezone" required>
                                @foreach(timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}" 
                                            {{ (old('timezone', $configuracoes['timezone'] ?? config('app.timezone')) === $timezone) ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Idioma *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('locale') border-red-500 @enderror" 
                                    id="locale" name="locale" required>
                                <option value="pt_BR" {{ (old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'pt_BR') ? 'selected' : '' }}>Português (Brasil)</option>
                                <option value="en" {{ (old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'en') ? 'selected' : '' }}>English</option>
                                <option value="es" {{ (old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'es') ? 'selected' : '' }}>Español</option>
                            </select>
                            @error('locale')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Imagens --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-images mr-2"></i>Imagens
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo da Aplicação</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('logo') border-red-500 @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @if(isset($configuracoes['logo']) && $configuracoes['logo'])
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $configuracoes['logo']) }}" 
                                         alt="Logo atual" class="rounded-lg border border-gray-200 dark:border-gray-600" style="max-height: 100px;">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Logo atual</p>
                                </div>
                            @endif
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formatos aceitos: JPG, PNG, SVG. Tamanho máximo: 2MB</p>
                        </div>

                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('favicon') border-red-500 @enderror" 
                                   id="favicon" name="favicon" accept="image/*">
                            @if(isset($configuracoes['favicon']) && $configuracoes['favicon'])
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $configuracoes['favicon']) }}" 
                                         alt="Favicon atual" class="rounded border border-gray-200 dark:border-gray-600" style="max-height: 50px;">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Favicon atual</p>
                                </div>
                            @endif
                            @error('favicon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formatos aceitos: ICO, PNG. Tamanho recomendado: 32x32px</p>
                        </div>
                    </div>
                </div>

                {{-- Redes Sociais --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-share-alt mr-2"></i>Redes Sociais
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('facebook_url') border-red-500 @enderror" 
                                   id="facebook_url" name="facebook_url" 
                                   value="{{ old('facebook_url', $configuracoes['facebook_url'] ?? '') }}" 
                                   placeholder="https://facebook.com/sua-pagina">
                            @error('facebook_url')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('instagram_url') border-red-500 @enderror" 
                                   id="instagram_url" name="instagram_url" 
                                   value="{{ old('instagram_url', $configuracoes['instagram_url'] ?? '') }}" 
                                   placeholder="https://instagram.com/seu-perfil">
                            @error('instagram_url')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('youtube_url') border-red-500 @enderror" 
                                   id="youtube_url" name="youtube_url" 
                                   value="{{ old('youtube_url', $configuracoes['youtube_url'] ?? '') }}" 
                                   placeholder="https://youtube.com/seu-canal">
                            @error('youtube_url')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200 flex items-center" onclick="window.location.reload()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center" id="btn-submit-geral">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>