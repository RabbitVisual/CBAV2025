@extends('layouts.admin')

@section('title', 'Novo Certificado EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Novo Certificado EBD</h1>
                <p class="text-gray-600">Crie um novo certificado para a Escola Bíblica Dominical</p>
            </div>
            <a href="{{ route('admin.ebd.certificados.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.ebd.certificados.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Aluno -->
                <div class="md:col-span-2">
                    <label for="aluno_id" class="block text-sm font-medium text-gray-700 mb-2">Aluno *</label>
                    <select name="aluno_id" id="aluno_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um aluno</option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                {{ $aluno->membro ? $aluno->membro->nome : $aluno->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('aluno_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avaliação -->
                <div class="md:col-span-2">
                    <label for="avaliacao_id" class="block text-sm font-medium text-gray-700 mb-2">Avaliação (Opcional)</label>
                    <select name="avaliacao_id" id="avaliacao_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma avaliação (opcional)</option>
                        @foreach($avaliacoes as $avaliacao)
                            <option value="{{ $avaliacao->id }}" {{ old('avaliacao_id') == $avaliacao->id ? 'selected' : '' }}>
                                {{ $avaliacao->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('avaliacao_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título do Certificado *</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Certificado de Conclusão do Curso Básico">
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Certificado *</label>
                    <select name="tipo" id="tipo" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        @foreach($tipos as $valor => $nome)
                            <option value="{{ $valor }}" {{ old('tipo') == $valor ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Emissão -->
                <div>
                    <label for="data_emissao" class="block text-sm font-medium text-gray-700 mb-2">Data de Emissão *</label>
                    <input type="date" name="data_emissao" id="data_emissao" value="{{ old('data_emissao', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_emissao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Carga Horária -->
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700 mb-2">Carga Horária (horas)</label>
                    <input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria') }}" 
                           min="1" max="1000"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 40">
                    @error('carga_horaria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nota Final -->
                <div>
                    <label for="nota_final" class="block text-sm font-medium text-gray-700 mb-2">Nota Final</label>
                    <input type="number" name="nota_final" id="nota_final" value="{{ old('nota_final') }}" 
                           min="0" max="100" step="0.1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 85.5">
                    @error('nota_final')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Validade -->
                <div>
                    <label for="data_validade" class="block text-sm font-medium text-gray-700 mb-2">Data de Validade</label>
                    <input type="date" name="data_validade" id="data_validade" value="{{ old('data_validade') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_validade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea name="descricao" id="descricao" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descrição detalhada do certificado...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conteúdo -->
                <div class="md:col-span-2">
                    <label for="conteudo" class="block text-sm font-medium text-gray-700 mb-2">Conteúdo do Certificado *</label>
                    <textarea name="conteudo" id="conteudo" rows="8" required
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Conteúdo detalhado que aparecerá no certificado...">{{ old('conteudo') }}</textarea>
                    @error('conteudo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assinatura Coordenador -->
                <div>
                    <label for="assinatura_coordenador" class="block text-sm font-medium text-gray-700 mb-2">Assinatura do Coordenador</label>
                    <input type="text" name="assinatura_coordenador" id="assinatura_coordenador" value="{{ old('assinatura_coordenador') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nome do coordenador">
                    @error('assinatura_coordenador')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assinatura Pastor -->
                <div>
                    <label for="assinatura_pastor" class="block text-sm font-medium text-gray-700 mb-2">Assinatura do Pastor</label>
                    <input type="text" name="assinatura_pastor" id="assinatura_pastor" value="{{ old('assinatura_pastor') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nome do pastor">
                    @error('assinatura_pastor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ativo -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="ativo" id="ativo" value="1" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Certificado ativo
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Criar Certificado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 