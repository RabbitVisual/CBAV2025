@extends('layouts.admin')

@section('title', 'Detalhes da Lição EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalhes da Lição EBD</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.licoes.edit', $licao) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.ebd.licoes.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações da Lição -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $licao->titulo }}</h2>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $licao->cor_dificuldade }}">
                        {{ $licao->dificuldade_formatada }}
                    </span>
                </div>

                @if($licao->descricao)
                    <p class="text-gray-600 mb-4">{{ $licao->descricao }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Duração</h3>
                        <p class="text-gray-900">{{ $licao->duracao_formatada }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total de Aulas</h3>
                        <p class="text-gray-900">{{ $licao->total_aulas }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total de Avaliações</h3>
                        <p class="text-gray-900">{{ $licao->total_avaliacoes }}</p>
                    </div>
                </div>

                @if($licao->versiculo_chave)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Versículo Chave</h3>
                        <p class="text-gray-900 font-medium">{{ $licao->versiculo_chave }}</p>
                    </div>
                @endif

                @if($licao->objetivos)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Objetivos</h3>
                        <p class="text-gray-900">{{ $licao->objetivos }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Conteúdo</h3>
                    <div class="text-gray-900 whitespace-pre-wrap">{{ $licao->conteudo }}</div>
                </div>

                @if($licao->aplicacao_pratica)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Aplicação Prática</h3>
                        <p class="text-gray-900">{{ $licao->aplicacao_pratica }}</p>
                    </div>
                @endif

                @if($licao->oracao)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Oração</h3>
                        <p class="text-gray-900">{{ $licao->oracao }}</p>
                    </div>
                @endif

                @if($licao->material_necessario)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Material Necessário</h3>
                        <p class="text-gray-900">{{ $licao->material_necessario }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.ebd.aulas.create') }}?licao_id={{ $licao->id }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-calendar-plus mr-2"></i>Agendar Aula
                    </a>
                    <a href="{{ route('admin.ebd.avaliacoes.create') }}?licao_id={{ $licao->id }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-clipboard-check mr-2"></i>Criar Avaliação
                    </a>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $licao->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $licao->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Criada em:</span>
                        <span class="text-sm text-gray-900">{{ $licao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Atualizada em:</span>
                        <span class="text-sm text-gray-900">{{ $licao->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 