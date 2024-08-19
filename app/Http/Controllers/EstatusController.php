<?php

namespace App\Http\Controllers;

use App\Models\Estatus;
use Illuminate\Http\Request;

class EstatusController extends Controller
{
    public function getEstatus(){
        try {
            $estatus = Estatus::where('status', 1)->get();
             
            $array_estatus = array();
            foreach ($estatus as $e) {
                $objectEstatus = new \stdClass();
                $objectEstatus->id = $e->id;
                $objectEstatus->nombre = $e->nombre;
                
                array_push($array_estatus, $objectEstatus);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Catalogo de estatus obtenidos con exito",
                "estatus" => $array_estatus
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de estatus",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
}
