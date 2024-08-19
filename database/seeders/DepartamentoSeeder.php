<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departamentos')->insert([
            'nombre' => 'SUPERADMINISTRADOR',
            'status' => 0,
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'ADMINISTRADOR',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'PRESIDENCIA DEL TRIBUNAL',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'PRESIDENCIA DEL CONSEJO',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'SECRETARÍA JURÍDICA DEL CONSEJO DE LA JUDICATURA',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'SECRETARÍA TÉCNICA DEL CONSEJO DE LA JUDICATURA',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'SECRETARÍA DE PLENO DEL TRIBUNAL SUPERIOR DE JUSTICIA',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'TERCERA PONENCIA DEL CONSEJO DE LA JUDICATURA DEL PODER JUDICIAL DEL ESTADO DE PUEBLA',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'SEGUNDA PONENCIA DEL CONSEJO DE LA JUDICATURA DEL PODER JUDICIAL DEL ESTADO DE PUEBLA',
        ]);
        DB::table('departamentos')->insert([
            'nombre' => 'OFICIALIA',
        ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ÓRGANO INTERNO DE CONTROL DEL CONSEJO DE LA JUDICATURA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ADMINISTRADORA GENERAL DE LOS JUZGADOS EN MATERIA CIVIL Y MERCANTIL',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE ARCHIVOS DE LA SECRETARÍA JURÍDICA DEL CONSEJO DE LA JUDICATURA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE LA UNIDAD DE VINCULACIÓN Y ATENCIÓN CIUDADANA DEL CONSEJO DE LA JUDICATURA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN JURIDICA DEL ORGANO DE CONTROL INTERNO DEL CONSEJO DE LA JUDICATURA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'SECRETARÍA AUXILIAR',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'SECRETARIA DE ADMINISTRACIÓN DE CONSEJO DE LA JUDICATURA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'SECRETARÍA DE ACUERDOS',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE PRESUPUESTO Y RECURSOS FINANCIEROS',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DEPARTAMENTO DE RECURSOS MATERIALES,  ENCARGADO DE PROYECTOS, MANTENIMIENTO Y CONSERVACIÓN PATRIMONIAL',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'CONTRALORÍA DEL PODER JUDICIAL DEL ESTADO',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DEPARTAMENTO DE ADQUISICIONES',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE INFORMÁTICA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DEPARTAMENTO DE DEPÓSITOS, FINANZAS Y MULTAS',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'TESORERIA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'UNIDAD DE TRANSPARENCIA Y ACCESO A LA INFORMACIÓN',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'UNIDAD DE ESTADÍSTICA JUDICIAL',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'CENTRO DE JUSTICIA ALTERNATIVA',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ESCUELA ESTATAL DE FORMACIÓN JUDICIAL',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE ARCHIVO JUDICIAL',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ENC. DIRECCIÓN DE RECURSOS HUMANOS',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ARCHIVO',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'AUDITORÍA SUPERIOR',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE RECURSOS HUMANOS',
        // ]);
        // DB::table('departamentos')->insert([
        //     'nombre' => 'ENCARGADO DE VEHÍCULOS',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'CENTRAL DE DILIGENCIARIOS DE PUEBLA',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECTOR DE SERVICIOS GENERALES Y RECURSOS  MATERIALES DEL CONSEJO DE LA JUDICATURA DEL PODER JUDICIAL DEL ESTADO',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'SUBDIRECCIÓN DE PRESUPUESTO Y RECURSOS FINANCIEROS',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'PROTECCIÓN CIVIL',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'UNIDAD DE FUNCIÓN DE CUMPLIMIENTO',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'PROTECCIÓN CIVIL METROPOLITANA',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'SERVICIOS GENERALES DEL CONSEJO DE LA JUDICATURA DEL PODER JUDICIAL',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'DEPARTAMENTO DE RECURSOS MATERIALES',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE PRESUPUESTO Y RECURSOS FINANCIEROS',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'UNIDAD DE DERECHOS HUMANOS E IGUALDAD DE GENERO',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'SECRETARIO RELATOR DE LOS ASUNTOS DEL TRIBUNAL SUPERIOR DE JUSTICIA DEL ESTADO DE PUEBLA',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE RECURSOS HUMANOS DEL CONSEJO DE LA JUDICATURA DEL ESTADO DE PUEBLA',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'Dirección de Servicios Generales y Recursos Materiales del Consejo de la Judicatura del Poder Judicial del Estado',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'DIRECCIÓN DE INFORMATICA DEL CONSEJO DE LA JUDICATURA DELPODER JUDICIAL DEL ESTADO DE PUEBLA',
        // ]);

        // DB::table('departamentos')->insert([
        //     'nombre' => 'ADQUISICIONES',
        // ]);
        
    }
}
