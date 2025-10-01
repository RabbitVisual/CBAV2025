<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, vamos adicionar um campo 'modulo' à tabela configuracoes
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->string('modulo')->default('sistema')->after('chave');
        });

        // Migrar dados da tabela ebd_configuracoes para configuracoes
        if (Schema::hasTable('ebd_configuracoes')) {
            $ebdConfigs = DB::table('ebd_configuracoes')->get();
            
            foreach ($ebdConfigs as $config) {
                DB::table('configuracoes')->insert([
                    'chave' => $config->chave,
                    'modulo' => 'ebd',
                    'valor' => $config->valor,
                    'tipo' => $config->tipo,
                    'descricao' => $config->descricao,
                    'created_at' => $config->created_at,
                    'updated_at' => $config->updated_at,
                ]);
            }

            // Remover a tabela ebd_configuracoes
            Schema::dropIfExists('ebd_configuracoes');
        }

        // Criar índices para melhor performance
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->index(['modulo', 'chave']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar a tabela ebd_configuracoes
        Schema::create('ebd_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->text('valor');
            $table->string('tipo')->default('string');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // Migrar dados de volta
        $ebdConfigs = DB::table('configuracoes')
            ->where('modulo', 'ebd')
            ->get();

        foreach ($ebdConfigs as $config) {
            DB::table('ebd_configuracoes')->insert([
                'chave' => $config->chave,
                'valor' => $config->valor,
                'tipo' => $config->tipo,
                'descricao' => $config->descricao,
                'created_at' => $config->created_at,
                'updated_at' => $config->updated_at,
            ]);
        }

        // Remover o campo modulo da tabela configuracoes
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->dropColumn('modulo');
        });
    }
}; 