@extends('layouts.member')
@section('title', 'EBD - Não Matriculado')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 mb-4">
                    <i class="fas fa-graduation-cap text-4xl text-yellow-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Você não está matriculado na EBD</h1>
                <p class="text-gray-600 mb-6">
                    Para acessar o conteúdo da Escola Bíblica Dominical, você precisa estar matriculado em uma turma.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Como se matricular?</h3>
                <div class="text-left space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                        <p class="text-blue-800">Entre em contato com a secretaria da igreja</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                        <p class="text-blue-800">Informe seu interesse em participar da EBD</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-white text-xs font-bold">3</span>
                        </div>
                        <p class="text-blue-800">Será feita sua matrícula na turma adequada</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Informações de Contato</h4>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-phone mr-2"></i>
                        (11) 99999-9999
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-envelope mr-2"></i>
                        secretaria@igreja.com
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Horário de Atendimento</h4>
                    <p class="text-sm text-gray-600">
                        Segunda a Sexta: 8h às 18h
                    </p>
                    <p class="text-sm text-gray-600">
                        Sábado: 8h às 12h
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('member.dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
                </a>
                <a href="tel:+5511999999999" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-phone mr-2"></i>Ligar Agora
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 