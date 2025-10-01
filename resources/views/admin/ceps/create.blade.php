@extends('layouts.admin')

@section('title', 'Novo CEP')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo CEP</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Adicione um novo CEP à base de dados</p>
                    </div>
                    <a href="{{ route('admin.people.ceps.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Formulário -->
            <form action="{{ route('admin.people.ceps.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CEP Inicial -->
                    <div>
                        <label for="cep_inicial" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            CEP Inicial *
                        </label>
                        <input type="text" 
                               id="cep_inicial" 
                               name="cep_inicial" 
                               value="{{ old('cep_inicial') }}"
                               maxlength="8"
                               placeholder="00000000"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cep_inicial') border-red-500 @enderror"
                               required>
                        @error('cep_inicial')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CEP Final -->
                    <div>
                        <label for="cep_final" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            CEP Final *
                        </label>
                        <input type="text" 
                               id="cep_final" 
                               name="cep_final" 
                               value="{{ old('cep_final') }}"
                               maxlength="8"
                               placeholder="99999999"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cep_final') border-red-500 @enderror"
                               required>
                        @error('cep_final')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Localidade -->
                    <div>
                        <label for="localidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Localidade *
                        </label>
                        <input type="text" 
                               id="localidade" 
                               name="localidade" 
                               value="{{ old('localidade') }}"
                               placeholder="Nome da cidade"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('localidade') border-red-500 @enderror"
                               required>
                        @error('localidade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- UF -->
                    <div>
                        <label for="uf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            UF *
                        </label>
                        <select id="uf" 
                                name="uf"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('uf') border-red-500 @enderror"
                                required>
                            <option value="">Selecione a UF</option>
                            <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>AC - Acre</option>
                            <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>AL - Alagoas</option>
                            <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>AP - Amapá</option>
                            <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>AM - Amazonas</option>
                            <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>BA - Bahia</option>
                            <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>CE - Ceará</option>
                            <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>DF - Distrito Federal</option>
                            <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>ES - Espírito Santo</option>
                            <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>GO - Goiás</option>
                            <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>MA - Maranhão</option>
                            <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>MT - Mato Grosso</option>
                            <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>MS - Mato Grosso do Sul</option>
                            <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>MG - Minas Gerais</option>
                            <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>PA - Pará</option>
                            <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>PB - Paraíba</option>
                            <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>PR - Paraná</option>
                            <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>PE - Pernambuco</option>
                            <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>PI - Piauí</option>
                            <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>RJ - Rio de Janeiro</option>
                            <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>RN - Rio Grande do Norte</option>
                            <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>RS - Rio Grande do Sul</option>
                            <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>RO - Rondônia</option>
                            <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>RR - Roraima</option>
                            <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>SC - Santa Catarina</option>
                            <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>SP - São Paulo</option>
                            <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>SE - Sergipe</option>
                            <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>TO - Tocantins</option>
                        </select>
                        @error('uf')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Faixa de CEP -->
                    <div class="md:col-span-2">
                        <label for="faixa_de_cep" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Faixa de CEP
                        </label>
                        <input type="text" 
                               id="faixa_de_cep" 
                               name="faixa_de_cep" 
                               value="{{ old('faixa_de_cep') }}"
                               placeholder="Descrição da faixa de CEP"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('faixa_de_cep') border-red-500 @enderror">
                        @error('faixa_de_cep')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Código IBGE -->
                    <div>
                        <label for="cod_ibge" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Código IBGE
                        </label>
                        <input type="text" 
                               id="cod_ibge" 
                               name="cod_ibge" 
                               value="{{ old('cod_ibge') }}"
                               placeholder="Código IBGE da cidade"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cod_ibge') border-red-500 @enderror">
                        @error('cod_ibge')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Latitude
                        </label>
                        <input type="number" 
                               id="latitude" 
                               name="latitude" 
                               value="{{ old('latitude') }}"
                               step="any"
                               min="-90"
                               max="90"
                               placeholder="-23.5505"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Longitude
                        </label>
                        <input type="number" 
                               id="longitude" 
                               name="longitude" 
                               value="{{ old('longitude') }}"
                               step="any"
                               min="-180"
                               max="180"
                               placeholder="-46.6333"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.people.ceps.index') }}" 
                       class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        Salvar CEP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CEP (apenas números)
    const cepInputs = document.querySelectorAll('#cep_inicial, #cep_final');
    
    cepInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) {
                value = value.substring(0, 8);
            }
            e.target.value = value;
        });
    });
});
</script>
@endpush
@endsection