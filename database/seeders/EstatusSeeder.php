<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_estatus')->insert([
            'nombre' => 'Iniciado',
        ]);
        DB::table('cat_estatus')->insert([
            'nombre' => 'En proceso',
        ]);
        DB::table('cat_estatus')->insert([
            'nombre' => 'Atendido',
        ]);
        DB::table('cat_estatus')->insert([
            'nombre' => 'Sin Atender',
        ]);
    }
}
