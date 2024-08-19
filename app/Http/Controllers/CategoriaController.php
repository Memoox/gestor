<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function getCategorias(){
        try {
            $categorias = Categoria::where('status', 1)->get();
             
            $array_categorias = array();
            $cont = 1;
            foreach ($categorias as $categoria) {
                $objectCategoria = new \stdClass();
                $objectCategoria->id = $categoria->id;
                $objectCategoria->numero_registro = $cont;
                $objectCategoria->nombre = $categoria->nombre;
                
                array_push($array_categorias, $objectCategoria);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Categorias obtenidas con exito",
                "categorias" => $array_categorias
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de categorias",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function getCategorias2(){
        try {
            $categorias = Categoria::all();
             
            $array_categorias = array();
            $cont = 1;
            foreach ($categorias as $categoria) {
                $objectCategoria = new \stdClass();
                $objectCategoria->id = $categoria->id;
                $objectCategoria->numero_registro = $cont;
                $objectCategoria->nombre = $categoria->nombre;
                
                array_push($array_categorias, $objectCategoria);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Categorias obtenidas con exito",
                "categorias" => $array_categorias
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de categorias",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }

    public function guardarNuevoCategoria(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $categoria = new Categoria;
            $categoria->nombre = $request->nombre;
            
            $categoria->save();


            $categorias = Categoria::where('status', 1)->get();
                
            $array_categoria = array();
            $cont = 1;
            foreach ($categorias  as $categoria) {
                $objectCategoria = new \stdClass();
                $objectCategoria->id = $categoria->id;
                $objectCategoria->numero_registro = $cont;
                $objectCategoria->nombre = $categoria->nombre;

                array_push($array_categoria, $objectCategoria);
                $cont++;
            }


            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al generar la nueva categoria.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Nueva categoria guardada con exito.",
                "categorias" => $array_categoria
            ], 200);
        }
    }

    public function actualizarCategoria(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $categoria = Categoria::find($request->id);
            
            $categoria->nombre = $request->nombre;
            $categoria->save();

            
            $categorias = Categoria::where('status', 1)->get();

            $array_categorias = array();
            $cont = 1;
            foreach ($categorias as $categoria) {
                $objectCategoria = new \stdClass();
                $objectCategoria->id = $categoria->id;
                $objectCategoria->numero_registro = $cont;
                $objectCategoria->nombre = $categoria->nombre;
                
                array_push($array_categorias, $objectCategoria);
                $cont++;
            }
            
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizar la Categoria.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Categoria actualizada con exito.",
                "categorias" => $array_categorias
            ], 200);
        }
    }

    public function eliminarCategoria(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $categoria = Categoria::find($request->id);
            $categoria->status = false;
            $categoria->save();

            $categorias = Categoria::where('status', 1)->get();

            $array_categoria = array();
            $cont = 1;
            foreach ($categorias as $categoria) {
                $objectCategoria = new \stdClass();
                $objectCategoria->id = $categoria->id;
                $objectCategoria->numero_registro = $cont;
                $objectCategoria->nombre = $categoria->nombre;
                
                array_push($array_categoria, $objectCategoria);
                $cont++;
            }

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al eliminar esta categoria.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Categoria eliminada con exito.",
                "categorias" => $array_categoria
            ], 200);
        }
    }
}
