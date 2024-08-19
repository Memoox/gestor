<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('cat_tipo_usuario')->insert([
            'nombre' => 'superadmin',
        ]);
        DB::table('cat_tipo_usuario')->insert([
            'nombre' => 'administrador',
        ]);
        DB::table('cat_tipo_usuario')->insert([
            'nombre' => 'carga',
        ]);
       
        
    }
}
