<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    public function getDepartamentos(){
        try {
            $departamentos = Departamento::where('status', 1)->get();
             
            $array_departamentos = array();
            $cont = 1;
            foreach ($departamentos as $departamento) {
                $objectDepartamento = new \stdClass();
                $objectDepartamento->id = $departamento->id;
                $objectDepartamento->numero_registro = $cont;
                $objectDepartamento->nombre = $departamento->nombre;
                
                array_push($array_departamentos, $objectDepartamento);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Departamentos obtenidos con exito",
                "departamentos" => $array_departamentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de departamentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getDepartamentosUsuarios(){
        try {
            $departamentos = Departamento::where('status', 1)->get();
            // $documento->departamento->usuario->email;
             
            $array_departamentos = array();
            $cont = 1;
            foreach ($departamentos as $departamento) {
                if($departamento->usuario){
                    $objectDepartamento = new \stdClass();
                    $objectDepartamento->id = $departamento->id;
                    $objectDepartamento->numero_registro = $cont;
                    $objectDepartamento->nombre = $departamento->nombre;
                    
                    array_push($array_departamentos, $objectDepartamento);
                    $cont++;

                }
                // $objectDepartamento = new \stdClass();
                // $objectDepartamento->id = $departamento->id;
                // $objectDepartamento->numero_registro = $cont;
                // $objectDepartamento->nombre = $departamento->nombre;
                
                // array_push($array_departamentos, $objectDepartamento);
                // $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Departamentos obtenidos con exito",
                "departamentos" => $array_departamentos
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de departamentos",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
    
    public function guardarNuevoDepto(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $departamento = new Departamento;
            $departamento->nombre = $request->nombre;
            
            $departamento->save();


            $departamentos = Departamento::where('status', 1)->get();
                
            $array_departamento = array();
            $cont = 1;
            foreach ($departamentos  as $departamento) {
                $objectDepartamento = new \stdClass();
                $objectDepartamento->id = $departamento->id;
                $objectDepartamento->numero_registro = $cont;
                $objectDepartamento->nombre = $departamento->nombre;

                array_push($array_departamento, $objectDepartamento);
                $cont++;
            }


            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al generar el nuevo departamento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Nuevo departamento guardado con exito.",
                "departamentos" => $array_departamento
            ], 200);
        }
    }

    public function actualizarDepto(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $Departamento = Departamento::find($request->id);
            
            $Departamento->nombre = $request->nombre;
            $Departamento->save();

            
            $Departamentos = Departamento::where('status', 1)->get();

            $array_Departamentos = array();
            $cont = 1;
            foreach ($Departamentos as $Departamento) {
                $objectDepartamento = new \stdClass();
                $objectDepartamento->id = $Departamento->id;
                $objectDepartamento->numero_registro = $cont;
                $objectDepartamento->nombre = $Departamento->nombre;
                
                array_push($array_Departamentos, $objectDepartamento);
                $cont++;
            }
            
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizar el departamento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Departamento actualizado con exito.",
                "deptos" => $array_Departamentos
            ], 200);
        }
    }

    public function eliminarDepto(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $Departamento = Departamento::find($request->id);
            $Departamento->status = false;
            $Departamento->save();

            $departamentos = Departamento::where('status', 1)->get();

            $array_departamento = array();
            $cont = 1;
            foreach ($departamentos as $departamento) {
                $objectDepartamento = new \stdClass();
                $objectDepartamento->id = $departamento->id;
                $objectDepartamento->numero_registro = $cont;
                $objectDepartamento->nombre = $departamento->nombre;
                
                array_push($array_departamento, $objectDepartamento);
                $cont++;
            }

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al eliminar este departamento.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Departamento eliminado con exito.",
                "departamentos" => $array_departamento
            ], 200);
        }
    }
}

