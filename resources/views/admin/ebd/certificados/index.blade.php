@extends('layouts.admin')

@section('title', 'Certificados EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Certificados EBD</h1>
            <p class="text-gray-600 mt-2">Gerencie os certificados da Escola Bíblica Dominical</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ebd.certificados.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Novo Certificado
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.ebd.certificados.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="aluno_id" class="block text-sm font-medium text-gray-700 mb-2">Aluno</label>
                <select name="aluno_id" id="aluno_id" class="form-select">
                    <option value="">Todos os alunos</option>
                    @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}" {{ request('aluno_id') == $aluno->id ? 'selected' : '' }}>
                            {{ $aluno->membro->nome ?? $aluno->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos os tipos</option>
                    <option value="conclusao" {{ request('tipo') == 'conclusao' ? 'selected' : '' }}>Conclusão de Curso</option>
                    <option value="participacao" {{ request('tipo') == 'participacao' ? 'selected' : '' }}>Participação</option>
                    <option value="excelencia" {{ request('tipo') == 'excelencia' ? 'selected' : '' }}>Excelência</option>
                    <option value="presenca" {{ request('tipo') == 'presenca' ? 'selected' : '' }}>Presença</option>
                    <option value="avaliacao" {{ request('tipo') == 'avaliacao' ? 'selected' : '' }}>Avaliação</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos os status</option>
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn btn-secondary w-full">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Certificados -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($certificados->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Certificado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aluno
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data Emissão
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($certificados as $certificado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificado->titulo }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Código: {{ $certificado->codigo }}
                                    </div>
                                    @if($certificado->descricao)
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ Str::limit($certificado->descricao, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificado->aluno->membro->nome ?? $certificado->aluno->nome }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $certificado->aluno->turma->nome ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $certificado->cor_tipo }}">
                                        {{ $certificado->tipo_formatado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificado->data_emissao->format('d/m/Y') }}
                                    </div>
                                    @if($certificado->data_validade)
                                        <div class="text-sm text-gray-500">
                                            Válido até: {{ $certificado->data_validade->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $certificado->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $certificado->ativo ? 'Válido' : 'Expirado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.ebd.certificados.show', $certificado) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ebd.certificados.edit', $certificado) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.ebd.certificados.download', $certificado) }}" 
                                           class="text-green-600 hover:text-green-900" title="Baixar PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.ebd.certificados.destroy', $certificado) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este certificado?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $certificados->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-certificate text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum certificado encontrado</h3>
                <p class="text-gray-500 mb-4">Crie o primeiro certificado para começar a gerenciar os documentos.</p>
                <a href="{{ route('admin.ebd.certificados.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Certificado
                </a>
            </div>
        @endif
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Certificados</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $certificados->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Certificados Válidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $certificados->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Alunos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $alunos->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Carga Horária Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $certificados->sum('carga_horaria') }}h</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 