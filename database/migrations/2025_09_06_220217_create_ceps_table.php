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
        Schema::create('ceps', function (Blueprint $table) {
            $table->id();
            $table->string('uf', 2)->index(); // Unidade Federativa (Estado)
            $table->string('regiao', 50)->index(); // Região geográfica
            $table->string('localidade', 255)->index(); // Nome da cidade/município
            $table->string('localidade_sem_acentos', 255)->index(); // Nome da cidade sem acentos
            $table->string('faixa_de_cep', 50); // Intervalo de CEPs
            $table->string('cep_inicial', 8)->index(); // Primeiro CEP da faixa
            $table->string('cep_final', 8)->index(); // Último CEP da faixa
            $table->string('situacao', 100); // Situação do código de CEP
            $table->string('tipo_de_faixa', 100); // Tipo da faixa de CEP
            $table->decimal('latitude', 10, 8)->nullable(); // Coordenada geográfica (latitude)
            $table->decimal('longitude', 11, 8)->nullable(); // Coordenada geográfica (longitude)
            $table->string('cod_geografico_subdivisao', 20)->nullable(); // Código geográfico da subdivisão
            $table->string('cod_geografico_distrito', 20)->nullable(); // Código geográfico do distrito
            $table->string('cod_ibge', 20)->index(); // Código do IBGE
            $table->string('microrregiao', 255)->nullable(); // Microrregião
            $table->string('mesorregiao', 255)->nullable(); // Mesorregião
            $table->string('categoria', 100); // Classificação da localidade
            $table->integer('altitude')->nullable(); // Altitude da cidade em metros
            $table->text('localizacao')->nullable(); // Localização completa
            $table->text('localizacao_sem_acentos')->nullable(); // Localização sem acentos
            $table->timestamps();
            
            // Índices compostos para consultas otimizadas
            $table->index(['uf', 'localidade']);
            $table->index(['cep_inicial', 'cep_final']);
            $table->index(['cod_ibge']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceps');
    }
};
