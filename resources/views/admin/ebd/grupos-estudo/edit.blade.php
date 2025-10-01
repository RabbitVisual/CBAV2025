@extends('layouts.admin')

@section('title', 'Editar Grupo de Estudo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Grupo: {{ $grupo->nome }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.grupos-estudo.show', $grupo) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.ebd.grupos-estudo.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
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

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.ebd.grupos-estudo.update', $grupo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Grupo -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Grupo *</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $grupo->nome) }}" required
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
                            <option value="{{ $turma->id }}" {{ old('turma_id', $grupo->turma_id) == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }} - {{ $turma->faixa_etaria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Capacidade Máxima -->
                <div>
                    <label for="capacidade_maxima" class="block text-sm font-medium text-gray-700 mb-2">Capacidade Máxima</label>
                    <input type="number" name="capacidade_maxima" id="capacidade_maxima" 
                           value="{{ old('capacidade_maxima', $grupo->capacidade_maxima) }}" min="2" max="20"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Número máximo de membros (2-20)</p>
                </div>

                <!-- Cor do Grupo -->
                <div>
                    <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">Cor do Grupo</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="cor" id="cor" value="{{ old('cor', $grupo->cor) }}"
                               class="w-16 h-10 border border-gray-300 rounded-md cursor-pointer">
                        <input type="text" id="cor_text" value="{{ old('cor', $grupo->cor) }}" readonly
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
                              placeholder="Descreva o propósito e objetivos do grupo...">{{ old('descricao', $grupo->descricao) }}</textarea>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="ativo" id="ativo" value="1" 
                               {{ old('ativo', $grupo->ativo) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Grupo ativo
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.ebd.grupos-estudo.show', $grupo) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <!-- Seção de Gerenciamento de Membros -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Gerenciar Membros</h2>
        
        <!-- Membros Atuais -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Membros Atuais ({{ $grupo->membrosAtivos->count() }})</h3>
            @if($grupo->membrosAtivos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($grupo->membrosAtivos as $membro)
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <div class="flex items-center">
                                @if($membro->aluno->id == $grupo->lider_id)
                                    <i class="fas fa-crown text-yellow-500 mr-2" title="Líder"></i>
                                @endif
                                <span class="text-sm font-medium">{{ $membro->aluno->nome }}</span>
                            </div>
                            <form action="{{ route('admin.ebd.grupos-estudo.remover-membro', [$grupo, $membro->aluno]) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Tem certeza que deseja remover este membro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Remover">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Nenhum membro no grupo.</p>
            @endif
        </div>

        <!-- Adicionar Novos Membros -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Adicionar Novos Membros</h3>
            @if($alunosDisponiveis->count() > 0)
                <form action="{{ route('admin.ebd.grupos-estudo.adicionar-membros', $grupo) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mb-4">
                        @foreach($alunosDisponiveis as $aluno)
                            <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="membros[]" value="{{ $aluno->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-2">
                                <span class="text-sm">{{ $aluno->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Adicionar Selecionados
                    </button>
                </form>
            @else
                <p class="text-gray-500">Todos os alunos da turma já estão em grupos ou não há alunos disponíveis.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Atualizar cor em tempo real
document.getElementById('cor').addEventListener('change', function() {
    document.getElementById('cor_text').value = this.value;
});

// Carregar todos os alunos no campo líder ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const liderSelect = document.getElementById('lider_id');
    const liderAtual = '{{ $grupo->lider_id }}';
    
    fetch('{{ route("admin.ebd.ajax.todos-alunos") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        liderSelect.innerHTML = '<option value="">Sem líder</option>';
        data.forEach(aluno => {
            const option = document.createElement('option');
            option.value = aluno.id;
            option.textContent = `${aluno.nome} (${aluno.turma_nome})`;
            if (aluno.id == liderAtual) {
                option.selected = true;
            }
            liderSelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Erro ao carregar alunos:', error);
        liderSelect.innerHTML = '<option value="">Erro ao carregar alunos</option>';
    });
});

// Carregar alunos quando turma for alterada
document.getElementById('turma_id').addEventListener('change', function() {
    const turmaId = this.value;
    
    if (turmaId && turmaId !== '{{ $grupo->turma_id }}') {
        if (confirm('Alterar a turma removerá todos os membros atuais do grupo. Deseja continuar?')) {
            // Recarregar a página com a nova turma
            window.location.href = `{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}?turma_id=${turmaId}`;
        } else {
            // Reverter para a turma original
            this.value = '{{ $grupo->turma_id }}';
        }
    }
});
</script>
@endpush
@endsection