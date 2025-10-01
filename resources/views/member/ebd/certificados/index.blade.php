@extends('layouts.member')

@section('title', 'Meus Certificados EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Meus Certificados EBD</h1>
        <p class="text-gray-600">Visualize e baixe seus certificados da Escola Bíblica Dominical</p>
    </div>

    @if($certificados->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($certificados as $certificado)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $certificado->titulo }}</h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $certificado->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $certificado->ativo ? 'Válido' : 'Expirado' }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            <span>{{ $certificado->tipo }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>Emitido em {{ $certificado->data_emissao->format('d/m/Y') }}</span>
                        </div>

                        @if($certificado->data_validade)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2"></i>
                                <span>Válido até {{ $certificado->data_validade->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-hashtag mr-2"></i>
                            <span>Código: {{ $certificado->codigo }}</span>
                        </div>

                        @if($certificado->descricao)
                            <p class="text-sm text-gray-600">{{ Str::limit($certificado->descricao, 100) }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ $certificado->carga_horaria ?? 0 }} horas</span>
                            <span>{{ $certificado->nota_final ?? 'N/A' }} pontos</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                        <a href="{{ route('member.ebd.certificados.show', $certificado) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Ver
                        </a>
                        
                        <a href="{{ route('member.ebd.certificados.download', $certificado) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Baixar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum Certificado Disponível</h3>
            <p class="text-gray-500">Você ainda não possui certificados EBD. Continue estudando para obter seus certificados!</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="ativo">Válidos</option>
                    <option value="inativo">Expirados</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="conclusao">Conclusão</option>
                    <option value="participacao">Participação</option>
                    <option value="excelencia">Excelência</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ano</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach(range(date('Y'), date('Y')-5) as $ano)
                        <option value="{{ $ano }}">{{ $ano }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Certificados</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $certificados->count() }}</p>
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
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Carga Horária Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $certificados->sum('carga_horaria') }}h</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Média Geral</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($certificados->avg('nota_final'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações sobre Certificados</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Como obter certificados:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Participe regularmente das aulas EBD</li>
                    <li>• Complete as avaliações obrigatórias</li>
                    <li>• Mantenha uma frequência mínima de 75%</li>
                    <li>• Alcançe a nota mínima de aprovação</li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Tipos de certificados:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• <strong>Conclusão:</strong> Ao finalizar um módulo completo</li>
                    <li>• <strong>Participação:</strong> Por frequência e envolvimento</li>
                    <li>• <strong>Excelência:</strong> Para desempenho excepcional</li>
                    <li>• <strong>Especial:</strong> Por projetos ou atividades especiais</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 