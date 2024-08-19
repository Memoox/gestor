<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\User;
use App\Models\Documento;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotaController extends Controller
{
    public function guardarNotas(Request $request){
        $exito = false;
        DB::beginTransaction();

        try {
            $documento = Documento::find($request->documento_id);

            $nota = new Nota;
            $nota->nota = $request->nota;
            $nota->documento_id = $documento->id;
            
            $nota->save();

            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->whereIn('estatus_id',[1,2,4])->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->whereIn('estatus_id',[1,2,4])->where('status', 1)->get();
            }
             
            $arrayDocumentos = array();
            $cont = 1;
            foreach ($documentos as $documento) {
                $objectDocumento = new \stdClass();
                $objectDocumento->id = $documento->id;
                $objectDocumento->numero_registro = $cont;
                $objectDocumento->folio = $documento->folio ? $documento->folio : ' ';
                $objectDocumento->no_documento = $documento->no_documento ? $documento->no_documento : ' ';
                $objectDocumento->fecha_ingreso = $documento->fecha_ingreso;
                $objectDocumento->fecha_emision = $documento->fecha_emision;
                $objectDocumento->fecha_recepcion = $documento->fecha_recepcion ? $documento->fecha_recepcion : ' ';
                $objectDocumento->fecha_recepcion_area = $documento->fecha_recepcion_area ? $documento->fecha_recepcion_area : ' ';
                $objectDocumento->emisor = $documento->emisor ? $documento->emisor : ' ';
                $objectDocumento->cargo = $documento->cargo ? $documento->cargo : ' ';
                $objectDocumento->asunto = $documento->asunto ? $documento->asunto : ' ';
                $objectDocumento->dependencia = $documento->dependencia ? $documento->dependencia : ' ';
                $objectDocumento->dirigido_a = $documento->dirigido_a;
                $objectDocumento->cargo_a = $documento->cargo_a;
                $objectDocumento->observaciones_a = $documento->observaciones_a ? $documento->observaciones_a : ' ';
                $objectDocumento->dependencia_a = $documento->dependencia_a ? $documento->dependencia_a : ' ';
                // $objectDocumento->tipo_archivo = $documento->tipo_archivo;
                $objectDocumento->archivo = $documento->archivo;
                $objectDocumento->enlace = $documento->enlace;
                $objectDocumento->status = $documento->status;
                $objectDocumento->tipo_documento = $documento->tipoDocumento ? $documento->tipoDocumento->nombre : '';
                // $objectDocumento->departamento = $documento->departamento->nombre;
                // if($documento->departamento){
                //     $objectDocumento->departamento = $documento->departamento->nombre;
                // }else{
                //     $objectDocumento->departamento = ' ';
                // }
                // if($documento->prioridad){
                //     $objectDocumento->prioridad = $documento->prioridad->nombre;
                // }else {
                //     $objectDocumento->prioridad = '';
                // }
                $objectDocumento->departamento = $documento->departamento ? $documento->departamento->nombre : ' ';
                $objectDocumento->prioridad = $documento->prioridad ? $documento->prioridad->nombre : ' ';
                $objectDocumento->estatus = $documento->estatus ? $documento->estatus->nombre: '';
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->get();
                foreach($archivos as $archivo){
                    $objectArchivo = new \stdClass();
                    $objectArchivo->id = $archivo->id;
                    $objectArchivo->documento_id = $archivo->documento_id;
                    $objectArchivo->archivo = Storage::url($archivo->archivo);
                    array_push($array_archivos,$objectArchivo);
                }
                // $objectDocumento->prioridad = $documento->prioridad->nombre;
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->tiene_archivo = $archivos ? true : false;
                $objectDocumento->archivo = $array_archivos;
                $objectDocumento->notas = $documento->notas;
                $objectDocumento->estatus_id = $documento->estatus_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al almacenar las notas",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Notas almacenadas con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        }
    }
}
