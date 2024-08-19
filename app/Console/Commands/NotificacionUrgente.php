<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Models\Documento;
use App\Mail\NotificarUrgente;

class NotificacionUrgente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urgente';

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
        $documentos = Documento::where('prioridad_id', 1)->where('estatus_id','!=',3)->get();
            foreach($documentos as $documento){
                $folio = $documento->folio;
                Mail::to('alejandro.ramirez@pjpuebla.gob.mx')->send(new NotificarUrgente($folio));

            }
       
    }
}
