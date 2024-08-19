<template>
    <div class="container-fluid my-6">
        <div class="text-center my-6">
            <h1>Oficialía</h1>
        </div>

        <div class="row justify-content-md-center">
            <v-card class="mx-auto card-desc col-12 col col-xs-1 col-sm-6 col-md-4 col-lg-3" @click="getTodosDocumentos">
                <v-card-item>
                    <div class="bg_colora padding_card">
                        <v-card-title class="counter">
                            {{estadistica.total_documentos}}
                        </v-card-title>
                        <p class="subtitulo-inicio">TOTAL DE DOCUMENTOS</p>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-desc col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3" @click="getDocumentosExtraUrgentes">
                <v-card-item>
                    <div class="bg_colorb padding_card">
                        <v-card-title class="counter">
                            {{estadistica.documentos_extraurgente}}
                        </v-card-title>
                        <p class="subtitulo-inicio">EXTRA URGENTE</p>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-desc col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3" @click="getDocumentosUrgentes">
                <v-card-item>
                    <div class="bg_colorc padding_card">
                        <v-card-title class="counter">
                            {{estadistica.documentos_urgente}}
                        </v-card-title>
                        <p class="subtitulo-inicio">URGENTE</p>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-desc col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3" @click="getDocumentosNormales">
                <v-card-item>
                    <div class="bg_colord padding_card">
                        <v-card-title class="counter">
                            {{estadistica.documentos_normal}}
                        </v-card-title>
                        <p class="subtitulo-inicio">NORMAL</p>
                    </div>
                </v-card-item>
            </v-card>
        </div>

        <div class="row justify-content-md-center mt-4">
            <v-card class="mx-auto card-graph col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <v-card-item class="estatus-por">
                    <div class="padding_card_graph_1">
                        <h5 style="font-weight: bold;">Total {{estadistica.total_documentos}}</h5>
                        <div class="row text-left">
                            <div class="col-12">
                                <span class="texto-grafica-1">Sin Atender</span>
                            </div>
                            <div class="col-12" style="cursor: pointer;" @click="getDocumentosSinAtender()">
                                <div class="barra-1">
                                    <div class="barra-porcent barra-1-porcent-1" :style="barra1"></div>
                                    <v-tooltip
                                        activator="parent"
                                        location="right"
                                        >
                                        <span style="font-size: 15px;">Sin atender: {{estadistica.documentos_sinatender}}</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-12">
                                <span class="texto-grafica-1">Iniciados</span>
                            </div>
                            <div class="col-12" style="cursor: pointer;" @click="getDocumentosIniciados()">
                                <div class="barra-1">
                                    <div class="barra-porcent barra-2-porcent-1" :style="barra2"></div>
                                    <v-tooltip
                                        activator="parent"
                                        location="right"
                                        height="70"
                                        width="160"
                                        class="text-center"
                                        >
                                        <span style="font-size: 15px;">Conocimiento de los documentos: {{estadistica.documentos_iniciados}}</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-12">
                                <span class="texto-grafica-1">En proceso</span>
                            </div>
                            <div class="col-12" style="cursor: pointer;" @click="getDocumentosEnProceso()">
                                <div class="barra-1">
                                    <div class="barra-porcent barra-3-porcent-1" :style="barra3"></div>
                                    <v-tooltip
                                        activator="parent"
                                        location="right"
                                        height="100"
                                        width="160"
                                        class="text-center"
                                        >
                                        <span style="font-size: 15px;">Se esta esperando información para atenderlo: {{estadistica.documentos_enproceso}}</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-12">
                                <span class="texto-grafica-1">Atendidos</span>
                            </div>
                            <div class="col-12" style="cursor: pointer;" @click="getDocumentosAtendidos()">
                                <div class="barra-1">
                                    <div class="barra-porcent barra-4-porcent-1" :style="barra4"></div>
                                    <v-tooltip
                                        activator="parent"
                                        location="right"
                                        >
                                        <span style="font-size: 15px;">Proceso terminado: {{estadistica.documentos_atendidos}}</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-graph col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <v-card-item class="estatus-por">
                    <div class="padding_card_graph hidden-card">
                        <v-card-title class="chartTitle color1">
                            Extra Urgente
                        </v-card-title>
                        <Doughnut :data="chartData" :options="chartOptions" />
                        <span class="texto-porcentaje color-texto-porcentaje-1">{{Math.round(estadistica.porcent_1)}}%</span>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-graph col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <v-card-item class="estatus-por">
                    <div class="padding_card_graph hidden-card">
                        <v-card-title class="chartTitle color2">
                            Urgente
                        </v-card-title>
                        <Doughnut :data="chartData2" :options="chartOptions" />
                        <span class="texto-porcentaje color-texto-porcentaje-2">{{Math.round(estadistica.porcent_2)}}%</span>
                    </div>
                </v-card-item>
            </v-card>
            <v-card class="mx-auto card-graph col-12 col col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <v-card-item class="estatus-por">
                    <div class="padding_card_graph hidden-card">
                        <v-card-title class="chartTitle color3">
                            Normal
                        </v-card-title>
                        <Doughnut :data="chartData3" :options="chartOptions" />
                        <span class="texto-porcentaje color-texto-porcentaje-3">{{Math.round(estadistica.porcent_3)}}%</span>
                    </div>
                </v-card-item>
            </v-card>
        </div>
        
        <div class="py-1 mb-2 mt-6">
            <div class="px-8 pb-6">
                <v-btn
                    class="custom-button"
                    block
                    color="#c4f45d"
                    @click="exportarExcel()"
                    >
                    Exportar Excel
                </v-btn>
            </div>
            
        </div>

        <div class="">
            <div class="my-6 px-4 py-4">

                <div class="buscador-data-table text-right">
                    <div class="buscador-data-table">
                        <input type="search" v-model="buscar" placeholder="Buscar..." autocomplete="off">
                    </div>
                </div>

                <div class="my-2 mb-12 py-6">
                    <div class="">
                        <table class="table">
                            <thead class="headers-table">
                                <tr class="">
                                    <th class="borders-header ocultar-titulo"></th>
                                    <!-- <th class="borders-header titulo-columna borde-izquierdo">#</th> -->
                                    <th class="borders-header titulo-columna borde-izquierdo">Folio</th>
                                    <th class="borders-header hidden-column titulo-columna">Documento</th>
                                    <th class="borders-header titulo-columna">Asunto</th>
                                    <th class="borders-header hidden-column titulo-columna">Dependencia</th>
                                    <th class="borders-header hidden-column titulo-columna">Turnado a</th>
                                    <th class="borders-header hidden-column titulo-columna">Fecha Recepción Oficialia</th>
                                    <th v-if="user.user.departamento_id != 10" class="borders-header hidden-column titulo-columna">Fecha Recepción Área</th>
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
                                    <!-- <td  class="texto-campo-table" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.numero_registro}}
                                    </td> -->
                                    <td class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.folio}}
                                    </td>
                                    <td class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.no_documento}}
                                    </td>
                                    <td class="texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.asunto}}
                                    </td>
                                    <td class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.dependencia}}
                                    </td>
                                    <td class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.departamento}}
                                    </td>
                                    <td class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.fecha_recepcion}}
                                    </td>
                                    <td v-if="user.user.departamento_id != 10" class="hidden-column texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        {{documento.fecha_recepcion_area}}
                                    </td>
                                    <td class="texto-campo-table text-center" :class="documento.estatus_id == 4 ? 'sin-atender' : ''">
                                        <p class="m-0" :class="documento.prioridad == 'Normal' ? 'texto-prioridad-normal' : documento.prioridad == 'Urgente' ? 'texto-prioridad-urgente' : documento.prioridad == 'Extra Urgente' ? 'texto-prioridad-extra-urgente' : ''"><span>{{documento.prioridad}}</span></p>
                                    </td>
                                    <td>
                                        <div class="text-center row justify-content-center">
                                            <div class="">
                                                <v-icon 
                                                    @click="agregarNota(documento)"
                                                    class="mr-1"
                                                    >
                                                        mdi-text-box-edit-outline
                                                </v-icon>
                                                <v-tooltip
                                                    activator="parent"
                                                    location="bottom"
                                                    >
                                                    <span style="font-size: 15px;">Agregar Nota</span>
                                                </v-tooltip>
                                            </div>
                                            
                                            <div>
                                                <v-icon 
                                                    title="Subir evidencias"
                                                    @click="seguimientoDocto(documento)"
                                                    class="ml-1"
                                                    >
                                                        mdi-content-duplicate
                                                </v-icon>
                                                <v-tooltip
                                                    activator="parent"
                                                    location="bottom"
                                                    >
                                                    <span style="font-size: 15px;">Agregar Evidencias</span>
                                                </v-tooltip>
                                            </div>

                                            <!-- <div class="ml-1">
                                                <i style="font-size: 13px;" class="fa fa-certificate" aria-hidden="true" :class="documento.prioridad == 'Normal' ? 'texto-prioridad-normal' : documento.prioridad == 'Urgente' ? 'texto-prioridad-urgente' : documento.prioridad == 'Extra Urgente' ? 'texto-prioridad-extra-urgente' : ''"></i>
                                            </div> -->
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

                <v-dialog
                    v-model="dialogAgregarNota"
                    max-width="800px"
                    persistent
                    >
                    <v-card>
                        <v-card-title>
                            <h3 class="mt-2">Agregar Nota</h3>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-form class="col-12" ref="formAgregarNota">
                                        <v-text-field
                                            v-model="nota.nota"
                                            label="Nota"
                                            :rules="notaRules"
                                            >
                                        </v-text-field>
                                    </v-form>
                                </v-row>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    variant="flat"
                                    color="#881001"
                                    @click="cancelarAgregarNota()"
                                >
                                    <span style="color: #eaeaed;">Cancelar</span>
                                </v-btn>
                                <v-btn
                                    variant="flat"
                                    color="#6a73a0"
                                    @click="guardarNotaDocto()"
                                >
                                    <span style="color: #eaeaed;">Guardar</span>
                                </v-btn>
                            </v-card-actions>
                        </v-card-text>
                    </v-card>
                </v-dialog>

                <v-dialog
                    v-model="dialogSeguimiento"
                    max-width="1000px"
                    persistent
                    >
                    <v-card>
                        <v-card-title>
                            <h3 class="mt-2">Seguimiento de documento</h3>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.no_documento"
                                            label="No. de Documento"
                                            readonly
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.folio"
                                            label="Folio"
                                            readonly
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="doc.tipo_documento"
                                            label="Tipo de documento"
                                            readonly
                                            prepend-inner-icon="mdi-file"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="doc.fecha_emision"
                                            type="date"
                                            label="Fecha de Emisión"
                                            readonly
                                            prepend-inner-icon="mdi-calendar"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <v-text-field
                                            v-model="doc.fecha_recepcion"
                                            type="date"
                                            label="Fecha de Recepción"
                                            readonly
                                            prepend-inner-icon="mdi-calendar"
                                        >
                                        </v-text-field>
                                    </div>
                                    
                                </v-row>

                                <v-divider ></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.emisor"
                                            label="Emisor"
                                            readonly
                                            prepend-inner-icon="mdi-account"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.cargo"
                                            label="Cargo"
                                            readonly
                                            prepend-inner-icon="mdi-chess-knight"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.asunto"
                                            label="Asunto"
                                            readonly
                                            prepend-inner-icon="mdi-tag"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.departamento"
                                            label="Área del emisor"
                                            readonly
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.dirigido_a"
                                            label="Dirigido a"
                                            readonly
                                            prepend-inner-icon="mdi-send"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.cargo_a"
                                            label="Cargo"
                                            readonly
                                            prepend-inner-icon="mdi-chess-knight"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>
                                
                                <v-row>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.observaciones_a"
                                            label="Observaciones"
                                            readonly
                                            prepend-inner-icon="mdi-content-paste"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.dependencia_a"
                                            label="Dependencia"
                                            readonly
                                            prepend-inner-icon="mdi-window-maximize"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.departamento"
                                            label="Turnada a"
                                            readonly
                                            prepend-inner-icon="mdi-transfer"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <v-text-field
                                            v-model="doc.prioridad"
                                            label="Prioridad"
                                            readonly
                                            prepend-inner-icon="mdi-align-horizontal-right"
                                        >
                                        </v-text-field>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <v-row class="mt-4">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <v-text-field
                                            v-model="doc.categoria"
                                            label="Categoría"
                                            readonly
                                            prepend-inner-icon="mdi-star"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <p class="link-archivo mt-4 ml-4" @click="verArchivos(doc)"><v-icon icon="mdi-paperclip"></v-icon> Ver Archivos</p>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>
    
                                <v-row class="mt-4 mb-4">
                                    <h5 class="ml-4" style="font-size: 20px;">Notas: </h5>
                                    <div class="container">
                                        <div v-for=" nota in doc.notas" :key="nota">
                                            - {{nota.nota}}
                                        </div>
                                    </div>
                                </v-row>

                                <v-divider></v-divider>

                                <div class="mt-8" v-if="user.user.tipo_usuario_id == 2">
                                    <v-form ref="formAgregarEvidencia">
                                        <p class="ml-1 mb-4">Estatus Actual: <span style="font-weight: bold;">{{doc.estatus_nombre}}</span></p>
                                        <v-row>
                                            <div class="col-sm-6 col-12">
                                                <v-autocomplete
                                                    v-model="doc.estatus"
                                                    :items="estatus"
                                                    item-title="nombre"
                                                    item-value="id"
                                                    label="Estatus"
                                                    prepend-inner-icon="mdi-content-duplicate"
                                                >
                                                </v-autocomplete>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <v-file-input
                                                    v-model="doc.archivo"
                                                    show-size
                                                    label="Evidencia"
                                                    accept="application/pdf"
                                                    variant="outlined"
                                                    
                                                ></v-file-input>
                                            </div>
                                        </v-row>
                                        <v-row>
                                            <div class="col-md-12 col-12">
                                                <v-textarea 
                                                    v-model="doc.descripcion"
                                                    label="Detalles de evidencia" 
                                                    variant="outlined"
                                                    rows="2"
                                                    :rules="descripcionRules"
                                                    >
                                                </v-textarea>
                                            </div>
                                        </v-row>
                                    </v-form>
                                </div>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    variant="flat"
                                    color="#881001"
                                    @click="cancelarNuevaEvidencia()"
                                >
                                    <span style="color: #eaeaed;">Cerrar</span>
                                </v-btn>
                                <v-btn
                                    variant="flat"
                                    color="#6a73a0"
                                    @click="guardarNuevaEvidencia()"
                                >
                                    <span style="color: #eaeaed;">Guardar</span>
                                </v-btn>
                            </v-card-actions>
                        </v-card-text>
                    </v-card>
                </v-dialog>

                <v-dialog
                    v-model="dialogPdf"
                    max-width="800px"
                    >
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
            </div>
        </div>
    </div>
    <v-dialog v-model="dialogVerarchivos" max-width="600px">
            <v-card>
                <h4>Archivos cargados en este Documento</h4>
                <div class="row justify-content-between" v-for="arch in doc.archivos" :key="arch">
                    <div class="col-md-6 col-12 mb-2 mt-2">
                        <p class="link-archivo ml-4" @click="verArchivo(arch)"><v-icon icon="mdi-paperclip"></v-icon> Ver archivo</p>
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
</template>

<script>
    import { defineComponent } from 'vue';
    import { errorSweetAlert, successSweetAlert, warningSweetAlert} from "../helpers/sweetAlertGlobals";
    import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
    import { Doughnut } from 'vue-chartjs'

    ChartJS.register(ArcElement, Tooltip, Legend)

    export default defineComponent({
        name: 'home',
        data() {
            return {
                show: false,
                show2: false,
                show3: false,
                nota:'',
                estadistica: {
                    total_documentos:''
                },
                dialogAgregarNota: false,
                dialogSeguimiento: false,
                dialogVerarchivos: false,
                docto: '',
                // documentos: [],
                nota: {
                    documento_id: null,
                    nota: '',
                },
                porcent_1: 0,
                porcent_2: 0,
                porcent_3: 0,
                porcent_4: 0,
                porcent_5: 0,
                porcent_6: 0,
                porcent_7: 0,
                porcent_8: 0,
                porcent_9: 0,
                porcent_10: 0,
                archivo: '',
                // documento: [],
                doc: {
                    id : null,
                    folio :'',
                    no_documento:'', 
                    tipo_documento :'',
                    fecha_emision :'',
                    fecha_recepcion :'',
                    dirigido_a :'',
                    cargo :'',
                    asunto :'',
                    departamento :'', 
                    cargo_a :'',
                    observaciones_a:'', 
                    dependencia_a :'',
                    estatus :'',
                    prioridad :'',
                    categoria :'',
                    descripcion :'',
                    archivo: null,
                    tiene_archivo: false,
                    notas:'',
                    emisor: '',
                    estatus_id: '',
                    estatus_nombre: '',
                    archivo: '',
                    archivos: []
                    // archivo_mostrar: null

                },
                chartOptions: {
                    responsive: true
                },
                loading: false,
                elementosPorPagina: 10,
                paginaActual: 1,
                datosPaginados: [],
                mostrar: false,
                from: '',
                to: '',
                numShown: 5,
                current: 1,
                buscar: '',
                notaRules: [
                    v => !!v || 'Campo requerido',
                ],
                dialogPdf: false,
                archivo: '',
                archivoRules: [
                    v => !!v || 'Debes agregar un archivo',
                ],
                descripcionRules: [
                    v => !!v || 'Campo requerido',
                ],
                export:{
                    documentos: []
                }
            }
        },
        components: {
            Doughnut
        },
        created() {
            this.getEstadistica()
            this.getDocumentosHome()
            this.getDocumentosPendientes()
            this.getCatalogoEstatus()
        },
        computed:{
           
            documentos() {
                return this.$store.getters.getDocumentos
            },
            user() {
                return this.$store.getters.user
            },
            chartData() {
                return {
                    datasets: [ 
                        { 
                            backgroundColor: ['#bf151f', '#FFFFFF'],
                            data: [this.porcent_1, this.porcent_2] 
                        } 
                    ]
                }
            },
            chartData2() {
                return {
                    datasets: [ 
                        { 
                            backgroundColor: ['#E78900', '#FFFFFF'],
                            data: [this.porcent_3, this.porcent_4] 
                        } 
                    ]
                }
            },
            chartData3() {
                return {
                    datasets: [ 
                        { 
                            backgroundColor: ['#A3BC39', '#FFFFFF'],
                            data: [this.porcent_5, this.porcent_6] 
                        } 
                    ]
                }
            },
            barra1() {
                return {
                    width: `${this.porcent_7}%`
                }
            },
            barra2() {
                return {
                    width: `${this.porcent_8}%`
                }
            },
            barra3() {
                return {
                    width: `${this.porcent_9}%`
                }
            },
            barra4() {
                return {
                    width: `${this.porcent_10}%`
                }
            },
            estatus() { 
                return this.$store.getters.getCatalogoEstatus
            },
            pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
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
            imprimir() {
                this.impresion = this.form.nombre
            },
            agregarNota(documento) {
                this.dialogAgregarNota = true
                this.nota.documento_id = documento.id
            },
            async seguimientoDocto(docto) {
                if(this.user.user.tipo_usuario_id == 2 && docto.estatus_id == 4)
                {
                    try {
                        let response = await axios.post('/api/documentos/actualizar-estatus-evidencia',docto)
                        if (response.status === 200) {
                            if (response.data.status === "ok") {

                                this.dialogSeguimiento = true
                                this.doc.id = docto.id
                                this.doc.folio = docto.folio
                                this.doc.no_documento = docto.no_documento
                                this.doc.tipo_documento = docto.tipo_documento
                                this.doc.fecha_emision = docto.fecha_emision
                                this.doc.fecha_recepcion = docto.fecha_recepcion
                                this.doc.dirigido_a = docto.dirigido_a
                                this.doc.cargo = docto.cargo
                                this.doc.asunto = docto.asunto
                                this.doc.departamento = docto.dependencia
                                this.doc.cargo_a = docto.cargo_a
                                this.doc.observaciones_a = docto.observaciones_a
                                this.doc.dependencia_a = docto.dependencia_a
                                this.doc.estatus = docto.estatus_id
                                this.doc.estatus_nombre = docto.estatus
                                this.doc.estatus_id = docto.estatus_id
                                this.doc.prioridad = docto.prioridad
                                this.doc.categoria = docto.categoria
                                this.doc.notas = docto.notas
                                this.doc.emisor = docto.emisor
                                this.doc.tiene_archivo = docto.tiene_archivo
                                this.doc.archivos = docto.archivo

                                this.getEstadistica()
                    
                            } else {
                                errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                            }
                        } else {
                            errorSweetAlert('Ocurrió un error al actualizar estatus para cargar evidencia')
                        }
                    } catch (error) {
                        errorSweetAlert('Ocurrió un error al actualizar estatus para cargar evidencia')
                    }
                } else{

                    this.dialogSeguimiento = true
                    this.doc.id = docto.id
                    this.doc.folio = docto.folio
                    this.doc.no_documento = docto.no_documento
                    this.doc.tipo_documento = docto.tipo_documento
                    this.doc.fecha_emision = docto.fecha_emision
                    this.doc.fecha_recepcion = docto.fecha_recepcion
                    this.doc.dirigido_a = docto.dirigido_a
                    this.doc.cargo = docto.cargo
                    this.doc.asunto = docto.asunto
                    this.doc.departamento = docto.dependencia
                    this.doc.cargo_a = docto.cargo_a
                    this.doc.observaciones_a = docto.observaciones_a
                    this.doc.dependencia_a = docto.dependencia_a
                    this.doc.estatus = docto.estatus_id
                    this.doc.estatus_nombre = docto.estatus
                    this.doc.estatus_id = docto.estatus_id
                    this.doc.prioridad = docto.prioridad
                    this.doc.categoria = docto.categoria
                    this.doc.notas = docto.notas
                    this.doc.emisor = docto.emisor
                    this.doc.tiene_archivo = docto.tiene_archivo
                    this.doc.archivos = docto.archivo
                }

            },
            cancelarAgregarNota(){
                this.dialogAgregarNota = false
                this.nota.id = null
                this.nota.nota = ''
            },
            AgregarNuevaEvidencia() {
                this.dialogSeguimiento = true
            },
            cancelarNuevaEvidencia() {
                this.doc.archivo = null
                this.doc.descripcion = ''
                this.dialogSeguimiento = false
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
            async guardarNuevaEvidencia() {
                const { valid } = await this.$refs.formAgregarEvidencia.validate()
                warningSweetAlert('Falta llenar detalle de evidencia')
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nueva evidencia?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/documentos/guardar-evidencia', this.doc, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data',
                                    }
                                })
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar la nueva evidencia.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setDocumentos', result.value.data.documentos)
                                    this.getEstadistica()
                                    this.cancelarNuevaEvidencia()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar la nueva evidencia.')
                            }
                        }
                    })
                }
            },
            async getEstadistica() {
                try {
                    let response = await axios.get('/api/estadistica')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.estadistica = response.data.estadisticas

                            this.porcent_1 = this.estadistica.porcent_1
                            this.porcent_2 = 100 - this.porcent_1
                            this.porcent_3 = this.estadistica.porcent_2
                            this.porcent_4 = 100 - this.porcent_3
                            this.porcent_5 = this.estadistica.porcent_3
                            this.porcent_6 = 100 - this.porcent_5

                            this.porcent_7 = this.estadistica.porcent_4
                            this.porcent_8 = this.estadistica.porcent_5
                            this.porcent_9 = this.estadistica.porcent_6
                            this.porcent_10 = this.estadistica.porcent_7
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                }
            },
            async getDocumentosHome() {
                // this.documentos = []
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
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
            async getDocumentosSinAtender() {
                // this.documentos = []
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/sin-atender')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            // this.documentos = response.data.documentos
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                }
                this.loading = false
            },
            async getDocumentosIniciados() {
                // this.documentos = []
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/iniciados')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            // this.documentos = response.data.documentos
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                }
                this.loading = false
            },
            async getDocumentosEnProceso() {
                // this.documentos = []
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/en-proceso')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            // this.documentos = response.data.documentos
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                }
                this.loading = false
            },
            async getDocumentosAtendidos() {
                // this.documentos = []
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/atendidos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            // this.documentos = response.data.documentos
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las estadísticas')
                }
                this.loading = false
            },
            async guardarNotaDocto() {
                const { valid } = await this.$refs.formAgregarNota.validate()
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nota?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/guardar-notas', this.nota)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar la nota.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setDocumentos', result.value.data.documentos)
                                    this.cancelarAgregarNota()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar la nota.')
                            }
                        }
                    })
                }
            },
            async getTodosDocumentos() {
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/todos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los documentos')
                }
                this.loading = false
            },
            async getDocumentosExtraUrgentes() {
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/extra-urgentes')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los documentos')
                }
                this.loading = false
            },
            async getDocumentosUrgentes() {
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/urgentes')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los documentos')
                }
                this.loading = false
            },
            async getDocumentosNormales() {
                this.loading = true
                try {
                    let response = await axios.get('/api/documentos/normales')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentos', response.data.documentos)
                            this.getDataPagina(1)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los documentos')
                }
                this.loading = false
            },
            // verArchivo(documento) {
            //     var extension = this.archivo.split('.')
            //     if(documento.tiene_archivo){
            //         if(extension[1] == 'pdf'){
            //             this.dialogPdf = true
            //         }
            //         else{
            //            window.open(this.archivo,'_blank')
            //         }
                    
            //     } else {
            //         warningSweetAlert('No se ha subido ningún archivo.')
            //     } 
            // },
            cerrarModalPdf() {
                this.dialogPdf = false
            },
            cerrarModalVerarchivos(){
                this.dialogVerarchivos = false
            },

            async exportarExcel()
            {
                this.export.documentos = this.documentos
                let response = await axios.post('/api/reporte-documentos',this.export,{
                    responseType: "blob",
                }).then((response)=>{    
                    var blob = new Blob([response.data], {
                        type: response.headers["content-type"],
                    });
                    const link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = `Reporte.xlsx`;
                    link.click();
                })
                this.loading2 = false 
            },
            verArchivos() {
                    this.dialogVerarchivos = true
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

            async getDocumentosPendientes() {
                this.loading = true
                try {
                    let response = await axios.post('/api/obtener-pendientes')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            if(response.data.pendientes > 0 )
                            {
                                warningSweetAlert('Tienes '+response.data.pendientes+' documentos sin atender')
                            }
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los documentos pendientes')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los documentos pendientes')
                }
                this.loading = false
            },
        },
    })
</script>