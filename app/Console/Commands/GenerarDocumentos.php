<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Faker\Factory;
use Carbon\CarbonPeriod;
use App\Models\Documento;
use Illuminate\Console\Command;

class GenerarDocumentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generar:documentos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("========== Generando registros de prueba ==========");
        for ($i=1; $i <= 200; $i++) {
            $faker = Factory::create();
            $faker2 = Factory::create();

            $this->info("Generando documento: " . $i+1);
            $doc = new Documento;
            $doc->folio = $i;
            $doc->no_documento = $i;
            $fecha_random_a = Carbon::today()->subDays(rand(1, 365));
            $doc->fecha_emision = $fecha_random_a->toDateString();
            $doc->fecha_recepcion = $fecha_random_a->toDateString();
            $doc->emisor = $faker->name;
            $doc->cargo = $faker->jobTitle;
            $doc->asunto = $faker->sentence($nbWords = 6, $variableNbWords = true);
            $doc->dependencia = $faker->sentence($nbWords = 10, $variableNbWords = true);
            $doc->dirigido_a = $faker2->name;
            $doc->cargo_a = $faker2->jobTitle;
            $doc->observaciones_a = $faker2->sentence($nbWords = 6, $variableNbWords = true);
            $doc->dependencia_a = $faker2->sentence($nbWords = 10, $variableNbWords = true);
            $tipo_archivo = '';
            $n_tipo = rand(1,2);
            if ($n_tipo == 1) {
                $tipo_archivo = 'Recibido';
            } else if ($n_tipo == 2) {
                $tipo_archivo = 'Enviado';
            }
            $doc->tipo_archivo = $tipo_archivo;
            $doc->tipo_documento_id = rand(1, 9);
            $doc->departamento_id = rand(1, 30);
            $doc->prioridad_id = rand(1, 3);
            $doc->categoria_id = rand(1, 7);
            $doc->estatus_id = rand(1, 4);
            $doc->save();
        }
        $this->info("========== Comando finalizado ==========");
    }
}
