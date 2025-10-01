@extends('layouts.admin')

@section('title', 'Visualizar Certificado EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $certificado->titulo }}</h1>
                <p class="text-gray-600">Detalhes do certificado EBD</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.ebd.certificados.edit', $certificado) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.ebd.certificados.download', $certificado) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Baixar PDF
                </a>
                <a href="{{ route('admin.ebd.certificados.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações do Certificado -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Certificado</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <p class="text-gray-900">{{ $certificado->titulo }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <p class="text-gray-900 font-mono">{{ $certificado->codigo }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $certificado->cor_tipo }}">
                            {{ $certificado->tipo_formatado }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $certificado->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $certificado->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data de Emissão</label>
                        <p class="text-gray-900">{{ $certificado->data_emissao->format('d/m/Y') }}</p>
                    </div>
                    
                    @if($certificado->data_validade)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Validade</label>
                            <p class="text-gray-900">{{ $certificado->data_validade->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    
                    @if($certificado->carga_horaria)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Carga Horária</label>
                            <p class="text-gray-900">{{ $certificado->carga_horaria }} horas</p>
                        </div>
                    @endif
                    
                    @if($certificado->nota_final)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nota Final</label>
                            <p class="text-gray-900">{{ $certificado->nota_final }}/100</p>
                        </div>
                    @endif
                    
                    @if($certificado->descricao)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="text-gray-900">{{ $certificado->descricao }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Aluno -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Aluno</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <p class="text-gray-900">{{ $certificado->aluno->membro->nome ?? $certificado->aluno->nome }}</p>
                    </div>
                    
                    @if($certificado->aluno->turma)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Turma</label>
                            <p class="text-gray-900">{{ $certificado->aluno->turma->nome }}</p>
                        </div>
                    @endif
                    
                    @if($certificado->aluno->data_matricula)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Matrícula</label>
                            <p class="text-gray-900">{{ $certificado->aluno->data_matricula->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    
                    @if($certificado->aluno->membro)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Membro</label>
                            <p class="text-gray-900">{{ $certificado->aluno->membro->nome }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Avaliação (se houver) -->
            @if($certificado->avaliacao)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Avaliação Relacionada</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título</label>
                            <p class="text-gray-900">{{ $certificado->avaliacao->titulo }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $certificado->avaliacao->cor_tipo }}">
                                {{ $certificado->avaliacao->tipo_formatado }}
                            </span>
                        </div>
                        
                        @if($certificado->avaliacao->descricao)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <p class="text-gray-900">{{ $certificado->avaliacao->descricao }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Conteúdo do Certificado -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Conteúdo do Certificado</h2>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="prose max-w-none">
                        {!! nl2br(e($certificado->conteudo)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assinaturas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assinaturas</h3>
                
                <div class="space-y-4">
                    @if($certificado->assinatura_coordenador)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Coordenador</label>
                            <p class="text-gray-900">{{ $certificado->assinatura_coordenador }}</p>
                        </div>
                    @endif
                    
                    @if($certificado->assinatura_pastor)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pastor</label>
                            <p class="text-gray-900">{{ $certificado->assinatura_pastor }}</p>
                        </div>
                    @endif
                    
                    @if(!$certificado->assinatura_coordenador && !$certificado->assinatura_pastor)
                        <p class="text-gray-500 text-sm">Nenhuma assinatura registrada</p>
                    @endif
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.ebd.certificados.edit', $certificado) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Certificado
                    </a>
                    
                    <a href="{{ route('admin.ebd.certificados.download', $certificado) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Baixar PDF
                    </a>
                    
                    <a href="{{ route('admin.ebd.certificados.visualizar', $certificado) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Visualizar PDF
                    </a>
                    
                    <form action="{{ route('admin.ebd.certificados.destroy', $certificado) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors"
                                onclick="return confirm('Tem certeza que deseja excluir este certificado?')">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Certificado
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Adicionais</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criado em</span>
                        <span class="font-medium">{{ $certificado->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização</span>
                        <span class="font-medium">{{ $certificado->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @if($certificado->data_validade)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Válido até</span>
                            <span class="font-medium {{ $certificado->data_validade->isPast() ? 'text-red-600' : 'text-green-600' }}">
                                {{ $certificado->data_validade->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 