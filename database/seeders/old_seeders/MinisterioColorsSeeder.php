<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ministerio;

class MinisterioColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cores claras que garantem boa visibilidade dos ícones escuros
        $coresClaras = [
            '#f3f4f6', // gray-100
            '#fef3c7', // yellow-100
            '#dbeafe', // blue-100
            '#dcfce7', // green-100
            '#f3e8ff', // purple-100
            '#fce7f3', // pink-100
            '#ecfdf5', // emerald-100
            '#fef2f2', // red-100
            '#fffbeb', // amber-100
            '#f0f9ff', // sky-100
        ];

        $ministerios = Ministerio::all();
        $corIndex = 0;

        foreach ($ministerios as $ministerio) {
            // Se a cor atual for muito escura, substituir por uma clara
            $corAtual = $ministerio->cor;
            
            // Verificar se a cor é muito escura (hex para rgb e verificar luminosidade)
            if ($this->isDarkColor($corAtual)) {
                $ministerio->update([
                    'cor' => $coresClaras[$corIndex % count($coresClaras)]
                ]);
            }
            
            $corIndex++;
        }

        $this->command->info('Cores dos ministérios atualizadas para tons claros!');
    }

    /**
     * Verifica se uma cor é escura baseada na luminosidade
     */
    private function isDarkColor($hexColor)
    {
        // Remover # se presente
        $hex = ltrim($hexColor, '#');
        
        // Converter para RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calcular luminosidade (fórmula padrão)
        $luminosidade = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Retorna true se a cor for escura (luminosidade < 0.5)
        return $luminosidade < 0.5;
    }
} 