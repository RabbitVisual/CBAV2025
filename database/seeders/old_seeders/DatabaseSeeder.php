<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            InitialDataSeeder::class,
            HomeConfigSeeder::class,
            GatewaySettingsSeeder::class,
            TimezoneSeeder::class, // Configurar fuso horário
            DemoDataSeeder::class, // Dados demonstrativos
        ]);
    }
}
