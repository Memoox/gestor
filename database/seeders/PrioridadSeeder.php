<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrioridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_prioridad')->insert([
            'nombre' => 'Extra Urgente',
            'tiempo_respuesta' => '24 Horas'
        ]);
        DB::table('cat_prioridad')->insert([
            'nombre' => 'Urgente',
            'tiempo_respuesta' => '48 Horas'
        ]);
        DB::table('cat_prioridad')->insert([
            'nombre' => 'Normal',
            'tiempo_respuesta' => '72 Horas'
        ]);
        DB::table('cat_prioridad')->insert([
            'nombre' => 'Conocimiento',
            'tiempo_respuesta' => '-'
        ]);
    }
}
