@props(['configuracoes'])

<div x-show="activeTab === 'geral'" class="space-y-8">
    <!-- Bloco de Informações Básicas -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Básicas</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure o nome, contato e localização da aplicação.</p>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-admin.label for="app_name" value="Nome da Aplicação *" />
                <x-admin.input id="app_name" name="app_name" type="text" class="mt-1 block w-full"
                               :value="old('app_name', $configuracoes['app_name'])" required />
            </div>
            <div>
                <x-admin.label for="app_description" value="Descrição" />
                <x-admin.input id="app_description" name="app_description" type="text" class="mt-1 block w-full"
                               :value="old('app_description', $configuracoes['app_description'])" />
            </div>
            <div>
                <x-admin.label for="contact_email" value="Email de Contato *" />
                <x-admin.input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full"
                               :value="old('contact_email', $configuracoes['contact_email'])" required />
            </div>
            <div>
                <x-admin.label for="contact_phone" value="Telefone de Contato" />
                <x-admin.input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full"
                               :value="old('contact_phone', $configuracoes['contact_phone'])" />
            </div>
            <div class="md:col-span-2">
                <x-admin.label for="address" value="Endereço" />
                <x-admin.textarea id="address" name="address" rows="3" class="mt-1 block w-full">
                    {{ old('address', $configuracoes['address']) }}
                </x-admin.textarea>
            </div>
        </div>
    </x-admin.card>

    <!-- Bloco de Localização e Imagens -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Identidade Visual e Localização</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Defina o fuso horário, idioma e as imagens da marca.</p>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-admin.label for="timezone" value="Fuso Horário *" />
                <x-admin.select id="timezone" name="timezone" class="mt-1 block w-full">
                    @foreach(timezone_identifiers_list() as $timezone)
                        <option value="{{ $timezone }}" @selected(old('timezone', $configuracoes['timezone']) === $timezone)>
                            {{ $timezone }}
                        </option>
                    @endforeach
                </x-admin.select>
            </div>
            <div>
                <x-admin.label for="locale" value="Idioma *" />
                <x-admin.select id="locale" name="locale" class="mt-1 block w-full">
                    <option value="pt_BR" @selected(old('locale', $configuracoes['locale']) === 'pt_BR')>Português (Brasil)</option>
                    <option value="en" @selected(old('locale', $configuracoes['locale']) === 'en')>English</option>
                    <option value="es" @selected(old('locale', $configuracoes['locale']) === 'es')>Español</option>
                </x-admin.select>
            </div>
            <div>
                <x-admin.label for="app_logo" value="Logo da Aplicação" />
                <x-admin.input id="app_logo" name="app_logo" type="file" class="mt-1 block w-full" accept="image/*" />
                @if($configuracoes['app_logo'])
                    <div class="mt-2">
                        <img src="{{ Storage::url($configuracoes['app_logo']) }}" alt="Logo Atual" class="h-16 rounded-md border bg-gray-100 p-1">
                    </div>
                @endif
            </div>
            <div>
                <x-admin.label for="app_favicon" value="Favicon" />
                <x-admin.input id="app_favicon" name="app_favicon" type="file" class="mt-1 block w-full" accept="image/x-icon, image/png">
                @if($configuracoes['app_favicon'])
                    <div class="mt-2">
                        <img src="{{ Storage::url($configuracoes['app_favicon']) }}" alt="Favicon Atual" class="h-8 w-8 rounded-md border bg-gray-100 p-1">
                    </div>
                @endif
            </div>
        </div>
    </x-admin.card>

     <!-- Bloco de Redes Sociais -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Redes Sociais</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Links para os perfis sociais da organização.</p>
        </x-slot>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <x-admin.label for="social_facebook" value="Facebook" />
                <x-admin.input id="social_facebook" name="social_facebook" type="url" class="mt-1 block w-full"
                               :value="old('social_facebook', $configuracoes['social_facebook'])" placeholder="https://facebook.com/..." />
            </div>
            <div>
                <x-admin.label for="social_instagram" value="Instagram" />
                <x-admin.input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full"
                               :value="old('social_instagram', $configuracoes['social_instagram'])" placeholder="https://instagram.com/..." />
            </div>
            <div>
                <x-admin.label for="social_youtube" value="YouTube" />
                <x-admin.input id="social_youtube" name="social_youtube" type="url" class="mt-1 block w-full"
                               :value="old('social_youtube', $configuracoes['social_youtube'])" placeholder="https://youtube.com/..." />
            </div>
        </div>
    </x-admin.card>
</div>