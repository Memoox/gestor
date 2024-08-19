<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Documento;
use App\Models\Evidencia;
use App\Models\Archivo;
use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Mail\DocumentoTurnado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DepartamentoController;
use App\Exports\DocumentosExport;
use App\Exports\ExportReporte;
use Illuminate\Support\Str;
use PDF;
// use DB;


class DocumentoController extends Controller
{
    public function getEstadisticas(){
        try {
            $user = Auth::user();
            
            $objectDashboard = new \stdClass();

            if ($user->tipo_usuario_id == 1) {
                $total_documentos = Documento::where('status', 1)->count();
                $extra_urgente = Documento::where('prioridad_id', 1)->where('status', 1)->count();
                $urgente = Documento::where('prioridad_id', 2)->where('status', 1)->count();
                $normal = Documento::where('prioridad_id', 3)->where('status', 1)->count();
                $sin_atender = Documento::where('estatus_id', 4)->where('status', 1)->count();
                $iniciados = Documento::where('estatus_id', 1)->where('status', 1)->count();
                $en_proceso = Documento::where('estatus_id', 2)->where('status', 1)->count();
                $atendidos = Documento::where('estatus_id', 3)->where('status', 1)->count();
            } else {
                $total_documentos = Documento::where('departamento_id', $user->departamento_id)->where('status', 1)->count();
                $extra_urgente = Documento::where('departamento_id', $user->departamento_id)->where('prioridad_id', 1)->where('status', 1)->count();
                $urgente = Documento::where('departamento_id', $user->departamento_id)->where('prioridad_id', 2)->where('status', 1)->count();
                $normal = Documento::where('departamento_id', $user->departamento_id)->where('prioridad_id', 3)->where('status', 1)->count();
                $sin_atender = Documento::where('departamento_id', $user->departamento_id)->where('estatus_id', 4)->where('status', 1)->count();
                $iniciados = Documento::where('departamento_id', $user->departamento_id)->where('estatus_id', 1)->where('status', 1)->count();
                $en_proceso = Documento::where('departamento_id', $user->departamento_id)->where('estatus_id', 2)->where('status', 1)->count();
                $atendidos = Documento::where('departamento_id', $user->departamento_id)->where('estatus_id', 3)->where('status', 1)->count();
            }

            $objectDashboard->total_documentos = $total_documentos;
            $objectDashboard->documentos_extraurgente = $extra_urgente;
            $objectDashboard->porcent_1 = $total_documentos > 0 ? ($extra_urgente * 100) / $total_documentos : 0;
            $objectDashboard->documentos_urgente = $urgente;
            $objectDashboard->porcent_2 = $total_documentos > 0 ? ($urgente * 100) / $total_documentos : 0;
            $objectDashboard->documentos_normal = $normal;
            $objectDashboard->porcent_3 = $total_documentos > 0 ? ($normal * 100) / $total_documentos : 0;

            $objectDashboard->documentos_sinatender = $sin_atender;
            $objectDashboard->porcent_4 = $total_documentos > 0 ? ($sin_atender * 100) / $total_documentos : 0;
            $objectDashboard->documentos_iniciados = $iniciados;
            $objectDashboard->porcent_5 = $total_documentos > 0 ? ($iniciados * 100) / $total_documentos : 0;
            $objectDashboard->documentos_enproceso = $en_proceso;
            $objectDashboard->porcent_6 = $total_documentos > 0 ? ($en_proceso * 100) / $total_documentos : 0;
            $objectDashboard->documentos_atendidos = $atendidos;
            $objectDashboard->porcent_7 = $total_documentos > 0 ? ($atendidos * 100) / $total_documentos : 0;
            
            return response()->json([
                "status" => "ok",
                "message" => "Estadísticas obtenidas con éxito",
                "estadisticas" => $objectDashboard
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener las estadísticas",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentos(){
        try {
            // $documentos = Documento::orderBy('folio_num', 'desc')->where('status', 1)->where('estatus_id','!=', 3)->get();
            $documentos = Documento::orderBy('folio_num', 'desc')->where('status', 1)->get();
            
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
                // // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                foreach($archivos as $archivo){
                    $objectArchivo = new \stdClass();
                    $objectArchivo->id = $archivo->id;
                    $objectArchivo->documento_id = $archivo->documento_id;
                    $objectArchivo->archivo = Storage::url($archivo->archivo);
                    array_push($array_archivos,$objectArchivo);
                }
                $objectDocumento->tiene_archivo = $archivos ? true : false;
                $objectDocumento->archivo = $array_archivos;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                // $objectDocumento->categoria_id = $documento->categoria_id;
                // $objectDocumento->tiene_archivo2 = $documento->archivo2 ? true : false;
                // $objectDocumento->archivo2 = Storage::url($documento->archivo2);
                
                array_push($arrayDocumentos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosHome(){
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                // $documentos = Documento::orderBy('id', 'desc')->whereIn('estatus_id',[1,2,3,4])->where('status', 1)->get();
                $documentos = Documento::orderBy('folio_num', 'desc')->where('status', 1)->get();
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
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                foreach($archivos as $archivo){
                    $objectArchivo = new \stdClass();
                    $objectArchivo->id = $archivo->id;
                    $objectArchivo->documento_id = $archivo->documento_id;
                    $objectArchivo->archivo = Storage::url($archivo->archivo);
                    array_push($array_archivos,$objectArchivo);
                }
                $objectDocumento->tiene_archivo = $archivos ? true : false;
                $objectDocumento->archivo = $array_archivos;
                // $objectDocumento->tiene_archivo = $documento->archivo ? true : false;
                // $objectDocumento->archivo_mostrar = Storage::url($documento->archivo);
                $objectDocumento->notas = $documento->notas;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                // $objectDocumento->tiene_archivo2 = $documento->archivo2 ? true : false;
                // $objectDocumento->archivo2 = Storage::url($documento->archivo2);
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function guardarNuevoDocumento(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        

        try {
            $user = Auth::user();
            $no_dpto = 0;
            if($request->departamentos_a){

            
                foreach($request->departamentos_a as $deptos){

                        $folio=intval($request->folio);

                        $documento = new Documento;
                        $documento->folio = $request->folio;
                        $documento->folio_num = $folio;
                        $documento->no_documento = $request->no_documento;
                        $documento->fecha_emision = $request->fecha_emision;
                        $documento->fecha_recepcion = $request->fecha_recepcion;
                        $documento->emisor = $request->emisor;
                        $documento->cargo = $request->cargo;
                        $documento->asunto = $request->asunto;
                        $documento->dependencia = $request->dependencia;
                        $documento->dirigido_a = $request->dirigido_a;
                        $documento->cargo_a = $request->cargo_a;
                        $documento->observaciones_a = $request->observaciones_a;
                        $documento->dependencia_a = $request->dependencia_a;
                        // $documento->tipo_archivo = $request->tipo_archivo;
                        $documento->tipo_documento_id = $request->tipo_documento;
                        $documento->departamento_id = $request->departamentos_a[$no_dpto];
                        $documento->prioridad_id = $request->prioridad_id;
                        // $documento->categoria_id = $request->categoria_id;
                        $documento->enlace = $request->enlace;
                        $documento->estatus_id = 4;
                        $documento->ultimo_usuario = $user->id;

                        $documento->save();
                        $nuevo_doc = $documento;
                        // $ruta ='';
                        if ($request->file('archivo')) {
                            $files = $request->file('archivo');
                            foreach($files as $file){
                                $current_day = Carbon::now();
                                $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                                $extension = $file->getClientOriginalExtension();
                                $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                                $path = $file->storeAs('public', $fileNameToStore);
                                $archivo = new Archivo;
                                $archivo->documento_id = $documento->id;
                                $archivo->archivo = $path;
                                $archivo->save();
                            }
                         
                        }
                        
                        $no_dpto++;
                        if($documento->notificado == 0){
                            if($documento->departamento->usuario){
                                $correo = $documento->departamento->usuario->email;
                                Mail::to($correo)->send(new DocumentoTurnado($documento->folio));
                                $documento->notificado = 1;
                                $documento->save();
                            }
                        }
                        

                }
            }
            else{
                $folio=intval($request->folio);
                $ruta ='';
                $documento = new Documento;
                $documento->folio = $request->folio;
                $documento->folio_num = $folio;
                $documento->no_documento = $request->no_documento;
                $documento->fecha_emision = $request->fecha_emision;
                $documento->fecha_recepcion = $request->fecha_recepcion;
                $documento->emisor = $request->emisor;
                $documento->cargo = $request->cargo;
                $documento->asunto = $request->asunto;
                $documento->dependencia = $request->dependencia;
                $documento->dirigido_a = $request->dirigido_a;
                $documento->cargo_a = $request->cargo_a;
                $documento->observaciones_a = $request->observaciones_a;
                $documento->dependencia_a = $request->dependencia_a;
                // $documento->tipo_archivo = $request->tipo_archivo;
                $documento->tipo_documento_id = $request->tipo_documento;
                // $documento->departamento_id = $request->departamentos_a[$no_dpto];
                $documento->prioridad_id = $request->prioridad_id;
                // $documento->categoria_id = $request->categoria_id;
                $documento->enlace = $request->enlace;
                $documento->estatus_id = 4;
                $documento->ultimo_usuario = $user->id;

                $documento->save();
                $nuevo_doc = $documento;
                
                if ($request->file('archivo')) {
                    $files = $request->file('archivo');
                    foreach($files as $file){
                        $current_day = Carbon::now();
                        $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                        $path = $file->storeAs('public', $fileNameToStore);
                        $archivo = new Archivo;
                        $archivo->documento_id = $documento->id;
                        $archivo->archivo = $path;
                        $archivo->save();
                       
                    }
                }
               

            }
           
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $documentos = Documento::orderBy('folio_num', 'desc')->where('status',1)->get();

            $arrayDocumentos = array();
            $cont = 1;
            foreach($documentos as $documento) {
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
                $objectDocumento->archivo = $documento->archivo;
                $objectDocumento->enlace = $documento->enlace;
                $objectDocumento->status = $documento->status;
                $objectDocumento->tipo_documento = $documento->tipoDocumento ? $documento->tipoDocumento->nombre : '';
               
                $objectDocumento->departamento = $documento->departamento ? $documento->departamento->nombre : ' ';
                $objectDocumento->prioridad = $documento->prioridad ? $documento->prioridad->nombre : ' ';
                
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                foreach($archivos as $archivo){
                    $objectArchivo = new \stdClass();
                    $objectArchivo->id = $archivo->id;
                    $objectArchivo->documento_id = $archivo->documento_id;
                    $objectArchivo->archivo = Storage::url($archivo->archivo);
                    array_push($array_archivos,$objectArchivo);
                }
                $objectDocumento->tiene_archivo = $archivos ? true : false;
                $objectDocumento->archivo = $array_archivos;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;

                array_push($arrayDocumentos,$objectDocumento);
                $cont++;
            }

            DB::commit();
           
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al agregar un documento nuevo.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Nuevo documento agregado con exito.",
                "documentos" => $arrayDocumentos,
                "oficio" => $nuevo_doc
            ], 200);
        }
    }

    public function guardarEvidencia(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $documento = Documento::find($request->id);
            $documento->estatus_id = $request->estatus;
            $documento->save();
            
            $evidencia = new Evidencia;
            $evidencia->documento_id = $documento->id;
            $evidencia->estatus_id = $request->estatus;
            $evidencia->descripcion = $request->descripcion;
            $evidencia->save();

            if ($request->file('archivo')) {
                $current_day = Carbon::now();
                $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                $file = $request->file('archivo');
                $extension = $file[0]->getClientOriginalExtension();
                $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                $path = $request->file('archivo')[0]->storeAs('public/documentos', $fileNameToStore);
                $evidencia->archivo = $path;
                $evidencia->save();
            }

            $evidencias = Evidencia::all();

            $array_evidencias = array();
            foreach($evidencias as $evidencia) {
                $objectEvidencia = new \stdClass();
                $objectEvidencia->estatus = $evidencia->documento_id;
                $objectEvidencia->descripcion = $evidencia->descripcion;                
                $objectEvidencia->archivo = Storage::url($evidencia->archivo);

                array_push($array_evidencias,$objectEvidencia);
            }

            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->whereIn('estatus_id',[1,2])->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->whereIn('estatus_id',[1,2])->where('status', 1)->get();
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
                // // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
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
                "message" => "Ocurrió un error al agregar nueva evidencia.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Nueva evidencia agregada con exito.",
                "evidencia" => $array_evidencias,
                "documentos" => $arrayDocumentos
            ], 200);
        }
    }

    public function actualizarDocumento(Request $request)
    {
        $exito = false;

        DB::beginTransaction();

        try {
            $user = Auth::user();
            if($request->departamento_id){
                if(count($request->departamento_id) > 1){
                    $tamaño = count($request->departamento_id);
                    $documento = Documento::find($request->id);

                    $folio=intval($request->folio);
                    $documento->id = $request->id;
                    $documento->folio = $request->folio;
                    $documento->folio_num = $folio;
                    $documento->no_documento = $request->no_documento;
                    $documento->fecha_emision = $request->fecha_emision;
                    $documento->fecha_recepcion = $request->fecha_recepcion;
                    $documento->fecha_recepcion_area = $request->fecha_recepcion_area;
                    $documento->emisor = $request->emisor;
                    $documento->cargo = $request->cargo;
                    $documento->asunto = $request->asunto;
                    $documento->dependencia = $request->dependencia;
                    $documento->dirigido_a = $request->dirigido_a;
                    $documento->cargo_a = $request->cargo_a;
                    $documento->observaciones_a = $request->observaciones_a;
                    $documento->dependencia_a = $request->dependencia_a;
                    $documento->tipo_documento_id = $request->tipo_documento;
                    $documento->departamento_id = intval($request->departamento_id[0]);
                    $documento->prioridad_id = $request->prioridad_id;
                    $documento->estatus_id = $request->estatus_id;
                    $documento->enlace = $request->enlace;
                    $documento->ultimo_usuario = $user->id;
                    $documento->save();

                    if($documento->notificado == 0){
                        if($documento->departamento->usuario){
                            $correo = $documento->departamento->usuario->email;
                            Mail::to($correo)->send(new DocumentoTurnado($documento->folio));
                            $documento->notificado = 1;
                            $documento->save();
                        }
                    }
                   
        
                    if ($request->file('archivo')) {
                        $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                        if($archivos){
                            foreach($archivos as $prueba){
                                $nombre= public_path().'/storage'.Str::substr($prueba->archivo,6);
                                unlink($nombre);                          
                                $prueba->status = 0;
                                $prueba->save();
                            }
                            $files = $request->file('archivo');
                            foreach($files as $file){
                            $current_day = Carbon::now();
                            $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                            $path = $file->storeAs('public', $fileNameToStore);
                            $archivo = new Archivo;
                            $archivo->documento_id = $documento->id;
                            $archivo->archivo = $path;
                            $archivo->save();
                            }
        
                        }else{
                            $files = $request->file('archivo');
                            foreach($files as $file){
                            $current_day = Carbon::now();
                            $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                            $path = $file->storeAs('public', $fileNameToStore);
                            $archivo = new Archivo;
                            $archivo->documento_id = $documento->id;
                            $archivo->archivo = $path;
                            $archivo->save();             
                            }
        
                        }
                        
                    
                    }
                    for($i=1; $i<$tamaño; $i++){
                        $documento_new = new Documento;
                        $documento_new->folio = $documento->folio;
                        $documento_new->folio_num = $documento->folio_num;
                        $documento_new->no_documento = $documento->no_documento;
                        $documento_new->fecha_emision = $documento->fecha_emision;
                        $documento_new->fecha_recepcion = $documento->fecha_recepcion;
                        $documento_new->fecha_recepcion_area = $documento->fecha_recepcion_area;
                        $documento_new->emisor = $documento->emisor;
                        $documento_new->cargo = $documento->cargo;
                        $documento_new->asunto = $documento->asunto;
                        $documento_new->dependencia = $documento->dependencia;
                        $documento_new->dirigido_a = $documento->dirigido_a;
                        $documento_new->cargo_a = $documento->cargo_a;
                        $documento_new->observaciones_a = $documento->observaciones_a;
                        $documento_new->dependencia_a = $documento->dependencia_a;
                        $documento_new->tipo_documento_id = $documento->tipo_documento;
                        $documento_new->departamento_id = intval($request->departamento_id[$i]);
                        $documento_new->prioridad_id = $documento->prioridad_id;
                        $documento_new->enlace = $documento->enlace;
                        $documento_new->estatus_id = 4;
                        $documento_new->ultimo_usuario = $user->id;
                        $documento_new->save();

                        if($documento_new->notificado == 0){
                            if($documento_new->departamento->usuario){
                                $correo = $documento_new->departamento->usuario->email;
                                Mail::to($correo)->send(new DocumentoTurnado($documento_new->folio));
                                $documento_new->notificado = 1;
                                $documento_new->save();
                            }
                        }

                        if ($request->file('archivo')) {
                           
                                    $files = $request->file('archivo');
                                    foreach($files as $file){
                                        $current_day = Carbon::now();
                                        $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                                        $extension = $file->getClientOriginalExtension();
                                        $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                                        $path = $file->storeAs('public', $fileNameToStore);
                                        $archivo = new Archivo;
                                        $archivo->documento_id = $documento_new->id;
                                        $archivo->archivo = $path;
                                        $archivo->save();
                                    }
                             
                            }
                            
                    }

                }else{
                    $documento = Documento::find($request->id);

                    $folio=intval($request->folio);
                    $documento->id = $request->id;
                    $documento->folio = $request->folio;
                    $documento->folio_num = $folio;
                    $documento->no_documento = $request->no_documento;
                    $documento->fecha_emision = $request->fecha_emision;
                    $documento->fecha_recepcion = $request->fecha_recepcion;
                    $documento->fecha_recepcion_area = $request->fecha_recepcion_area;
                    $documento->emisor = $request->emisor;
                    $documento->cargo = $request->cargo;
                    $documento->asunto = $request->asunto;
                    $documento->dependencia = $request->dependencia;
                    $documento->dirigido_a = $request->dirigido_a;
                    $documento->cargo_a = $request->cargo_a;
                    $documento->observaciones_a = $request->observaciones_a;
                    $documento->dependencia_a = $request->dependencia_a;
                    $documento->tipo_documento_id = $request->tipo_documento;
                    $documento->departamento_id = intval($request->departamento_id[0]);
                    $documento->prioridad_id = $request->prioridad_id;
                    $documento->estatus_id = $request->estatus_id;
                    $documento->enlace = $request->enlace;
                    $documento->ultimo_usuario = $user->id;
                    $documento->save();

                    if($documento->notificado == 0){
                        if($documento->departamento->usuario){
                            $correo = $documento->departamento->usuario->email;
                            Mail::to($correo)->send(new DocumentoTurnado($documento->folio));
                            $documento->notificado = 1;
                            $documento->save();
                            
                        }
                    }
        
                    if ($request->file('archivo')) {
                        $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                        if($archivos){
                            foreach($archivos as $prueba){
                                $nombre= public_path().'/storage'.Str::substr($prueba->archivo,6);
                                unlink($nombre);                          
                                $prueba->status = 0;
                                $prueba->save();
                            }
                            $files = $request->file('archivo');
                            foreach($files as $file){
                            $current_day = Carbon::now();
                            $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                            $path = $file->storeAs('public', $fileNameToStore);
                            $archivo = new Archivo;
                            $archivo->documento_id = $documento->id;
                            $archivo->archivo = $path;
                            $archivo->save();
                            }
        
                        }else{
                            $files = $request->file('archivo');
                            foreach($files as $file){
                            $current_day = Carbon::now();
                            $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                            $path = $file->storeAs('public', $fileNameToStore);
                            $archivo = new Archivo;
                            $archivo->documento_id = $documento->id;
                            $archivo->archivo = $path;
                            $archivo->save();             
                            }
        
                        }
                        
                    
                    }
                }
            }
            else{

                $documento = Documento::find($request->id);

                $folio=intval($request->folio);
                $documento->id = $request->id;
                $documento->folio = $request->folio;
                $documento->folio_num = $folio;
                $documento->no_documento = $request->no_documento;
                $documento->fecha_emision = $request->fecha_emision;
                $documento->fecha_recepcion = $request->fecha_recepcion;
                $documento->fecha_recepcion_area = $request->fecha_recepcion_area;
                $documento->emisor = $request->emisor;
                $documento->cargo = $request->cargo;
                $documento->asunto = $request->asunto;
                $documento->dependencia = $request->dependencia;
                $documento->dirigido_a = $request->dirigido_a;
                $documento->cargo_a = $request->cargo_a;
                $documento->observaciones_a = $request->observaciones_a;
                $documento->dependencia_a = $request->dependencia_a;
                // $documento->tipo_archivo = $request->tipo_archivo;
                $documento->tipo_documento_id = $request->tipo_documento;
                $documento->departamento_id = $request->departamento_id;
                $documento->prioridad_id = $request->prioridad_id;
                // $documento->categoria_id = $request->categoria_id;
                $documento->estatus_id = $request->estatus_id;
                $documento->enlace = $request->enlace;
                $documento->ultimo_usuario = $user->id;
                $documento->save();
    
                if ($request->file('archivo')) {
                    $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                    if($archivos){
                        foreach($archivos as $prueba){
                            $nombre= public_path().'/storage'.Str::substr($prueba->archivo,6);
                            unlink($nombre);                          
                            $prueba->status = 0;
                            $prueba->save();
                        //    $prueba->delete();
                        }
                        $files = $request->file('archivo');
                        foreach($files as $file){
                        $current_day = Carbon::now();
                        $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                        $path = $file->storeAs('public', $fileNameToStore);
                        $archivo = new Archivo;
                        $archivo->documento_id = $documento->id;
                        $archivo->archivo = $path;
                        $archivo->save();
                        }
    
                    }else{
                        $files = $request->file('archivo');
                        foreach($files as $file){
                        $current_day = Carbon::now();
                        $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                        $path = $file->storeAs('public', $fileNameToStore);
                        $archivo = new Archivo;
                        $archivo->documento_id = $documento->id;
                        $archivo->archivo = $path;
                        $archivo->save();             
                        }
    
                    }
                    
                   
                }

            }
           

            $documentos = Documento::orderBy('folio_num', 'desc')->where('status',1)->get();
          

            $arrayDocumentos = array();
            $cont = 1;
            foreach($documentos as $documento) {
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
                $objectDocumento->archivo = $documento->archivo;
                $objectDocumento->enlace = $documento->enlace;
                $objectDocumento->status = $documento->status;
                $objectDocumento->tipo_documento = $documento->tipoDocumento ? $documento->tipoDocumento->nombre : '';
                $objectDocumento->departamento = $documento->departamento ? $documento->departamento->nombre : ' ';
                $objectDocumento->prioridad = $documento->prioridad ? $documento->prioridad->nombre : ' ';

                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                array_push($arrayDocumentos,$objectDocumento);
                $cont++;
            }

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizr el documento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Documento actualizado con exito.",
                "documentos" => $arrayDocumentos
            ], 200);
        }

    }

    public function eliminarDocumento(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        
        try {
            $documento = Documento::find($request->id);
            $documento->status = false;
            $documento->save();
            $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
            if($archivos){
                foreach($archivos as $prueba){
                        $nombre= public_path().'/storage'.Str::substr($prueba->archivo,6);
                        unlink($nombre);
                           $prueba->status = 0;
                           $prueba->save();
                      
                    }
            }

            $documentos = Documento::orderBy('folio_num', 'desc')->where('status',1)->get();

            $arrayDocumentos = array();
            $cont = 1;
            foreach($documentos as $documento) {
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
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;

                array_push($arrayDocumentos,$objectDocumento);
                $cont++;
            }

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizr el documento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Documento eliminado con exito.",
                "documentos" => $arrayDocumentos
            ], 200);
        }
    }

    public function getDocumentosArchivo()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1 || $user->departamento_id == 10) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('estatus_id', 3)->where('status',1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('estatus_id', 3)->where('status',1)->get();
            }

            $arrayDocumentosArchivos = array();
            $cont = 1;
            foreach($documentos as $documento) {
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
                foreach($archivos as $archivo){
                    $objectArchivo = new \stdClass();
                    $objectArchivo->id = $archivo->id;
                    $objectArchivo->documento_id = $archivo->documento_id;
                    $objectArchivo->archivo = Storage::url($archivo->archivo);
                    array_push($array_archivos,$objectArchivo);
                }
                $objectDocumento->tiene_archivo = $archivos ? true : false;
                $objectDocumento->archivo = $array_archivos;
                $objectDocumento->notas = $documento->notas;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;

                $evidencias = $documento->evidencias;
                $array_evidencias = array();
                foreach ($evidencias as $evidencia) {
                    $objectEvidencia = new \stdClass();
                    $objectEvidencia->id = $evidencia->id;
                    $objectEvidencia->descripcion = $evidencia->descripcion;
                    $objectEvidencia->tiene_archivo = $evidencia->archivo ? true : false;
                    $objectEvidencia->archivo = Storage::url($evidencia->archivo);

                    array_push($array_evidencias, $objectEvidencia);
                }

                $objectDocumento->evidencias = $array_evidencias;
                array_push($arrayDocumentosArchivos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentosArchivo" => $arrayDocumentosArchivos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosSinAtender()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('estatus_id', 4)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('estatus_id', 4)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;

                // $objectDocumento->estatus_id = $documento->estatus_id;
                
                array_push($arrayDocumentos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosIniciados()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('estatus_id', 1)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('estatus_id', 1)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                // $objectDocumento->estatus_id = $documento->estatus_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosEnProceso()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('estatus_id', 2)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('estatus_id', 2)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                $objectDocumento->estatus_id = $documento->estatus_id;
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosAtendidos()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('estatus_id', 3)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('estatus_id', 3)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosTodos()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosExtraUrgentes()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('prioridad_id', 1)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('prioridad_id', 1)->where('status', 1)->get();
            }

            $arrayDocumentos = array();
            $cont = 1;
            foreach ($documentos as $documento) {
                $objectDocumento = new \stdClass();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosUrgentes()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('prioridad_id', 2)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('prioridad_id', 2)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDocumentosNormales()
    {
        try {
            $user = Auth::user();

            if ($user->tipo_usuario_id == 1) {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('prioridad_id', 3)->where('status', 1)->get();
            } else {
                $documentos = Documento::orderBy('folio_num', 'desc')->where('departamento_id', $user->departamento_id)->where('prioridad_id', 3)->where('status', 1)->get();
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
                // $objectDocumento->categoria = $documento->categoria->nombre;
                $objectDocumento->estatus = $documento->estatus->nombre;
                $array_archivos = array();

                $archivos = Archivo::where('documento_id',$documento->id)->where('status', 1)->get();
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
                // $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                $objectDocumento->departamento_id = $documento->departamento_id;
                $objectDocumento->tipo_documento_id = $documento->tipo_documento_id;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                
                $cont++;
                array_push($arrayDocumentos, $objectDocumento);
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentos" => $arrayDocumentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function actualizarEstatusEvidencia(Request $request)
    {

        $exito = false;

        DB::beginTransaction();

        try {

            $documento = Documento::find($request->id);
            $documento->estatus_id = 1;
            $documento->save();

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizar estatus del documento",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Estatus actualizado con exito.",
                // "documentos" => $arrayDocumentos
            ], 200);
        }
    }
    public function descargarArchivo(Request $request)
    {
        $nombre_archivo = Str::substr($request->archivo,9);

        if(Storage::disk('public')->exists($nombre_archivo)){
            return Storage::disk('public')->download($nombre_archivo);
        }

    }
    public function descargarArchivo2(Request $request)
    {
        $nombre_archivo2 = Str::substr($request->archivo_visualizar2,9);

        if(Storage::disk('public')->exists($nombre_archivo2)){
            return Storage::disk('public')->download($nombre_archivo2);
        }
    }

    public function ExportarExcel(Request $request)
    {
        return (new DocumentosExport($request->documentos))->download('Documentos.xlsx');
    }

    public function ExportarReporte(Request $request)
    {
        return (new ExportReporte($request->documentos))->download('Documentos.xlsx');
    }

    public function getDocumentosReporte()
    {
        try {
            $user = Auth::user();
            $hoy = Carbon::now()->toDateString();
            $inicio_mes = Carbon::now()->startOfMonth()->toDateString();

            if($user->tipo_usuario_id == 1){
                $documentos = Documento::where('status', 1)
                ->where('fecha_emision', '>=',$inicio_mes)
                ->where('estatus_id','!=' ,3)
                // ->where('fecha_recepcion', '<=',$hoy)
                // ->orWhere('fecha_recepcion', null)
                ->where(Documento::raw('COALESCE(fecha_recepcion,0)'), '<=', $hoy)
                ->get(); 

            }
            else{
                $documentos = Documento::where('status', 1)
                ->where('fecha_emision', '>=',$inicio_mes)
                ->where('estatus_id','!=' ,3)
                ->where('departamento_id', $user->departamento_id)
                // ->orWhere('fecha_recepcion', null)
                ->where(Documento::raw('COALESCE(fecha_recepcion,0)'), '<=', $hoy)
                ->get();

            }
          

            $arrayDocumentosArchivos = array();
            $cont = 1;
            foreach($documentos as $documento) {
                $objectDocumento = new \stdClass();
                $objectDocumento->id = $documento->id;
                $objectDocumento->numero_registro = $cont;
                $objectDocumento->folio = $documento->folio;
                $objectDocumento->no_documento = $documento->no_documento;
                $objectDocumento->fecha_emision = $documento->fecha_emision;
                $objectDocumento->fecha_recepcion = $documento->fecha_recepcion;
                $objectDocumento->fecha_recepcion_area = $documento->fecha_recepcion_area;
                $objectDocumento->asunto = $documento->asunto;
                $objectDocumento->dependencia = $documento->dependencia;
                $objectDocumento->dirigido_a = $documento->dirigido_a;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                // $objectDocumento->prioridad = $documento->prioridad->nombre;
                if($documento->prioridad){
                    $objectDocumento->prioridad = $documento->prioridad->nombre;
                }else {
                    $objectDocumento->prioridad = '';
                }
                $objectDocumento->estatus = $documento->estatus->nombre;
                array_push($arrayDocumentosArchivos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentosReporte" => $arrayDocumentosArchivos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }


    public function getDocumentosFiltro(Request $request)
    {
        try {
            if($request->dpto == 999){
                $documentoss = Documento::where('fecha_emision', null)
                ->where('status' , 1)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final);

                $documentosss = Documento::where('fecha_emision','>=', $request->f_inicial)
                ->where('status' , 1)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', null);

                /////////////////////////////////////// 
                $documentos = Documento::where('fecha_emision', '>=', $request->f_inicial)
                ->where('status' , 1)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final)
                ->union($documentoss)
                ->union($documentosss)
                ->get();

            }else{

                $documentoss = Documento::where('fecha_emision', null)
                ->where('status' , 1)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final);

                $documentosss = Documento::where('fecha_emision','>=', $request->f_inicial)
                ->where('status' , 1)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion',  null);

                /////////////////////////////////////// 
                $documentos = Documento::where('fecha_emision', '>=', $request->f_inicial)
                ->where('status' , 1)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final)
                ->union($documentoss)
                ->union($documentosss)
                ->get();

            }
                
              
            
            $arrayDocumentosArchivos = array();
            $cont = 1;
            foreach($documentos as $documento) {
                $objectDocumento = new \stdClass();
                $objectDocumento->id = $documento->id;
                $objectDocumento->numero_registro = $cont;
                $objectDocumento->folio = $documento->folio;
                $objectDocumento->no_documento = $documento->no_documento;
                $objectDocumento->fecha_emision = $documento->fecha_emision;
                $objectDocumento->fecha_recepcion = $documento->fecha_recepcion;
                $objectDocumento->fecha_recepcion_area = $documento->fecha_recepcion_area;
                $objectDocumento->asunto = $documento->asunto;
                $objectDocumento->dependencia = $documento->dependencia;
                $objectDocumento->dirigido_a = $documento->dirigido_a;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                // $objectDocumento->prioridad = $documento->prioridad->nombre;
                if($documento->prioridad){
                    $objectDocumento->prioridad = $documento->prioridad->nombre;
                }else {
                    $objectDocumento->prioridad = '';
                }
                $objectDocumento->estatus = $documento->estatus->nombre;
                array_push($arrayDocumentosArchivos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentosReporte" => $arrayDocumentosArchivos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
    public function getDocumentosFiltroHistorico(Request $request)
    {
        try {
            if($request->dpto == 999){
                $documentoss = Documento::where('fecha_emision', null)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final);

                $documentosss = Documento::where('fecha_emision', $request->f_inicial)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', null);

                /////////////////////////////////////// 
                $documentos = Documento::where('fecha_emision', '>=', $request->f_inicial)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final)
                ->union($documentoss)
                ->union($documentosss)
                ->get();

            }else{

                $documentoss = Documento::where('fecha_emision', null)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final);

                $documentosss = Documento::where('fecha_emision', $request->f_inicial)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', null);

                $documentos = Documento::where('fecha_emision', '>=', $request->f_inicial)
                ->where('departamento_id', $request->dpto)
                ->where('estatus_id', $request->estatus)
                ->where('prioridad_id', $request->prioridad)
                ->where('fecha_recepcion', '<=', $request->f_final)
                ->union($documentoss)
                ->union($documentosss)
                ->get();
            }
               
           

            $arrayDocumentosArchivos = array();
            $cont = 1;
            foreach($documentos as $documento) {
                $objectDocumento = new \stdClass();
                $objectDocumento->id = $documento->id;
                $objectDocumento->numero_registro = $cont;
                $objectDocumento->folio = $documento->folio;
                $objectDocumento->no_documento = $documento->no_documento;
                $objectDocumento->fecha_emision = $documento->fecha_emision;
                $objectDocumento->fecha_recepcion = $documento->fecha_recepcion;
                $objectDocumento->fecha_recepcion_area = $documento->fecha_recepcion_area;
                $objectDocumento->asunto = $documento->asunto;
                $objectDocumento->dependencia = $documento->dependencia;
                $objectDocumento->dirigido_a = $documento->dirigido_a;
                $objectDocumento->prioridad_id = $documento->prioridad_id;
                $objectDocumento->estatus_id = $documento->estatus_id;
                // $objectDocumento->prioridad = $documento->prioridad->nombre;
                if($documento->prioridad){
                    $objectDocumento->prioridad = $documento->prioridad->nombre;
                }else {
                    $objectDocumento->prioridad = '';
                }
                $objectDocumento->estatus = $documento->estatus->nombre;
                array_push($arrayDocumentosArchivos, $objectDocumento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentosReporte" => $arrayDocumentosArchivos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function descargarComprobante(Request $request) {
        $oficio = Documento::find($request->id);
        $oficio->hora = $request->hora;
        $oficio->medio = $request->medio;



    $view = View::make('pdf.pdf_comprobante', compact('oficio'));
            $html_content = $view->render();
                PDF::reset();

                $PDF_MARGIN_LEFT = 15;
                $PDF_MARGIN_TOP = 10;
                $PDF_MARGIN_RIGHT = 15;
                $PDF_MARGIN_BOTTOM = 05;

                PDF::SetMargins($PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, $PDF_MARGIN_RIGHT,$PDF_MARGIN_BOTTOM);
                PDF::SetAutoPageBreak(true, $PDF_MARGIN_BOTTOM);

            PDF::SetTitle('Comprobante');
            
            PDF::AddPage();
	    PDF::writeHTML($html_content, true, false, true, false, '');
            // PDF::write2DBarcode('http://defensoria_publica.test/confirmacion-cita-buscada?folio='.$f, 'QRCODE,Q', 160, 200, 70, 70, $style, 'N');
            // PDF::write2DBarcode('www.tcpdf.org', 'QRCODE,H', 20, 210, 50, 50, $style, 'N');
            // PDF::Text(20, 205, 'QRCODE H');
	    PDF::setXY(15,155);
            PDF::writeHTML($html_content, true, false, true, false, '');

            ob_end_clean();
            
            PDF::Output('Confirmacion_Cita.pdf');
}

     public function DocumentosTotales(Request $request)
    {
        try {
                $departamentos = Departamento::where('status', 1)->get();    
           
            
                $arrayDepartamentos = array();
                $cont = 1;
                foreach($departamentos as $departamento) {
                    if($departamento->usuario){
                        $objectDepartamento = new \stdClass();
                        $objectDepartamento->id = $departamento->id;
                        // $objectDepartamento->numero_registro = $cont;
                        $total = Documento::where('status', 1)
                        ->where('created_at', '>=', $request->inicio)
                        ->where('created_at', '<=', $request->fin)
                        ->where('departamento_id',$departamento->id)
                        ->count();

                        $sin_atender = Documento::where('status', 1)
                        ->where('created_at', '>=', $request->inicio)
                        ->where('created_at', '<=', $request->fin)
                        ->where('departamento_id',$departamento->id)
                        ->where('estatus_id', 4)
                        ->count();

                        $iniciados = Documento::where('status', 1)
                        ->where('created_at', '>=', $request->inicio)
                        ->where('created_at', '<=', $request->fin)
                        ->where('departamento_id',$departamento->id)
                        ->where('estatus_id', 1)
                        ->count();

                        $en_proceso = Documento::where('status', 1)
                        ->where('created_at', '>=', $request->inicio)
                        ->where('created_at', '<=', $request->fin)
                        ->where('departamento_id',$departamento->id)
                        ->where('estatus_id', 2)
                        ->count();

                        $atendidos = Documento::where('status', 1)
                        ->where('created_at', '>=', $request->inicio)
                        ->where('created_at', '<=', $request->fin)
                        ->where('departamento_id',$departamento->id)
                        ->where('estatus_id', 3)
                        ->count();

                        $objectDepartamento->nombre = $departamento->nombre;
                        $objectDepartamento->total = $total;
                        $objectDepartamento->sin_atender = $sin_atender;
                        $objectDepartamento->iniciados = $iniciados;
                        $objectDepartamento->en_proceso = $en_proceso;
                        $objectDepartamento->atendidos = $atendidos;
                        array_push($arrayDepartamentos, $objectDepartamento);
                        $cont++;
                    }
               
                
            }

            return response()->json([
                "status" => "ok",
                "message" => "Documentos obtenidos con exito",
                "documentosTotales" => $arrayDepartamentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurri� un error al obtener los documentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
}
