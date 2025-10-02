<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('ebd_turmas', 'ebd_turmas_old');
        Schema::rename('ebd_alunos', 'ebd_alunos_old');
        Schema::rename('ebd_professores', 'ebd_professores_old');
        Schema::rename('ebd_grupos_estudo', 'ebd_grupos_estudo_old');
        Schema::rename('ebd_grupo_membros', 'ebd_grupo_membros_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('ebd_turmas_old', 'ebd_turmas');
        Schema::rename('ebd_alunos_old', 'ebd_alunos');
        Schema::rename('ebd_professores_old', 'ebd_professores');
        Schema::rename('ebd_grupos_estudo_old', 'ebd_grupos_estudo');
        Schema::rename('ebd_grupo_membros_old', 'ebd_grupo_membros');
    }
};