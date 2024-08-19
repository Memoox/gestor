<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Oficio',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Memorándum',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Memorándum Circular',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Tarjeta Informativa',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'e-mail',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Oficio Invitación',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Sobre cerrado',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Solicitud Ciudadana',
        ]);
        DB::table('cat_tipo_doc')->insert([
            'nombre' => 'Solicitud por Sector',
        ]);
    }
}
