@extends('layouts.member')

@section('title', 'Acesso Negado - ' . \App\Models\Configuracao::get('app_name', 'CBAV'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Minhas Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Acesso Negado</li>
        </ol>
    </nav>

    <!-- Mensagem de Acesso Negado -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <!-- Ícone de Acesso Negado -->
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Acesso Negado
            </h1>

            <!-- Mensagem -->
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    {{ $mensagem }}
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Informação de Segurança
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Por questões de segurança e privacidade, apenas o usuário que emitiu o comprovante 
                                    pode visualizar seus detalhes completos. Esta medida protege suas informações pessoais.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-gray-900 mb-2">O que você pode fazer:</h3>
                <ul class="text-sm text-gray-600 space-y-1 text-left">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Verificar seus próprios comprovantes na área de membro
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Usar a verificação pública para comprovantes de terceiros
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Entrar em contato conosco se precisar de ajuda
                    </li>
                </ul>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('member.donations.verificar-comprovante') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verificar Meus Comprovantes
                </a>
                
                <a href="{{ url('/public/validacao/comprovante') }}" 
                   target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Verificação Pública
                </a>
                
                <a href="{{ route('member.donations.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Doações
                </a>
            </div>

            <!-- Informações de Contato -->
            @if(\App\Models\Configuracao::get('contact_email'))
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">
                    Precisa de ajuda? Entre em contato conosco:
                </p>
                <p class="text-blue-600 font-medium">
                    {{ \App\Models\Configuracao::get('contact_email') }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 