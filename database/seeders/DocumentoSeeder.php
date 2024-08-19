<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('documentos')->insert([
        //     'folio' => 1,
        //     'no_documento' => '1234',
        //     'fecha_ingreso' => '2023-01-01',
        //     'fecha_emision' => '2023-01-01',
        //     'fecha_recepcion' => '2023-01-01',
        //     'emisor' => 'MELISSA JAULI GUTIÉRREZ',
        //     'cargo' => 'TITULAR DE LA DIRECCIÓN DE ATENCIÓN CIUDADANA',
        //     'asunto' => 'REMITE PETICIÓN DEL C. RAFAEL GASCA PASTEIN CON NÚMERO DE SEGUIMIENTO 171-0382, EN EL QUE ',
        //     'dependencia' => 'OFICINA DEL GOBERNADOR',
        //     'dirigido_a' => 'HELIODORO LUNA VITE',
        //     'cargo_a' => 'SECRETARIO DE INFRAESTRUCTURA',
        //     'observaciones_a' => 'LA OFICINA DEL GOBERNADOR SOLICITA DAR RESPUESTA DENTRO DE UN PLAZO DE 10 DÍAS.  ',
        //     'dependencia_a' => 'SECRETARIA DE INFRAESTRUCTURA',
        //     'tipo_archivo' => 'Recibido',
        //     // 'seccion_id' => 1,
        //     // 'serie_id' => 1,
        //     'tipo_documento_id' => 1,
        //     'departamento_id' => 1,
        //     'prioridad_id' => 1,
        //     'categoria_id' => 1,
        //     'estatus_id' => 1,
        // ]);
    }
}
