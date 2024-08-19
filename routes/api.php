<?php

use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EstatusController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PrioridadController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\NotaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
});

/**
 * RUTAS ADMINISTRATIVAS
 */
Route::group(['middleware' => 'auth:sanctum'], function ($router) {
    // Rutas para catalogos
    Route::get('/catalogos/usuarios', [UserController::class, 'getUsuarios']);
    Route::get('/catalogos/documentos', [DocumentoController::class, 'getDocumentos']);
    Route::get('/catalogos/documentos-archivo', [DocumentoController::class, 'getDocumentosArchivo']);
    Route::get('/catalogos/categorias', [CategoriaController::class, 'getCategorias']);
    Route::get('/catalogos/categorias2', [CategoriaController::class, 'getCategorias2']);
    Route::get('/catalogos/tipos-usuarios', [TipoUsuarioController::class, 'getTiposUsuarios']);
    Route::get('/catalogos/departamentos', [DepartamentoController::class, 'getDepartamentos']);
    Route::get('/catalogos/prioridades', [PrioridadController::class, 'getPrioridades']);
    Route::get('/catalogos/estatus', [EstatusController::class, 'getEstatus']);
    Route::get('/catalogos/tipos-documentos', [TipoDocumentoController::class, 'getTiposDocumentos']);
    Route::get('/catalogos/departamentos', [DepartamentoController::class, 'getDepartamentos']);
    Route::get('/catalogos/departamentos-con-usuario', [DepartamentoController::class, 'getDepartamentosUsuarios']);
    Route::get('/catalogos/documentos-reporte', [DocumentoController::class, 'getDocumentosReporte']);
    Route::post('/catalogos/documentos-filtros', [DocumentoController::class, 'getDocumentosFiltro']);
    Route::post('/catalogos/documentos-filtro-historico', [DocumentoController::class, 'getDocumentosFiltroHistorico']);





     // Rutas tipos documentos
    Route::post('/tipos-documentos/guardar-nuevo', [TipoDocumentoController::class, 'nuevoTipoDocumento']);
    Route::post('/tipos-documentos/actualizar-tipoDoc', [TipoDocumentoController::class, 'actualizarTipoDocumento']);
    Route::post('/tipos-documentos/eliminar-tipoDoc', [TipoDocumentoController::class, 'eliminarTipoDocumento']);
    
    
    // Rutas para documentos
    Route::post('/documento/guardar-nuevo', [DocumentoController::class, 'guardarNuevoDocumento']);
    Route::post('/documento/actualizar-documento', [DocumentoController::class, 'actualizarDocumento']);
    Route::post('/documento/eliminar-documento', [DocumentoController::class, 'eliminarDocumento']);
    
    // Rutas para usuarios
    Route::post('/usuarios/agregar-usuario', [UserController::class, 'guardarNuevoUsuario']);
    Route::post('/usuarios/actualizar-usuario', [UserController::class, 'actualizarUsuario']);
    Route::post('/usuarios/eliminar-usuario', [UserController::class, 'eliminarUsuario']);
    Route::post('/documento/descargar-comprobante', [DocumentoController::class, 'descargarComprobante']);
    
    // Rutas para categorias
    Route::post('/requisito/guardar-categoria', [CategoriaController::class, 'guardarNuevoCategoria']);
    Route::post('/requisito/editar-categoria', [CategoriaController::class, 'actualizarCategoria']);
    Route::post('/requisito/eliminar-categoria', [CategoriaController::class, 'eliminarCategoria']);

    // Ruta para obtener numeros estadisticos a mostrar en graficas
    Route::get('/estadistica', [DocumentoController::class, 'getEstadisticas']);
    // Ruta para obtener los documentos a mostrar en la tabla de la vista principal
    Route::get('/documentos', [DocumentoController::class, 'getDocumentosHome']);
    
    // Rutas para filtros de la página principal
    Route::get('/documentos/sin-atender', [DocumentoController::class, 'getDocumentosSinAtender']);
    Route::get('/documentos/iniciados', [DocumentoController::class, 'getDocumentosIniciados']);
    Route::get('/documentos/en-proceso', [DocumentoController::class, 'getDocumentosEnProceso']);
    Route::get('/documentos/atendidos', [DocumentoController::class, 'getDocumentosAtendidos']);
    Route::get('/documentos/todos', [DocumentoController::class, 'getDocumentosTodos']);
    Route::get('/documentos/extra-urgentes', [DocumentoController::class, 'getDocumentosExtraUrgentes']);
    Route::get('/documentos/urgentes', [DocumentoController::class, 'getDocumentosUrgentes']);
    Route::get('/documentos/normales', [DocumentoController::class, 'getDocumentosNormales']);


    Route::post('/requisito/guardar-depto', [DepartamentoController::class, 'guardarNuevoDepto']);
    Route::post('/guardar-notas',[NotaController::class, 'guardarNotas']);
    Route::post('/documentos/guardar-evidencia', [DocumentoController::class, 'guardarEvidencia']);
    Route::post('/requisito/eliminar-depto', [DepartamentoController::class, 'eliminarDepto']);
    Route::post('/requisito/editar-depto', [DepartamentoController::class, 'actualizarDepto']);
    Route::post('/documentos/actualizar-estatus-evidencia', [DocumentoController::class, 'actualizarEstatusEvidencia']);

    Route::post('/reporte-documentos', [DocumentoController::class, 'ExportarExcel']);
    Route::post('/reporte-exportar', [DocumentoController::class, 'ExportarReporte']);


    Route::post('/documentos/descargar-archivo', [DocumentoController::class, 'descargarArchivo']);
    Route::post('/documentos/descargar-archivo2', [DocumentoController::class, 'descargarArchivo2']);


    Route::post('/obtener-pendientes', [UserController::class, 'documentosPendientes']);
    Route::post('/documentos/totales', [DocumentoController::class, 'DocumentosTotales']);

});
