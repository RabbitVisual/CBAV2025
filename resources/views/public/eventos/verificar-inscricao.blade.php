@extends('layouts.public')

@section('title', 'Verificar Inscrição')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-search text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Verificar Inscrição</h1>
            <p class="text-gray-600 mt-2">Digite seu e-mail para verificar o status da sua inscrição</p>
        </div>

        <!-- Formulário -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form method="POST" action="{{ route('public.eventos.verificar-inscricao', $evento) }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="seu@email.com">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>Verificar Inscrição
                    </button>
                </form>
            </div>
        </div>

        <!-- Informações do Evento -->
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Evento</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Evento:</span>
                        <p class="text-gray-900">{{ $evento->titulo }}</p>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-700">Data:</span>
                        <p class="text-gray-900">{{ $evento->data_inicio->format('d/m/Y') }}</p>
                    </div>

                    @if($evento->hora_inicio)
                        <div>
                            <span class="text-sm font-medium text-gray-700">Hora:</span>
                            <p class="text-gray-900">{{ $evento->hora_inicio->format('H:i') }}</p>
                        </div>
                    @endif

                    @if($evento->local)
                        <div>
                            <span class="text-sm font-medium text-gray-700">Local:</span>
                            <p class="text-gray-900">{{ $evento->local }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Links -->
        <div class="mt-6 text-center">
            <a href="{{ route('public.eventos.show', $evento) }}" 
               class="text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Evento
            </a>
        </div>
    </div>
</div>
@endsection 