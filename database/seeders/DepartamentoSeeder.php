<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Ministerio;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Criando departamentos...');

        // Obter ministérios para associar aos departamentos
        $ministerios = Ministerio::all();
        $louvor = $ministerios->where('nome', 'Louvor e Adoração')->first();
        $infantil = $ministerios->where('nome', 'Infantil')->first();
        $jovens = $ministerios->where('nome', 'Jovens')->first();
        $intercessao = $ministerios->where('nome', 'Intercessão')->first();
        $evangelismo = $ministerios->where('nome', 'Evangelismo')->first();
        $acao_social = $ministerios->where('nome', 'Ação Social')->first();
        $ensino = $ministerios->where('nome', 'Ensino')->first();
        $hospitalidade = $ministerios->where('nome', 'Hospitalidade')->first();
        $tecnologia = $ministerios->where('nome', 'Tecnologia')->first();
        $financas = $ministerios->where('nome', 'Finanças')->first();

        $departamentos = [
            [
                'nome' => 'Administrativo',
                'descricao' => 'Departamento responsável pela administração geral da igreja',
                'ministerio_id' => $financas->id,
                'ativo' => true
            ],
            [
                'nome' => 'Financeiro',
                'descricao' => 'Departamento responsável pela gestão financeira da igreja',
                'ministerio_id' => $financas->id,
                'ativo' => true
            ],
            [
                'nome' => 'Espiritual',
                'descricao' => 'Departamento responsável pela área espiritual e pastoral',
                'ministerio_id' => $intercessao->id,
                'ativo' => true
            ],
            [
                'nome' => 'Educacional',
                'descricao' => 'Departamento responsável pela educação cristã e Escola Dominical',
                'ministerio_id' => $ensino->id,
                'ativo' => true
            ],
            [
                'nome' => 'Social',
                'descricao' => 'Departamento responsável pela ação social e assistência',
                'ministerio_id' => $acao_social->id,
                'ativo' => true
            ],
            [
                'nome' => 'Tecnologia',
                'descricao' => 'Departamento responsável pela tecnologia e mídia da igreja',
                'ministerio_id' => $tecnologia->id,
                'ativo' => true
            ],
            [
                'nome' => 'Eventos',
                'descricao' => 'Departamento responsável pela organização de eventos e celebrações',
                'ministerio_id' => $hospitalidade->id,
                'ativo' => true
            ],
            [
                'nome' => 'Comunicação',
                'descricao' => 'Departamento responsável pela comunicação interna e externa',
                'ministerio_id' => $tecnologia->id,
                'ativo' => true
            ]
        ];

        foreach ($departamentos as $departamento) {
            Departamento::updateOrCreate(
                ['nome' => $departamento['nome']],
                $departamento
            );
        }

        $this->command->info('✅ Departamentos criados com sucesso');
        $this->command->info('📊 Total de departamentos: ' . count($departamentos));
        
        // Estatísticas
        $ativos = collect($departamentos)->where('ativo', true)->count();
        
        $this->command->info("✅ Ativos: {$ativos}");
    }
} 