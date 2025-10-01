@extends('layouts.admin')

@section('title', 'Editar Aluno EBD')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Editar Aluno da Escola Bíblica Dominical</h1>
        <a href="{{ route('admin.ebd.alunos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Voltar</a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.ebd.alunos.update', $aluno) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Membro (Opcional) -->
                <div>
                    <label for="membro_id" class="block text-sm font-medium text-gray-700 mb-2">Membro (Opcional)</label>
                    <select name="membro_id" id="membro_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um membro (opcional)</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" {{ old('membro_id', $aluno->membro_id) == $membro->id ? 'selected' : '' }}>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('membro_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Turma -->
                <div>
                    <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                    <select name="turma_id" id="turma_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma turma</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}" {{ old('turma_id', $aluno->turma_id) == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('turma_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $aluno->nome) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $aluno->email) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefone -->
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $aluno->telefone) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('telefone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Nascimento -->
                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento', $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : '') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_nascimento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nome do Responsável -->
                <div>
                    <label for="nome_responsavel" class="block text-sm font-medium text-gray-700 mb-2">Nome do Responsável</label>
                    <input type="text" name="nome_responsavel" id="nome_responsavel" value="{{ old('nome_responsavel', $aluno->nome_responsavel) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nome_responsavel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefone do Responsável -->
                <div>
                    <label for="telefone_responsavel" class="block text-sm font-medium text-gray-700 mb-2">Telefone do Responsável</label>
                    <input type="text" name="telefone_responsavel" id="telefone_responsavel" value="{{ old('telefone_responsavel', $aluno->telefone_responsavel) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('telefone_responsavel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Matrícula -->
                <div>
                    <label for="data_matricula" class="block text-sm font-medium text-gray-700 mb-2">Data de Matrícula</label>
                    <input type="date" name="data_matricula" id="data_matricula" value="{{ old('data_matricula', $aluno->data_matricula->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_matricula')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="ativo" {{ old('status', $aluno->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ old('status', $aluno->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        <option value="transferido" {{ old('status', $aluno->status) == 'transferido' ? 'selected' : '' }}>Transferido</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="4" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observacoes', $aluno->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Atualizar Aluno
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 