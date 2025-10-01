@extends('layouts.member')

@section('title', 'Solicitar Participação - ' . $ministerio->nome)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('member.ministries.index') }}" class="text-purple-600 hover:text-purple-800 mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-purple-600 mr-3"></i>
                Solicitar Participação
            </h1>
        </div>
        <p class="text-gray-600">Preencha o formulário abaixo para solicitar participação no ministério.</p>
    </div>

    <!-- Informações do Ministério -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Ministério: {{ $ministerio->nome }}
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Descrição</h4>
                    <p class="text-gray-600">{{ $ministerio->descricao ?? 'Sem descrição disponível.' }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Departamentos</h4>
                    <p class="text-gray-600">{{ $ministerio->departamentos->count() }} departamento(s)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Solicitação -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-edit text-green-600 mr-2"></i>
                Formulário de Solicitação
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('member.ministries.request', $ministerio->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cargo -->
                    <div>
                        <label for="cargo_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Cargo Desejado <span class="text-red-500">*</span>
                        </label>
                        <select name="cargo_id" id="cargo_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Selecione um cargo</option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                                    {{ $cargo->nome }} - {{ $cargo->departamento->nome ?? 'Sem departamento' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cargo_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motivo -->
                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo da Solicitação
                        </label>
                        <textarea name="motivo" id="motivo" rows="3" 
                                  placeholder="Explique brevemente por que deseja participar deste ministério..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('motivo') }}</textarea>
                        @error('motivo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informações Importantes
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Sua solicitação será analisada pelos líderes do ministério</li>
                        <li>• Você receberá uma notificação quando a solicitação for aprovada ou rejeitada</li>
                        <li>• O processo pode levar alguns dias para ser concluído</li>
                        <li>• Você pode cancelar a solicitação a qualquer momento</li>
                    </ul>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('member.ministries.show', $ministerio->id) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 