<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Festa'],
            ['nome' => 'Conferência'],
            ['nome' => 'Workshop'],
            ['nome' => 'Show'],
            ['nome' => 'Esporte'],
            ['nome' => 'Cultural'],
            ['nome' => 'Religioso'],
            ['nome' => 'Outro'],
        ];

        DB::table('app_categorias')->insert($categorias);
    }
}