<?php

namespace App\Http\Controllers;

use App\Models\Prioridad;
use Illuminate\Http\Request;

class PrioridadController extends Controller
{
    public function getPrioridades(){
        try {
            $prioridades = Prioridad::where('status', 1)->get();
             
            $array_prioridades = array();
            foreach ($prioridades as $prioridad) {
                $objectPrioridad = new \stdClass();
                $objectPrioridad->id = $prioridad->id;
                $objectPrioridad->nombre = $prioridad->nombre;
                
                array_push($array_prioridades, $objectPrioridad);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Prioridades obtenidos con exito",
                "prioridades" => $array_prioridades
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de prioridades",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
}
