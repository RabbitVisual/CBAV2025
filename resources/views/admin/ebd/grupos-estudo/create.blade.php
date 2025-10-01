@extends('layouts.admin')

@section('title', 'Novo Grupo de Estudo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Novo Grupo de Estudo</h1>
        <a href="{{ route('admin.ebd.grupos-estudo.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.ebd.grupos-estudo.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Grupo -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Grupo *</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Grupo dos Jovens Guerreiros">
                </div>

                <!-- Turma -->
                <div>
                    <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-2">Turma *</label>
                    <select name="turma_id" id="turma_id" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma turma</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }} - {{ $turma->faixa_etaria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Capacidade Máxima -->
                <div>
                    <label for="capacidade_maxima" class="block text-sm font-medium text-gray-700 mb-2">Capacidade Máxima</label>
                    <input type="number" name="capacidade_maxima" id="capacidade_maxima" 
                           value="{{ old('capacidade_maxima', 8) }}" min="2" max="20"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Número máximo de membros (2-20)</p>
                </div>

                <!-- Cor do Grupo -->
                <div>
                    <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">Cor do Grupo</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="cor" id="cor" value="{{ old('cor', '#3b82f6') }}"
                               class="w-16 h-10 border border-gray-300 rounded-md cursor-pointer">
                        <input type="text" id="cor_text" value="{{ old('cor', '#3b82f6') }}" readonly
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 bg-gray-50">
                    </div>
                </div>

                <!-- Líder do Grupo -->
                <div>
                    <label for="lider_id" class="block text-sm font-medium text-gray-700 mb-2">Líder do Grupo</label>
                    <select name="lider_id" id="lider_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Carregando alunos...</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Lista de todos os alunos cadastrados no sistema</p>
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea name="descricao" id="descricao" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descreva o propósito e objetivos do grupo...">{{ old('descricao') }}</textarea>
                </div>

                <!-- Membros Iniciais -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membros Iniciais</label>
                    <div id="membros-container" class="border border-gray-300 rounded-md p-4 min-h-[100px]">
                        <p class="text-gray-500 text-center" id="no-membros-message">Selecione uma turma primeiro para ver os alunos disponíveis</p>
                    </div>
                    <input type="hidden" name="membros[]" id="membros-input">
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="ativo" id="ativo" value="1" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Grupo ativo
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.ebd.grupos-estudo.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Criar Grupo
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Atualizar cor em tempo real
document.getElementById('cor').addEventListener('change', function() {
    document.getElementById('cor_text').value = this.value;
});

// Carregar todos os alunos para o campo líder quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    const liderSelect = document.getElementById('lider_id');
    
    fetch('/admin/ebd/ajax/todos-alunos', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        liderSelect.innerHTML = '<option value="">Selecionar líder depois</option>';
        data.forEach(aluno => {
            const turmaInfo = aluno.turma ? ` (${aluno.turma.nome})` : '';
            liderSelect.innerHTML += `<option value="${aluno.id}">${aluno.nome}${turmaInfo}</option>`;
        });
    })
    .catch(error => {
        console.error('Erro ao carregar alunos:', error);
        liderSelect.innerHTML = '<option value="">Erro ao carregar alunos</option>';
    });
});

// Carregar alunos quando turma for selecionada (apenas para membros)
document.getElementById('turma_id').addEventListener('change', function() {
    const turmaId = this.value;
    const membrosContainer = document.getElementById('membros-container');
    const noMembrosMessage = document.getElementById('no-membros-message');
    
    // Limpar seleções anteriores
    membrosContainer.innerHTML = '<p class="text-gray-500 text-center">Carregando alunos...</p>';
    
    if (turmaId) {
        fetch(`/admin/ebd/ajax/alunos-por-turma?turma_id=${turmaId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Atualizar lista de membros
                if (data.length > 0) {
                    let membrosHtml = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">';
                    data.forEach(aluno => {
                        membrosHtml += `
                            <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="membros[]" value="${aluno.id}" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-2">
                                <span class="text-sm">${aluno.nome}</span>
                            </label>
                        `;
                    });
                    membrosHtml += '</div>';
                    membrosContainer.innerHTML = membrosHtml;
                } else {
                    membrosContainer.innerHTML = '<p class="text-gray-500 text-center">Nenhum aluno encontrado nesta turma</p>';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar alunos:', error);
                membrosContainer.innerHTML = '<p class="text-red-500 text-center">Erro ao carregar alunos</p>';
            });
    } else {
        membrosContainer.innerHTML = '<p class="text-gray-500 text-center" id="no-membros-message">Selecione uma turma primeiro para ver os alunos disponíveis</p>';
    }
});
</script>
@endpush
@endsection