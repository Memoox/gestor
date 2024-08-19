<?php

namespace App\Http\Controllers;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;

class TipoUsuarioController extends Controller
{
    public function getTiposUsuarios(){
        try {
            $tipoUsuarios = TipoUsuario::where('id', '!=', 1)->get();
             
            $array_tipoUsuarios = array();
            foreach ($tipoUsuarios as $tipoUsuario) {
                $objectTipousuario = new \stdClass();
                $objectTipousuario->id = $tipoUsuario->id;
                $objectTipousuario->nombre = $tipoUsuario->nombre;
                array_push($array_tipoUsuarios, $objectTipousuario);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Tipos de suarios obtenidos con exito",
                "tipoUsuario" => $array_tipoUsuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de tipos de usuarios",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
}
