<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_categoria')->insert([
            'nombre' => 'Documento del Gobernador',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Oficina del Gobernador',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Oficina del Secretario',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Coordinación General Jurídica',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Transparencia',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Archivo',
        ]);
        DB::table('cat_categoria')->insert([
            'nombre' => 'Copia de Conocimiento',
        ]);
    }
}
