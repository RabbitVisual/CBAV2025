@extends('layouts.admin')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-plus text-green-600 mr-3"></i>
                    Criar Nova Sala
                </h1>
                <p class="text-gray-600 mt-2">Configure uma nova sala de chat</p>
            </div>
            <a href="{{ route('admin.chat.manage') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.chat.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informações Básicas -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informações Básicas
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome da Sala *
                                </label>
                                <input type="text" 
                                       id="nome" 
                                       name="nome" 
                                       value="{{ old('nome') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Ex: Jovens, Oração, Estudo Bíblico"
                                       required>
                                @error('nome')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição
                                </label>
                                <textarea id="descricao" 
                                          name="descricao" 
                                          rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Descreva o propósito desta sala...">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-cog text-purple-600 mr-2"></i>
                            Configurações
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Sala *
                                </label>
                                <select id="tipo" 
                                        name="tipo" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="publico" {{ old('tipo') === 'publico' ? 'selected' : '' }}>Público</option>
                                    <option value="privado" {{ old('tipo') === 'privado' ? 'selected' : '' }}>Privado</option>
                                    <option value="ministerio" {{ old('tipo') === 'ministerio' ? 'selected' : '' }}>Ministério</option>
                                    <option value="admin" {{ old('tipo') === 'admin' ? 'selected' : '' }}>Administração</option>
                                </select>
                                @error('tipo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="max_participantes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Máximo de Participantes
                                </label>
                                <input type="number" 
                                       id="max_participantes" 
                                       name="max_participantes" 
                                       value="{{ old('max_participantes') }}"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Deixe em branco para ilimitado">
                                @error('max_participantes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Aparência -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-palette text-pink-600 mr-2"></i>
                            Aparência
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cor da Sala *
                                </label>
                                <input type="color" 
                                       id="cor" 
                                       name="cor" 
                                       value="{{ old('cor', '#3b82f6') }}"
                                       class="w-full h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('cor')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="icone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ícone *
                                </label>
                                <select id="icone" 
                                        name="icone" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="fas fa-comments" {{ old('icone') === 'fas fa-comments' ? 'selected' : '' }}>💬 Comentários</option>
                                    <option value="fas fa-users" {{ old('icone') === 'fas fa-users' ? 'selected' : '' }}>👥 Usuários</option>
                                    <option value="fas fa-pray" {{ old('icone') === 'fas fa-pray' ? 'selected' : '' }}>🙏 Oração</option>
                                    <option value="fas fa-bible" {{ old('icone') === 'fas fa-bible' ? 'selected' : '' }}>📖 Bíblia</option>
                                    <option value="fas fa-music" {{ old('icone') === 'fas fa-music' ? 'selected' : '' }}>🎵 Música</option>
                                    <option value="fas fa-child" {{ old('icone') === 'fas fa-child' ? 'selected' : '' }}>👶 Jovens</option>
                                    <option value="fas fa-baby" {{ old('icone') === 'fas fa-baby' ? 'selected' : '' }}>👼 Crianças</option>
                                    <option value="fas fa-shield-alt" {{ old('icone') === 'fas fa-shield-alt' ? 'selected' : '' }}>🛡️ Administração</option>
                                    <option value="fas fa-heart" {{ old('icone') === 'fas fa-heart' ? 'selected' : '' }}>❤️ Amor</option>
                                    <option value="fas fa-star" {{ old('icone') === 'fas fa-star' ? 'selected' : '' }}>⭐ Estrela</option>
                                </select>
                                @error('icone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Participantes Iniciais -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user-plus text-green-600 mr-2"></i>
                            Participantes Iniciais
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-4">
                                Selecione os usuários que serão adicionados automaticamente à sala:
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto">
                                @foreach($users as $user)
                                    <label class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" 
                                               name="participantes[]" 
                                               value="{{ $user->id }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.chat.manage') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Criar Sala
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview da cor
    const colorInput = document.getElementById('cor');
    const iconSelect = document.getElementById('icone');
    
    // Atualizar preview quando a cor mudar
    colorInput.addEventListener('change', function() {
        // Aqui você pode adicionar um preview visual se necessário
    });
    
    // Atualizar preview quando o ícone mudar
    iconSelect.addEventListener('change', function() {
        // Aqui você pode adicionar um preview visual se necessário
    });
});
</script>
@endpush
@endsection 