<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Documento;


class UserController extends Controller
{
    public function getUsuarios(){
        try {
            $usuarios = User::where('status', 1)-> where('tipo_usuario_id', '!=', 1)->get();

            $array_usuarios = array();
            $cont = 1;
            foreach ($usuarios as $usuario) {
                $objectUsuario = new \stdClass();
                $objectUsuario->id = $usuario->id;
                $objectUsuario->numero_registro = $cont;
                $objectUsuario->username = $usuario->username;
                $objectUsuario->nombre = $usuario->nombre;
                $objectUsuario->apellido = $usuario->apellido;
                $objectUsuario->password = $usuario->password;
                $objectUsuario->email = $usuario->email;
                $objectUsuario->tipo_usuario_id = $usuario->tipo_usuario_id;
                $objectUsuario->tipo_usuario = $usuario->tipoUsuario->nombre;
                $objectUsuario->departamento_id = $usuario->departamento_id;
                $objectUsuario->departamento = $usuario->departamento->nombre;
                
                array_push($array_usuarios, $objectUsuario);
                $cont++;
            }

            return response()->json([
                "status" => "ok",
                "message" => "Usuarios obtenidos con exito",
                "usuarios" => $array_usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el catalogo de usuarios",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }
    }
    
    public function guardarNuevoUsuario(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $usuario = new User;
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->departamento_id = $request->departamento_id;
            $usuario->tipo_usuario_id = $request->tipo_usuario_id;
            $usuario->email = $request->email;
            $usuario->username = $request->username;
            $usuario->password = $request->password;
            $usuario->save();

            $usuarios = User::where('status', 1)->where('tipo_usuario_id', '!=', 1)->get();
             
            $array_usuarios = array();
            $cont = 1;
            foreach ($usuarios as $usuario) {
                $objectUsuario = new \stdClass();
                $objectUsuario->id = $usuario->id;
                $objectUsuario->numero_registro = $cont;
                $objectUsuario->username = $usuario->username;
                $objectUsuario->nombre = $usuario->nombre;
                $objectUsuario->apellido = $usuario->apellido;
                $objectUsuario->password = $usuario->password;
                $objectUsuario->email = $usuario->email;
                $objectUsuario->tipo_usuario_id = $usuario->tipo_usuario_id;
                $objectUsuario->tipo_usuario = $usuario->tipoUsuario->nombre;
                $objectUsuario->departamento_id = $usuario->departamento_id;
                $objectUsuario->departamento = $usuario->departamento->nombre;
                
                array_push($array_usuarios, $objectUsuario);
                $cont++;
            }


            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al generar al nuevo usuario.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Nuevo Usuario guardado con exito.",
                "usuarios" => $array_usuarios
            ], 200);
        }
    }

    public function actualizarUsuario(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $usuario = User::find($request->id);
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->departamento_id = $request->departamento_id;
            $usuario->tipo_usuario_id = $request->tipo_usuario_id;
            $usuario->email = $request->email;
            $usuario->username = $request->username;
            $usuario->password = $request->password;
            $usuario->save();

            
            $usuarios = User::where('status', 1)-> where('tipo_usuario_id', '!=', 1)->get();

            $array_usuarios = array();
            $cont = 1;
            foreach ($usuarios as $usuario) {
                $objectUsuario = new \stdClass();
                $objectUsuario->id = $usuario->id;
                $objectUsuario->numero_registro = $cont;
                $objectUsuario->username = $usuario->username;
                $objectUsuario->nombre = $usuario->nombre;
                $objectUsuario->apellido = $usuario->apellido;
                $objectUsuario->password = $usuario->password;
                $objectUsuario->email = $usuario->email;
                $objectUsuario->tipo_usuario_id = $usuario->tipo_usuario_id;
                $objectUsuario->tipo_usuario = $usuario->tipoUsuario->nombre;
                $objectUsuario->departamento_id = $usuario->departamento_id;
                $objectUsuario->departamento = $usuario->departamento->nombre;
                
                array_push($array_usuarios, $objectUsuario);
                $cont++;
            }
            
            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al actualizar los datos del usuario.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Usuario actualizado con exito.",
                "usuarios" => $array_usuarios
            ], 200);
        }
    }
    
    public function eliminarUsuario(Request $request)
    {
        $exito = false;

        DB::beginTransaction();
        try {
            $Usuario = User::find($request->id);
            $Usuario->status = false;
            $Usuario->save();

            $usuarios = User::where('status', 1)-> where('tipo_usuario_id', '!=', 1)->get();

            $array_usuarios = array();
            $cont = 1;
            foreach ($usuarios as $usuario) {
                $objectUsuario = new \stdClass();
                $objectUsuario->id = $usuario->id;
                $objectUsuario->numero_registro = $cont;
                $objectUsuario->username = $usuario->username;
                $objectUsuario->nombre = $usuario->nombre;
                $objectUsuario->apellido = $usuario->apellido;
                $objectUsuario->password = $usuario->password;
                $objectUsuario->email = $usuario->email;
                $objectUsuario->tipo_usuario_id = $usuario->tipo_usuario_id;
                $objectUsuario->tipo_usuario = $usuario->tipoUsuario->nombre;
                $objectUsuario->departamento_id = $usuario->departamento_id;
                $objectUsuario->departamento = $usuario->departamento->nombre;
                
                array_push($array_usuarios, $objectUsuario);
                $cont++;
            }

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al eliminar a este usuario.",
                "error" => $th->getMessage(),
                "location" => $th->getFile(),
                "line" => $th->getLine(),
            ], 200);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Usuario eliminado con exito.",
                "usuarios" => $array_usuarios
            ], 200);
        }
    }

    public function documentosPendientes()
    {
        $user = Auth::user();

        $pendientes = Documento::where('departamento_id', $user->departamento->id)
                                ->where('estatus_id',4)
                                ->count();

        return response()->json([
            "status" => "ok",
            "message" => "Usuario eliminado con exito.",
            "pendientes" => $pendientes
        ], 200);
        

    }
}