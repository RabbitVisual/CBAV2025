@extends('layouts.member')

@section('title', 'Detalhes do Certificado EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $certificado->titulo }}</h1>
                <p class="text-gray-600">Certificado da Escola Bíblica Dominical</p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full 
                {{ $certificado->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $certificado->ativo ? 'Válido' : 'Expirado' }}
            </span>
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
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <p class="text-gray-900">{{ ucfirst($certificado->tipo) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <p class="text-gray-900 font-mono">{{ $certificado->codigo }}</p>
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
                            <p class="text-gray-900">{{ $certificado->nota_final }} pontos</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descrição -->
            @if($certificado->descricao)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Descrição</h2>
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($certificado->descricao)) !!}
                    </div>
                </div>
            @endif

            <!-- Conteúdo do Certificado -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Conteúdo do Certificado</h2>
                
                <div class="prose max-w-none">
                    {!! nl2br(e($certificado->conteudo)) !!}
                </div>
            </div>

            <!-- Assinaturas -->
            @if($certificado->assinatura_coordenador || $certificado->assinatura_pastor)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Assinaturas</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($certificado->assinatura_coordenador)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Coordenador EBD</label>
                                <p class="text-gray-900">{{ $certificado->assinatura_coordenador }}</p>
                            </div>
                        @endif
                        
                        @if($certificado->assinatura_pastor)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pastor</label>
                                <p class="text-gray-900">{{ $certificado->assinatura_pastor }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- QR Code de Verificação -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Verificação</h3>
                
                <div class="text-center">
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-600">Código de verificação: {{ $certificado->codigo }}</p>
                    <p class="text-xs text-gray-500 mt-2">Escaneie para verificar a autenticidade</p>
                </div>
            </div>

            <!-- Informações do Aluno -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Aluno</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <p class="text-gray-900">{{ $certificado->aluno->nome }}</p>
                    </div>
                    
                    @if($certificado->aluno->turma)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Turma</label>
                            <p class="text-gray-900">{{ $certificado->aluno->turma->nome }}</p>
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

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Frequência</span>
                        <span class="font-medium">{{ number_format($certificado->aluno->percentual_presenca, 1) }}%</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aulas Participadas</span>
                        <span class="font-medium">{{ $certificado->aluno->total_presencas }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Avaliações Realizadas</span>
                        <span class="font-medium">{{ $certificado->aluno->notas->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Média Geral</span>
                        <span class="font-medium">{{ number_format($certificado->aluno->media_geral, 1) }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('member.ebd.certificados.download', $certificado) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Baixar PDF
                    </a>
                    
                    <a href="{{ route('member.ebd.certificados.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar aos Certificados
                    </a>
                </div>
            </div>

            <!-- Informações Legais -->
            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="font-medium text-yellow-900 mb-2">Informações Legais</h4>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Este certificado é válido apenas em formato digital</li>
                    <li>• Pode ser verificado através do código único</li>
                    <li>• Emitido pela Escola Bíblica Dominical</li>
                    <li>• Não possui valor oficial de diploma</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 