<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membro;
use App\Models\Cargo;
use App\Models\MembroCargo;

class MembroCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👔 Associando membros aos cargos...');

        // Obter membros e cargos
        $membros = Membro::all();
        $cargos = Cargo::all();

        // Mapeamento de cargos específicos
        $cargoMappings = [
            'Pastor' => ['Pr. João Silva'],
            'Tesoureiro' => ['Maria Santos'],
            'Secretário' => ['Pedro Santos'],
            'Líder de Ministério' => ['Ana Costa', 'Carlos Oliveira', 'Lucia Ferreira', 'Roberto Lima', 'Fernanda Costa', 'Ricardo Almeida'],
            'Professor EBD' => ['Roberto Silva Costa'],
            'Músico' => ['Pedro Costa Lima'],
            'Cantor' => ['Ana Paula Ferreira'],
            'Intercessor' => ['André Luiz Ferreira'],
            'Evangelista' => ['Marcos Antonio Pereira'],
            'Líder de Jovens' => ['Fernanda Lima Santos'],
            'Líder de Crianças' => ['Maria Santos Oliveira'],
            'Técnico de Som' => ['Ricardo Almeida Oliveira'],
            'Diácono' => ['Carlos Eduardo Almeida'],
            'Presbítero' => ['Lucia Helena Rodrigues'],
            'Conselheiro' => ['Carlos Eduardo Almeida', 'Lucia Helena Rodrigues'],
            'Auxiliar' => ['Juliana Santos Costa', 'Camila Oliveira Lima'],
            'Membro' => $membros->pluck('nome')->toArray()
        ];

        $associacoes = [];

        foreach ($cargoMappings as $cargoNome => $membroNomes) {
            $cargo = $cargos->where('nome', $cargoNome)->first();
            
            if ($cargo) {
                foreach ($membroNomes as $membroNome) {
                    $membro = $membros->where('nome', $membroNome)->first();
                    
                    if ($membro) {
                        $associacoes[] = [
                            'membro_id' => $membro->id,
                            'cargo_id' => $cargo->id,
                            'data_inicio' => now()->subMonths(rand(1, 24)),
                            'data_fim' => null,
                            'ativo' => true
                        ];
                    }
                }
            }
        }

        // Adicionar alguns cargos extras para membros aleatórios
        $cargosExtras = ['Auxiliar', 'Membro'];
        foreach ($cargosExtras as $cargoNome) {
            $cargo = $cargos->where('nome', $cargoNome)->first();
            
            if ($cargo) {
                $membrosSemCargo = $membros->filter(function ($membro) use ($associacoes) {
                    return !collect($associacoes)->contains('membro_id', $membro->id);
                });
                
                foreach ($membrosSemCargo->take(5) as $membro) {
                    $associacoes[] = [
                        'membro_id' => $membro->id,
                        'cargo_id' => $cargo->id,
                        'data_inicio' => now()->subMonths(rand(1, 12)),
                        'data_fim' => null,
                        'ativo' => true
                    ];
                }
            }
        }

        // Criar as associações
        foreach ($associacoes as $associacao) {
            MembroCargo::updateOrCreate(
                [
                    'membro_id' => $associacao['membro_id'],
                    'cargo_id' => $associacao['cargo_id']
                ],
                $associacao
            );
        }

        $this->command->info('✅ Associações de membros e cargos criadas com sucesso');
        $this->command->info('📊 Total de associações: ' . count($associacoes));
        
        // Mostrar estatísticas
        $cargosUnicos = collect($associacoes)->pluck('cargo_id')->unique()->count();
        $membrosComCargo = collect($associacoes)->pluck('membro_id')->unique()->count();
        
        $this->command->info("👔 Cargos utilizados: {$cargosUnicos}");
        $this->command->info("👥 Membros com cargo: {$membrosComCargo}");
    }
} 