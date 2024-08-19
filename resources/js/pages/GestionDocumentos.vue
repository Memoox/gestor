<template>
    <div class="container-fluid my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Gestión de Documentos</h2>
            <div>
                <v-btn
                    color="#6a73a0"
                    class="mt-4 mb-2 boton-nuevo"
                    large
                    @click="altaDocmuneto"
                    append-icon="mdi-plus"
                >
                    Nuevo Documento
                </v-btn>
            </div>
        </div>

        <div class="buscador-data-table text-right">
            <div class="buscador-data-table">
                <input type="search" v-model="buscar" placeholder="Buscar..." autocomplete="off">
            </div>
        </div>
        
        <!-- Se dibuja la tabla para mostrar todos los documentos -->
        <div class="my-2 mb-12 py-6">
            <div class="">
                <table class="table">
                    <thead class="headers-table">
                        <tr>
                            <th class="borders-header ocultar-titulo"></th>
                            <!-- <th class="borders-header titulo-columna borde-izquierdo">#</th> -->
                            <th class="borders-header titulo-columna borde-izquierdo">Folio</th>
                            <th class="borders-header hidden-column titulo-columna">Documento</th>
                            <th class="borders-header titulo-columna">Asunto</th>
                            <th class="borders-header hidden-column titulo-columna">Dependencia</th>
                            <th class="borders-header hidden-column titulo-columna">Turnado a</th>
                            <th class="borders-header hidden-column titulo-columna">Fecha Recepción Oficialia</th>
                            <th class="borders-header hidden-column titulo-columna">Fecha Recepción Área</th>
                            <th class="borders-header titulo-columna">Prioridad</th>
                            <th class="borders-header titulo-columna borde-derecho">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <th colspan="10">
                                <p class="text-center texto-cargando-datos">Cargando datos...</p>
                                <div class="linear-activity">
                                    <div class="indeterminate"></div>
                                </div>
                            </th>
                        </tr>
                        <tr v-else v-for="documento in datosPaginados" :key="documento.id">
                            <p>
                                <i style="font-size: 13px;" class="fa fa-certificate" aria-hidden="true" :class="documento.estatus_id == 1 ? 'texto-estatus-iniciado' : documento.estatus_id == 2 ? 'texto-estatus-proceso' : documento.estatus_id == 3 ? 'texto-estatus-atendido' : documento.estatus_id == 4 ? 'texto-estatus-sin-atender' : ''"></i>
                                <v-tooltip
                                    activator="parent"
                                    location="bottom"
                                    >
                                    <span style="font-size: 15px;">{{documento.estatus}}</span>
                                </v-tooltip>
                            </p>
                            <!-- <td class="texto-campo-table">
                                {{documento.numero_registro}}
                            </td> -->
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.folio}}
                            </td>
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.no_documento}}
                            </td>
                            <td class="texto-campo-table text-center">
                                {{documento.asunto}}
                            </td>
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.dependencia}}
                            </td>
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.departamento}}
                            </td>
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.fecha_recepcion}}
                            </td>
                            <td class="hidden-column texto-campo-table text-center">
                                {{documento.fecha_recepcion_area}}
                            </td>
                            <td class="texto-campo-table">
                                <p class="m-0" :class="documento.prioridad == 'Normal' ? 'texto-prioridad-normal' : documento.prioridad == 'Urgente' ? 'texto-prioridad-urgente' : documento.prioridad == 'Extra Urgente' ? 'texto-prioridad-extra-urgente' : ''"><span>{{documento.prioridad}}</span></p>
                            </td>
                            <td>
                                <div class="text-center row justify-content-center">
                                    <div class="">
                                        <v-icon
                                            @click="editarDocumentp(documento)"
                                            class="mr-1"
                                        >
                                            mdi-text-box-edit-outline
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Editar Documento</span>
                                        </v-tooltip>

                                    </div>

                                    <div>
                                        <v-icon
                                            @click="verHora(documento)"
                                            class="ml-1"
                                        >
                                            mdi-cloud-print-outline
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Imprimir Papeleta</span>
                                        </v-tooltip>
                                    </div>
                                    <div>
                                        <v-icon
                                            @click="eliminardocumento(documento)"
                                            class="ml-1"
                                        >
                                            mdi-trash-can-outline
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Eliminar Documento</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template v-if="documentos.length > 0">
                <div class="row justify-content-between container">
                    <div>
                        <p class="text-resultados mt-2">
                            Mostrando
                            <span>{{from}}</span>
                            -
                            <span>{{to}}</span>
                            de
                            <span>{{documentos.length}}</span>
                            resultados
                        </p>
                    </div>
                    <div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination pagination-lg justify-content-center">
                                <li class="page-item cursor-paginator" @click="getFirstPage()">
                                    <a class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&lt;&lt;</span>
                                        <span class="sr-only">First</span>
                                    </a>
                                </li>
                                <li class="page-item cursor-paginator" @click="getPreviousPage()">
                                    <a class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&lt;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li v-for="pagina in pages" @click="getDataPagina(pagina), setCurrentPage(pagina)" :key="pagina" class="page-item cursor-paginator" :class="isActive(pagina)">
                                    <a class="page-link">
                                        {{pagina}}
                                    </a>
                                </li>
                                <li class="page-item cursor-paginator" @click="getNextPage()">
                                    <a class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&gt;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                                <li class="page-item cursor-paginator" @click="getLastPage()">
                                    <a class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&gt;&gt;</span>
                                        <span class="sr-only">Last</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </template>
            <template v-else-if="!loading">
                <div class="text-center">
                    <p class="texto-no-data">Aún no hay datos disponibles</p>
                </div>
            </template>
        </div>
        <!-- Fin tabla -->

        <!-- Modal para agregar informacion de un nuevo documento -->
        <v-dialog
            v-model="dialogAltaDocumento"
            max-width="1000px"
            persistent    
            >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Alta documento</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formAltaDocumento">
                                <v-row>
                                    <!-- <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="documento.folio"
                                            type="text"
                                            label="Folio" 
                                            prepend-inner-icon="mdi-bookmark"
                                            :rules="folioRules"
                                        >
                                        </v-text-field>
                                    </div> -->
                                    <div class="col-md-12 col-12">
                                        <v-text-field
                                            v-model="documento.no_documento"
                                            type="text"
                                            label="No. de documento"
                                            prepend-inner-icon="mdi-bookmark"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.folio"
                                            type="text"
                                            label="Folio"
                                            prepend-inner-icon="mdi-list-box"
                                            :rules="folioRules"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.tipo_documento"
                                            type="text"
                                            label="Tipo de documento"
                                            prepend-inner-icon="mdi-list-box"
                                        >
                                        </v-text-field>
                                        <!-- <v-autocomplete
                                            v-model="documento.tipo_documento"
                                            :items="tiposDocumentos"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Tipo de documento"
                                            prepend-inner-icon="mdi-file"
                                            :rules="tipoDocumentoRules"
                                        >
                                        </v-autocomplete> -->
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.fecha_emision"
                                            type="date"
                                            label="Fecha de Emisión"
                                            prepend-inner-icon="mdi-calendar"
                                           
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.fecha_recepcion"
                                            type="date"
                                            label="Fecha de Recepción"
                                            prepend-inner-icon="mdi-calendar"
                                            
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.emisor"
                                            type="text"
                                            label="Emisor"
                                            prepend-inner-icon="mdi-account"
                                            :rules="emisorRules"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.cargo"
                                            type="text"
                                            label="Cargo"
                                            prepend-inner-icon="mdi-chess-knight"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.asunto"
                                            type="text"
                                            label="Asunto"
                                            prepend-inner-icon="mdi-tag"
                                            :rules="asuntoRules"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dependencia"
                                            type="text"
                                            label="Área del emisor"
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dirigido_a"
                                            type="text"
                                            label="Dirigido a"
                                            prepend-inner-icon="mdi-send"
                                            
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.cargo_a"
                                            type="text"
                                            label="Cargo"
                                            prepend-inner-icon="mdi-chess-knight"
                                            :rules="cargoARules"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.observaciones_a"
                                            type="text"
                                            label="Observaciones"
                                            prepend-inner-icon="mdi-content-paste"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dependencia_a"
                                            type="text"
                                            label="Dependencia"
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-select
                                            v-model="documento.departamentos_a"
                                            :items="departamentosUsuarios"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Con atención a"
                                            prepend-inner-icon="mdi-star"
                                            multiple
                                            
                                        >
                                        </v-select>
                                    </div>
                                    <!-- <div class="col-md-6 col-12">
                                        <v-autocomplete
                                            v-model="documento.departamento_id"
                                            :items="departamentos"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Turnada a"
                                            prepend-inner-icon="mdi-transfer"
                                            :rules="departamentoRules"
                                        >
                                        </v-autocomplete>
                                    </div> -->
                                    <div class="col-md-6 col-12">
                                        <v-autocomplete
                                            v-model="documento.prioridad_id"
                                            :items="prioridades"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Prioridad"
                                            prepend-inner-icon="mdi-align-horizontal-right"
                                      
                                        >
                                        </v-autocomplete>
                                    </div>
                                </v-row>
                                <v-row>
                                    <!-- <div class="col-md-12 col-12">
                                        <v-select
                                            v-model="documento.departamentos_a"
                                            :items="departamentosUsuarios"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Con atención a"
                                            prepend-inner-icon="mdi-star"
                                            multiple
                                        >
                                        </v-select>
                                    </div> -->
                                </v-row>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-autocomplete
                                            v-model="documento.estatus_id"
                                            :items="estatus"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Estatus"
                                            readonly
                                            prepend-inner-icon="mdi-content-duplicate"
                                        >
                                        </v-autocomplete>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-file-input
                                            multiple
                                            v-model="documento.archivo"
                                            show-size
                                            label="Archivos"
                                            variant="outlined"
                                            accept="application/pdf,.xlsx,.doc,.docx"                                        
                                        ></v-file-input>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <!-- <v-file-input
                                            v-model="documento.archivo2"
                                            show-size
                                            label="Archivo"
                                            variant="outlined"
                                            accept="application/pdf,.xlsx,.doc,.docx"
                                        ></v-file-input> -->
                                        <p><span class="nota-class">NOTA: </span>Solo se permiten archivos que pesen 20 MB o menos. <a class="boton_inicio" @click="irCompresor()">Puede comprimir su archivo PDF aquí</a></p>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.enlace"
                                            type="text"
                                            label="Enlace"
                                            prepend-inner-icon="mdi-content-paste"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-divider></v-divider>
                            </v-form>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="flat"
                            color="#881001"
                            @click="cancelarAltaDocumento()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarNuevoDocumento()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Fin modal agregar-->

        <!-- Modal para editar informacion de documento -->
        <v-dialog
            v-model="dialogEditarDocumento"
            max-width="1000px"
            persistent
        >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Editar documento</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formEditarDocumento">
                                <v-row>
                                    <div class="col-md-12 col-12">
                                        <v-text-field
                                            v-model="documento.no_documento"
                                            type="text"
                                            label="No. de documento"
                                            prepend-inner-icon="mdi-bookmark"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.folio"
                                            type="text"
                                            label="Folio"
                                            prepend-inner-icon="mdi-list-box"
                                            :rules="folioRules"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.tipo_documento"
                                            type="text"
                                            label="Tipo de documento"
                                            prepend-inner-icon="mdi-list-box"
                                        >
                                        </v-text-field>
                                        <!-- <v-autocomplete
                                            v-model="documento.tipo_documento"
                                            :items="tiposDocumentos"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Tipo de documento"
                                            prepend-inner-icon="mdi-file"
                                        >
                                        </v-autocomplete> -->
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="documento.fecha_emision"
                                            type="date"
                                            label="Fecha de Emisión"
                                            prepend-inner-icon="mdi-calendar"
                                           
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="documento.fecha_recepcion"
                                            type="date"
                                            label="Fecha de Recepción Oficialia"
                                            prepend-inner-icon="mdi-calendar"
                                            
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="documento.fecha_recepcion_area"
                                            type="date"
                                            label="Fecha de Recepción Area"
                                            prepend-inner-icon="mdi-calendar"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider ></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.emisor"
                                            type="text"
                                            label="Emisor"
                                            prepend-inner-icon="mdi-account"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.cargo"
                                            type="text"
                                            label="Cargo"
                                            prepend-inner-icon="mdi-chess-knight"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-row >
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.asunto"
                                            type="text"
                                            label="Asunto"
                                            prepend-inner-icon="mdi-tag"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dependencia"
                                            type="text"
                                            label="Area del emisor"
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dirigido_a"
                                            type="text"
                                            label="Dirigido a"
                                            prepend-inner-icon="mdi-send"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.cargo_a"
                                            type="text"
                                            label="Cargo"
                                            prepend-inner-icon="mdi-chess-knight"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.observaciones_a"
                                            type="text"
                                            label="Observaciones"
                                            prepend-inner-icon="mdi-content-paste"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.dependencia_a"
                                            type="text"
                                            label="Dependencia"
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-select
                                            v-model="documento.departamento_id"
                                            :items="departamentosUsuarios"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Con atención a"
                                            prepend-inner-icon="mdi-star"
                                            multiple
                                        >
                                        </v-select>
                                    </div>
                                    <!-- <div class="col-md-4 col-12">
                                        <v-autocomplete
                                            v-model="documento.departamento_id"
                                            :items="departamentos"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Turnada a"
                                            prepend-inner-icon="mdi-transfer"
                                        >
                                        </v-autocomplete>
                                    </div> -->
                                    <div class="col-md-6 col-12">
                                        <v-autocomplete
                                            v-model="documento.prioridad_id"
                                            :items="prioridades"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Prioridad"
                                            prepend-inner-icon="mdi-align-horizontal-right"
                                        >
                                        </v-autocomplete>
                                    </div>
                                    <!-- <div class="col-md-4 col-12">
                                        <v-select
                                            v-model="documento.departamento_id"
                                            :items="departamentos"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Con atención a"
                                            prepend-inner-icon="mdi-star"
                                            multiple
                                        >
                                        </v-select>
                                    </div> -->
                                </v-row>

                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-autocomplete
                                            v-model="documento.estatus_id"
                                            :items="estatus"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Estatus"
                                            prepend-inner-icon="mdi-content-duplicate"
                                        >
                                        </v-autocomplete>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-file-input
                                            multiple
                                            v-model="documento.archivo"
                                            show-size
                                            label="Archivo"
                                            variant="outlined"
                                            accept="application/pdf,.xlsx,.doc,.docx"
                                           
                                        ></v-file-input>
                                    </div>
                                </v-row>
                                
                                <v-row>
                                    <!-- <div class="col-md-6 col-12">
                                        <v-file-input
                                            v-model="documento.archivo2"
                                            show-size
                                            label="Archivo"
                                            variant="outlined"
                                            accept="application/pdf,.xlsx,.doc,.docx"
                                            :rules="rules"
                                        ></v-file-input>
                                    </div> -->
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <p class="link-archivo ml-4" @click="verArchivos()"><v-icon icon="mdi-paperclip"></v-icon> Ver archivos</p>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="documento.enlace"
                                            type="text"
                                            label="Enlace"
                                            prepend-inner-icon="mdi-content-paste"
                                        >
                                        </v-text-field>
                                    </div>
                                    
                                </v-row>
                                
                                <v-divider></v-divider>
                            </v-form>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="flat"
                            color="#881001"
                            @click="cancelarEditarDocumento()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarCambioDocumento()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Fin modal editar -->
        <v-dialog v-model="dialogPdf" max-width="800px">
            <v-card>
                <v-card-title>
                </v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <div align="center" style="background-color:black;width:100%; height:500px">
                        <!-- <embed style="width:95%;height:100%" :src="archivo" type="application/pdf"> -->
                        <iframe :src="archivo" frameborder="0" width="100%" height="100%"></iframe>
                    </div>
                </v-container>
                <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn
                        variant="flat"
                        color="error"
                        @click="cerrarModalPdf()"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogVerarchivos" max-width="600px">
            <v-card>
                <h4>Archivos cargados en este Documento</h4>
                <div class="row justify-content-between" v-for="arch in documento.archivos" :key="arch">
                    <div class="col-md-6 col-12 mb-2 mt-2">
                        <p class="link-archivo ml-4" @click="verArchivo(arch)"><v-icon icon="mdi-paperclip"></v-icon> Ver archivo</p>
                        <!-- <p class="link-archivo ml-4" @click="descargarArchivo(arch)"><v-icon icon="mdi-paperclip"></v-icon> Descargar archivo</p> -->
                    </div>
                </div>
                <!-- <div class="col-md-6 col-sm-6 col-12 mt-6" v-if="documento.tiene_archivo2">
                    <p class="link-archivo ml-4" @click="descargarArchivo2(documento)"><v-icon icon="mdi-paperclip"></v-icon> Descargar archivo</p>
                </div> -->
                <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn
                        variant="flat"
                        color="error"
                        @click="cerrarModalVerarchivos()"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
    <v-dialog v-model="dialogAgregarHora" max-width="600px">
            <v-card>
                <v-card-title>
                    <h4>Ingresa la informacion</h4>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <div class="col-md-6 col-12 mt-6">
                                <v-text-field
                                    v-model="datos.hora"
                                    type="time"
                                    label="Hora"
                                    variant="outlined"
                                    >
                                </v-text-field>
                            </div>
                            <div class="col-md-6 col-12 mt-6">
                                <v-select
                                    v-model="datos.medio"
                                    :items="medios"
                                    item-title="nombre"
                                    item-value="nombre"
                                    label="Medio de recepcion"
                                    variant="outlined"
                                    >
                                </v-select>
                            </div>
                        <!-- </div> -->
                        </v-row>
                    </v-container>
                </v-card-text>
                
                
                <!-- <div class="col-md-6 col-sm-6 col-12 mt-6" v-if="documento.tiene_archivo2">
                    <p class="link-archivo ml-4" @click="descargarArchivo2(documento)"><v-icon icon="mdi-paperclip"></v-icon> Descargar archivo</p>
                </div> -->
                <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn
                        variant="flat"
                        color="#6a73a0"
                        @click="descargarComprobante()"
                    >
                    <span style="color: #eaeaed;">Aceptar</span>
                    </v-btn>
                    <v-btn
                        variant="flat"
                        color="error"
                        @click="cerrarHora()"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
</template>

<script>
    import { defineComponent } from "vue"
    // import { VTimePicker } from 'vuetify/labs/VTimePicker'
    import { successSweetAlert, errorSweetAlert, warningSweetAlert } from '../helpers/sweetAlertGlobals'

    export default defineComponent({
        name: 'gestion-documento',
        data () {
            return { 
                // VTimePicker,
                dialogAltaDocumento: false,
                dialogEditarDocumento: false,
                dialogAgregarHora: false,
                dialogPdf: false,
                dialogVerarchivos: false,
                loading: false,
                archivo: '',
                doc: '',
                datos:{id:null,hora: '',medio:''},
                medios:[
                    {id: 1,nombre:'Correos de México'},
                    {id: 2,nombre:'Correo electronico'},
                    {id: 3,nombre:'Oficialia de Partes'},
                    {id: 4,nombre:'Estafeta'},
                ],
                documento: {
                    id: null,
                    folio:'',
                    no_documento: '',
                    tipo_documento: '',
                    fecha_emision: '',
                    fecha_recepcion: '',
                    fecha_recepcion_area: '',
                    emisor:'',
                    cargo:'',
                    asunto: '',
                    dependencia:'',
                    dirigido_a:'',
                    cargo_a:'',
                    observaciones_a:'',
                    dependencia_a:'',
                    departamento_id:[],
                    prioridad_id: '',
                    categoria_id: '',
                    tipo_archivo: '',
                    archivo: [],
                    archivos: [],
                    tiene_archivo: false,
                    archivo_visualizar: [],
                    tiene_archivo2: false,
                    archivo_visualizar2: null,
                    estatus_id: 1,
                    enlace: '',
                    departamentos_a: []
                },
                elementosPorPagina: 10,
                paginaActual: 1,
                datosPaginados: [],
                mostrar: false,
                from: '',
                to: '',
                numShown: 5,
                current: 1,
                buscar: '',
                folioRules: [
                    v => !!v || 'El folio es requerido',
                ],
                noDocumentoRules: [
                    v => !!v || 'El número de documento es requerido',
                ],
                tipoDocumentoRules: [
                    v => !!v || 'El tipo de documento es requerido',
                ],
                fechaEmisionRules: [
                    v => !!v || 'La fecha de emisión es requerido',
                ],
                fechaRecepcionRules: [
                    v => !!v || 'La fecha de recepción es requerido',
                ],
                departamentoRules: [
                    v => !!v || 'El departamento es requerido',
                ],
                prioridadRules: [
                    v => !!v || 'La prioridad es requerido',
                ],
                prioridadRuless: [
                    v => !!v || 'La prioridad es requerido',
                ],
                categoriaRules: [
                    v => !!v || 'La categoría es requerida',
                ],
                archivoRules: [
                    v => !!v || 'El archivo es requerido',
                    v => !v || !v.length || v[0].size < 20000000 || 'El archivo debe pesar 20 MB o menos'
                ],
                rules: [
                    v => {
                        if (v) {
                            if (v[0].size > 20000000) {
                                return 'El archivo debe pesar 20 MB o menos'
                            }
                            return true
                        } else {
                            return true
                        }
                    }
                ],
                emisorRules: [
                    v => !!v || 'Campo requerido',
                ],
                cargoRules: [
                    v => !!v || 'Campo requerido',
                ],
                asuntoRules: [
                    v => !!v || 'Campo requerido',
                ],
                dependenciaRules: [
                    v => !!v || 'Campo requerido',
                ],
                dirigidoRules: [
                    v => !!v || 'Campo requerido',
                ],
                cargoARules: [
                    v => !!v || 'Campo requerido',
                ],
                asuntoARules: [
                    v => !!v || 'Campo requerido',
                ],
                dependenciaARules: [
                    v => !!v || 'Campo requerido',
                ],
            }
        },
        created() {
            this.getDocumentos()
            // this.getTipoDocumentos()
            this.getDepartamentosConUsuario()
            this.getCatalogoPrioridades()
            // this.getCatalogoCategorias()
            // this.getCatalogoCategorias2()
            this.getCatalogoEstatus()
        },
        computed: {
            documentos() {
                return this.$store.getters.getDocumentos
            },
            pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
            },
            // tiposDocumentos() {
            //     return this.$store.getters.getCatalogoTiposDocumentos
            // },
            // departamentos() {
            //     return this.$store.getters.getCatalogoDepartamentos
            // },
            departamentosUsuarios() {
                return this.$store.getters.getCatalogoDepartamentosUsuarios
            },
            prioridades() {
                return this.$store.getters.getCatalogoPrioridades
            },
            // categorias() { 
            //     return this.$store.getters.getCatalogoCategorias
            // },
            // categorias2() { 
            //     return this.$store.getters.getCatalogoCategorias2
            // },
            estatus() { 
                return this.$store.getters.getCatalogoEstatus
            },
        },
        watch: {
            buscar: function () {
                if (!this.buscar.length == 0) {
                    this.datosPaginados = this.documentos.filter(item => {
                        return item.folio.toString().includes(this.buscar)
                        || item.no_documento.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.observaciones_a.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.asunto.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.dependencia.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.departamento.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.prioridad.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.emisor.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.fecha_recepcion.includes(this.buscar)
                        || item.fecha_recepcion_area.includes(this.buscar)

                    })
                } else {
                    this.getDataPagina(1)
                }
            },
            mostrar: function () {
                if (this.mostrar) {
                    this.getDataPagina(1)
                }
            }
        },
        methods: {
            async guardarNuevoDocumento() {
               
                const { valid } = await this.$refs.formAltaDocumento.validate()
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nuevo documento?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/documento/guardar-nuevo', this.documento, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data',
                                    }
                                })
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar nuevo documento.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setDocumentos', result.value.data.documentos)
                                    this.cancelarAltaDocumento()
                                    this.getDataPagina(1)
                                    this.limpiarFormulario()
                                    // this.descargarComprobante(result.value.data.documentos[0].id)
                                    // this.descargarComprobante(result.value.data.oficio)

                                    // this.doc = result.value.data.documentos[0].id
                                    // console.log(this.doc)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar nuevo documento.')
                            }
                        }
                    })
                }
            },
            async getDocumentos() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/documentos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener listado de documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener listado de documentos')
                }
                this.loading = false
            },
            totalPaginas() {
                return Math.ceil(this.documentos.length / this.elementosPorPagina)
            },
            getDataPagina(noPagina) {
                this.paginaActual = noPagina
                this.datosPaginados = []

                let ini = (noPagina * this.elementosPorPagina) - this.elementosPorPagina
                let fin = (noPagina * this.elementosPorPagina)

                for (let index = ini; index < fin; index++) {
                    if (this.documentos[index]) {
                        this.datosPaginados.push(this.documentos[index])
                    }
                }

                // Para el texto "Mostrando 1 - 10 de 20 resultados"
                this.from = ini+1
                if (noPagina < this.totalPaginas()) {
                    this.to = fin
                } else {
                    this.to = this.documentos.length
                }
            },
            getFirstPage() {
                this.paginaActual = 1
                this.setCurrentPage(this.paginaActual)
                this.getDataPagina(this.paginaActual)
            },
            getPreviousPage() {
                if (this.paginaActual > 1) {
                    this.paginaActual--
                }
                this.setCurrentPage(this.paginaActual)
                this.getDataPagina(this.paginaActual)
            },
            getNextPage() {
                if (this.paginaActual < this.totalPaginas()) {
                    this.paginaActual++
                }
                this.setCurrentPage(this.paginaActual)
                this.getDataPagina(this.paginaActual)
            },
            getLastPage() {
                this.paginaActual = this.totalPaginas()
                this.setCurrentPage(this.paginaActual)
                this.getDataPagina(this.paginaActual)
            },
            isActive (noPagina) {
                return noPagina == this.paginaActual ? 'active' : ''
            },
            setCurrentPage(pagina) {
                this.current = pagina
            },
            // async getTipoDocumentos() {
            //     try {
            //         let response = await axios.get('/api/catalogos/tipos-documentos')
            //         if (response.status === 200) {
            //             if (response.data.status === "ok") {
            //                 this.$store.commit('setCatalogoTiposDocumentos', response.data.tipos_documentos)
            //             } else {
            //                 errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
            //             }
            //         } else {
            //             errorSweetAlert('Ocurrió un error al obtener catalogo de tipos de documento')
            //         }
            //     } catch (error) {
            //         errorSweetAlert('Ocurrió un error al obtener catalogo de tipos de documento')
            //     }
            // },
            async getCatalogoDepartamentos() {
                try {
                    let response = await axios.get('/api/catalogos/departamentos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoDepartamentos', response.data.departamentos)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                }
            },
            async getDepartamentosConUsuario() {
                try {
                    let response = await axios.get('/api/catalogos/departamentos-con-usuario')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoDepartamentosUsuarios', response.data.departamentos)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                }
            },
            async getCatalogoPrioridades() {
                try {
                    let response = await axios.get('/api/catalogos/prioridades')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoPrioridades', response.data.prioridades)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de prioridades')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de prioridades')
                }
            },
            // async getCatalogoCategorias() {
            //     try {
            //         let response = await axios.get('/api/catalogos/categorias')
            //         if (response.status === 200) {
            //             if (response.data.status === "ok") {
            //                 this.$store.commit('setCatalogoCategorias', response.data.categorias)
            //             } else {
            //                 errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
            //             }
            //         } else {
            //             errorSweetAlert('Ocurrió un error al obtener el catalogo de categorías')
            //         }
            //     } catch (error) {
            //         errorSweetAlert('Ocurrió un error al obtener el catalogo de categorías')
            //     }
            // },
            // async getCatalogoCategorias2() {
            //     try {
            //         let response = await axios.get('/api/catalogos/categorias2')
            //         if (response.status === 200) {
            //             if (response.data.status === "ok") {
            //                 this.$store.commit('setCatalogoCategorias2', response.data.categorias)
            //             } else {
            //                 errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
            //             }
            //         } else {
            //             errorSweetAlert('Ocurrió un error al obtener el catalogo de categorías')
            //         }
            //     } catch (error) {
            //         errorSweetAlert('Ocurrió un error al obtener el catalogo de categorías')
            //     }
            // },
            altaDocmuneto() {
                this.dialogAltaDocumento = true
            },
            cancelarAltaDocumento() {
                this.dialogAltaDocumento = false
                this.limpiarFormulario()
            },
            limpiarFormulario() {
                this.documento.folio = ''
                this.documento.no_documento = ''
                this.documento.tipo_documento = ''
                this.documento.fecha_emision = ''
                this.documento.fecha_recepcion = ''
                this.documento.emisor = ''
                this.documento.cargo = ''
                this.documento.asunto = ''
                this.documento.dependencia = ''
                this.documento.dirigido_a = ''
                this.documento.cargo_a = ''
                this.documento.observaciones_a = ''
                this.documento.dependencia_a = ''
                this.documento.departamento_id = []
                this.documento.prioridad_id = ''
                this.documento.categoria_id = ''
                this.documento.tipo_archivo = ''
                this.documento.enlace = ''
                this.documento.departamentos_a = []
                this.documento.archivo = null
                this.documento.tiene_archivo = false
                this.documento.archivo_visualizar = null
                this.documento.estatus_id = 1
                // this.documento.estatus_id = 1
            },
            editarDocumentp(documento) {
                // console.log(documento)
                this.documento.id = documento.id
                this.documento.folio = documento.folio
                this.documento.no_documento = documento.no_documento
                this.documento.tipo_documento = documento.tipo_documento_id
                this.documento.fecha_emision = documento.fecha_emision
                this.documento.fecha_recepcion = documento.fecha_recepcion.trim()
                this.documento.fecha_recepcion_area = documento.fecha_recepcion_area.trim()
                this.documento.emisor = documento.emisor
                this.documento.cargo = documento.cargo
                this.documento.asunto = documento.asunto
                this.documento.dependencia = documento.dependencia
                this.documento.dirigido_a = documento.dirigido_a
                this.documento.cargo_a = documento.cargo_a
                this.documento.observaciones_a = documento.observaciones_a
                // this.documento.observaciones_a = documento.observaciones_a.trim()
                this.documento.dependencia_a = documento.dependencia_a
                if(documento.departamento_id)
                this.documento.departamento_id.push(documento.departamento_id) 
                else 
                this.documento.departamento_id =[]
                this.documento.prioridad_id = documento.prioridad_id
                this.documento.categoria_id = documento.categoria_id
                this.documento.tipo_archivo = documento.tipo_archivo
                this.documento.tiene_archivo = documento.tiene_archivo
                this.documento.archivos = documento.archivo
                this.documento.estatus_id = documento.estatus_id
                this.documento.enlace = documento.enlace
                this.documento.archivo_visualizar2 = documento.archivo2

                this.dialogEditarDocumento = true
                //  console.log(this.documento)
            },
            cancelarEditarDocumento() {
                this.dialogEditarDocumento = false
                this.limpiarFormulario()
            },
            async guardarCambioDocumento() {
                // console.log(this.documento)
                const { valid } = await this.$refs.formEditarDocumento.validate()
                if (valid) {
                    // console.log(this.documento)
                    Swal.fire({
                        title: '¿Guardar cambios?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/documento/actualizar-documento', this.documento,{
                                    headers: {
                                        'Content-Type': 'multipart/form-data',
                                    }
                                })
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al actualizar documento.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setDocumentos', result.value.data.documentos)
                                    this.cancelarEditarDocumento()
                                    // this.buscar= ''
                                    this.getDataPagina(1)
                                    this.limpiarFormulario()
                                    // this.buscar= ''
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al actualizar documento.')
                            }
                        }
                    })
                }
            },
            eliminardocumento(documento) {
                Swal.fire({
                    title: '¿Eliminar Documento?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085D6',
                    cancelButtonColor: '#D33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            let response = await axios.post('/api/documento/eliminar-documento', documento)
                            return response
                        } catch (error) {
                            errorSweetAlert('Ocurrió un error al eliminar el documento.')
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value.status === 200) {
                            if (result.value.data.status === "ok") {
                                successSweetAlert(result.value.data.message)
                                this.$store.commit('setDocumentos', result.value.data.documentos)
                                // this.buscar= ''
                                this.getDataPagina(1)
                            } else {
                                errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                            }
                        } else {
                            errorSweetAlert('Ocurrió un error al eliminar el documento.')
                        }
                    }
                })
            },
            verArchivo(documento) {
                // console.log(documento)
                var extension = documento.archivo.split('.')
                if(documento.archivo){
                    if(extension[1] == 'pdf'){
                        this.archivo = documento.archivo
                        this.dialogPdf = true
                    }else{
                        this.archivo = documento.archivo
                        window.open(this.archivo,'_black')
                    }
                   
                } else {
                    warningSweetAlert('No se ha subido ningún archivo.')
                } 
            },
            cerrarModalPdf() {
                this.dialogPdf = false
            },
            async getCatalogoEstatus() {
                try {
                    let response = await axios.get('/api/catalogos/estatus')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoEstatus', response.data.estatus)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de estatus')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de estatus')
                }
            },
            irCompresor() {
                Swal.fire({
                    title: '¿Se te redireccionará a un sitio externo, estás seguro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ir al sitio',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open("https://www.ilovepdf.com/es/comprimir_pdf")
                    }
                })
            },
            verArchivos() {
                    this.dialogVerarchivos = true
            },
            verHora(doc) {
                    this.datos.id = doc.id
                    this.dialogAgregarHora = true
                    // console.log(this.datos)
            },
            cerrarModalVerarchivos(){
                this.dialogVerarchivos = false
            },
            cerrarHora(){
                this.dialogAgregarHora = false
                this.datos.hora=''
                this.datos.medio= ''
            },
            async descargarArchivo(documento) {
                // console.log(documento)
                let response = await axios.post('/api/documentos/descargar-archivo', documento,{
                    responseType: "blob",
                }).then((response)=>{    
                    // console.log(response.data)
                    var blob = new Blob([response.data], {
                        type: response.headers["content-type"],
                    });
                    const link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = `archivo_descargado`;
                    link.click();
                })
            },
            async descargarArchivo2() {
                let response = await axios.post('/api/documentos/descargar-archivo2', this.datos,{
                    responseType: "blob",
                }).then((response)=>{    
                    var blob = new Blob([response.data], {
                        type: response.headers["content-type"],
                    });
                    const link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = `archivo_descargado2`;
                    link.click();
                })
            },
            async descargarComprobante(){
                try{
                    // console.log(this.datos)
                    if(this.datos.hora=='' || this.datos.medio =='')
                    return
                    let response = await axios.post('/api/documento/descargar-comprobante',this.datos,{
                                                    responseType: 'blob',
                                                    }).then((response)=>{    
                                                        this.cerrarHora()
                                                        let blob = new Blob([response.data], { 
                                                            type: response.headers["content-type"], })
                                                        let link = document.createElement('a')
                                                        link.href = window.URL.createObjectURL(blob)
                                                        // link.download = `${item.nombre}.pdf
                                                        link.download = 'comprobante.pdf'
                                                        link.click()
                                                        
                                                    })   
                                                }catch (error) {
                        errorSweetAlert('Ocurrió un error al descargar el archivo PDF')
                    }  
            }
        },
    })
</script>