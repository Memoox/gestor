<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\DB;

class TipoDocumentoController extends Controller
{
    public function getTiposDocumentos(){
        try {
            $tipos_documentos = TipoDocumento::where('status', 1)->get();
             
            $array_tipos_documentos = array();
            $cont = 1;
            foreach ($tipos_documentos as $tipo_documento) {
                $objectTIpoDocumento = new \stdClass();
                $objectTIpoDocumento->id = $tipo_documento->id;
                $objectTIpoDocumento->nombre = $tipo_documento->nombre;
                $objectTIpoDocumento->numero_registro = $cont;
                array_push($array_tipos_documentos, $objectTIpoDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Tipos de documentos obtenidos con exito",
                "tipos_documentos" => $array_tipos_documentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de tipos de documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function nuevoTipoDocumento(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $TipoDocumento = new TipoDocumento;
            $TipoDocumento->nombre = $request->nombre;
            
            $TipoDocumento->save();

            $TipoDocumentos = TipoDocumento::where('status', 1)->get();

            $array = array();
            $cont = 1;
            foreach ($TipoDocumentos as $TipoDocumento) {
                $objectTipoDocumento = new \stdClass();
                $objectTipoDocumento->id = $TipoDocumento->id;
                $objectTipoDocumento->numero_registro = $cont;
                $objectTipoDocumento->nombre = $TipoDocumento->nombre;

                array_push($array, $objectTipoDocumento);
                $cont++;
            }
         
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al dar de alta el Tipo de Documento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Tipo Documento agregado con éxito.",
                "tipos_documentos" => $array
            ], 200);
        }
    }


    public function actualizarTipoDocumento(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $TipoDocumento = TipoDocumento::find($request->id);
            $TipoDocumento->nombre = $request->nombre;

            $TipoDocumento->save();

            $TipoDocumentos = TipoDocumento::where('status', 1)->get();

            $array = array();
            $cont = 1;
            foreach ($TipoDocumentos as $TipoDocumento) {
                $objectTipoDocumento = new \stdClass();
                $objectTipoDocumento->id = $TipoDocumento->id;
                $objectTipoDocumento->numero_registro = $cont;
                $objectTipoDocumento->nombre = $TipoDocumento->nombre;

                array_push($array, $objectTipoDocumento);
                $cont++;
            }
         
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizar los datos del Tipo de Documento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Tipo de Documento actualizado con éxito.",
                "tipos_documentos" => $array
            ], 200);
        }
    }

    public function eliminarTipoDocumento (Request $request){
        $exito = false;

        DB::beginTransaction();
        try {
            $TipoDocumento = TipoDocumento::find($request->id);
            $TipoDocumento->status = false;
            
            $TipoDocumento->save();

            $TipoDocumentos = TipoDocumento::where('status', 1)->get();

            $array = array();
            $cont = 1;
            foreach ($TipoDocumentos as $TipoDocumento) {
                $objectTipoDocumento = new \stdClass();
                $objectTipoDocumento->id = $TipoDocumento->id;
                $objectTipoDocumento->numero_registro = $cont;
                $objectTipoDocumento->nombre = $TipoDocumento->nombre;

                array_push($array, $objectTipoDocumento);
                $cont++;
            }             
         
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al eliminar el Tipo de Documento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Tipo de Documento eliminado con éxito.",
                "tipos_documentos" => $array
            ], 200);
        }

    }
}
