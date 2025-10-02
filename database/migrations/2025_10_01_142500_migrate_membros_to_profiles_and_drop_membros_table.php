<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Membro;
use App\Models\Profile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Garante que a tabela 'membros' exista antes de tentar a migração
        if (!Schema::hasTable('membros')) {
            return;
        }

        DB::transaction(function () {
            // Itera sobre cada membro para migrar seus dados
            Membro::all()->each(function ($membro) {
                // Encontra o usuário correspondente pelo email
                $user = User::where('email', $membro->email)->first();

                if ($user) {
                    // Cria o novo perfil com os dados do membro antigo
                    Profile::create([
                        'user_id' => $user->id,
                        'telefone' => $membro->telefone,
                        'data_nascimento' => $membro->data_nascimento,
                        'endereco' => $membro->endereco,
                        'cidade' => $membro->cidade,
                        'estado' => $membro->estado,
                        'cep' => $membro->cep,
                        'estado_civil' => $membro->estado_civil,
                        'data_batismo' => $membro->data_batismo,
                        'data_ingresso' => $membro->data_ingresso,
                        'observacoes' => $membro->observacoes,
                        'foto' => $membro->foto,
                    ]);
                }
            });
        });

        // Remove a tabela 'membros' antiga após a migração dos dados
        Schema::dropIfExists('membros');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recria a tabela 'membros' para o caso de um rollback
        Schema::create('membros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->enum('estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo'])->nullable();
            $table->date('data_batismo')->nullable();
            $table->date('data_ingresso')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // A restauração dos dados do profile para membro pode ser complexa
        // e é omitida aqui, pois a migração para frente é o caminho desejado.
    }
};