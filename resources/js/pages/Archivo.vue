<template>
    <div class="container-fluid my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Documentos Atendidos</h2>
        </div>

        <div class="buscador-data-table text-right">
            <div class="buscador-data-table">
                <input type="search" v-model="buscar" placeholder="Buscar..." autocomplete="off">
            </div>
        </div>

        <div class="my-2 mb-12 py-6">
            <div class="">
                <table class="table">
                    <thead class="headers-table">
                        <tr>
                            <th class="borders-header ocultar-titulo"></th>
                            <!-- <th class="borders-header titulo-columna borde-izquierdo">#</th> -->
                            <th class="borders-header titulo-columna borde-izquierdo">Folio </th>
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
                                <div class="text-center">
                                    <v-icon
                                        title="Ver Documento"
                                        @click="openModal(documento)"
                                        class="mr-1"
                                    >
                                        mdi-folder-open
                                    </v-icon>

                                    <v-tooltip
                                        activator="parent"
                                        location="bottom"
                                        >
                                        <span style="font-size: 15px;">Ver Documento</span>
                                    </v-tooltip>
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
            v-model="dialogMostrarInfo"
            max-width="1000px"
            >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2 text-left">No. Documento: {{ documento.no_documento }}</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <div class="col-md-3 col-12">
                                <v-text-field
                                    v-model="documento.no_documento"
                                    variant="solo"
                                    readonly
                                    label="No. de documento"
                                ></v-text-field>
                            </div>
                            <div class="col-md-3 col-12">
                                <v-text-field
                                    v-model="documento.tipoDocumento"
                                    variant="solo"
                                    readonly
                                    label="Tipo documento"
                                ></v-text-field>
                            </div>
                            <div class="col-md-3 col-12">
                                <v-text-field
                                    v-model="documento.fecha_emision"
                                    variant="solo"
                                    readonly
                                    label="Fecha emisión"
                                ></v-text-field>
                            </div>
                            <div class="col-md-3 col-12">
                                <v-text-field
                                    v-model="documento.fecha_recepcion"
                                    variant="solo"
                                    readonly
                                    label="Fecha recepción"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-divider></v-divider>

                        <v-row class="mt-4">
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.emisor"
                                    readonly
                                    label="Emisor"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.cargo"
                                    readonly
                                    label="Cargo"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-row class="mt-4">
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.asunto"
                                    readonly
                                    label="Asunto"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.dependencia"
                                    readonly
                                    label="Área del emisor"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-divider></v-divider>

                        <v-row class="mt-4">
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.dirigido_a"
                                    readonly
                                    label="Dirigido a"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.cargo_a"
                                    readonly
                                    label="Cargo"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-row class="mt-4">
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.observaciones_a"
                                    readonly
                                    label="Observaciones"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-12">
                                <v-text-field
                                    v-model="documento.dependencia_a"
                                    readonly
                                    label="Dependencia"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-divider></v-divider>

                        <v-row class="mt-4"> 
                            <div class="col-sm-4 col-12">
                                <v-text-field
                                    v-model="documento.departamento"
                                    readonly
                                    label="Turnado a"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-sm-4 col-12">
                                <v-text-field
                                    v-model="documento.prioridad"
                                    readonly
                                    label="Prioridad"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                            <div class="col-sm-4 col-12">
                                <v-text-field
                                    v-model="documento.estatus"
                                    readonly
                                    label="Estatus"
                                    variant="solo"
                                ></v-text-field>
                            </div>
                        </v-row>

                        <v-divider></v-divider>

                        <v-row class="mt-4">
                            <h5 class="ml-4" style="font-size: 20px;">Archivos:</h5>
                            <div class="container">
                                <div class="col-md-6 col-sm-6 col-12" v-for="archivo in documento.archivo" :key="archivo">
                                    <p class="link-archivo mt-4 ml-4" @click="verArchivo(archivo)"><v-icon icon="mdi-paperclip"></v-icon> Ver archivo</p>
                                </div>
                                <!-- <div class="col-md-6 col-sm-6 col-12">
                                    <p class="link-archivo mt-4 ml-4" @click="verArchivo(documento)"><v-icon icon="mdi-paperclip"></v-icon> Ver archivo</p>
                                </div> -->
                            </div>
                          
                            <!-- <div class="col-md-6 col-sm-6 col-12">
                                <v-text-field
                                    v-model="documento.estatus"
                                    readonly
                                    label="Estatus"
                                    variant="solo"
                                ></v-text-field>
                            </div> -->
                        </v-row>

                        <v-divider></v-divider>
    
                        <v-row class="mt-4 mb-4">
                            <h5 class="ml-4" style="font-size: 20px;">Notas:</h5>
                            <div class="container">
                                <div v-for=" nota in documento.notas" :key="nota">
                                    - {{nota.nota}}
                                </div>
                            </div>
                        </v-row>

                        <v-divider></v-divider>

                        <v-row class="mt-4 mb-4">
                            <h5 class="ml-4" style="font-size: 20px;">Evidencias:</h5>
                            <div class="container">
                                <div class="row justify-content-between" v-for="evidencia in documento.evidencias" :key="evidencia">
                                    <div class="col-md-6 col-12 mb-2 mt-2">
                                        <p>- Descripcion: {{evidencia.descripcion}}</p>
                                    </div>
                                    <div v-if="evidencia.tiene_archivo == true" class="col-md-6 col-12">
                                        <p class="link-archivo mt-2 ml-4" @click="verArchivo(evidencia)"><v-icon icon="mdi-paperclip"></v-icon> Ver evidencia</p>
                                    </div>
                                </div>
                            </div>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="flat"
                            color="#881001"
                            @click="closeModal()"
                        >
                            <span style="color: #eaeaed;">Cerrar</span>
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="dialogPdf"
            max-width="600px"
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
</template>

<script>
    import { defineComponent } from "vue"
    import { successSweetAlert, errorSweetAlert,warningSweetAlert } from '../helpers/sweetAlertGlobals'

    export default defineComponent({
        name:'archivo',
        data() {
            return { 
                dialogMostrarInfo: false,
                loading: false,
                dialogPdf: false,
                archivo: '',
                documento: {
                    id: null,
                    folio:'',
                    no_documento: '',
                    tipo_documento: '',
                    fecha_emision: '',
                    fecha_recepcion: '',
                    emisor:'',
                    cargo:'',
                    asunto: '',
                    dependencia:'',
                    dirigido_a:'',
                    cargo_a:'',
                    observaciones_a:'',
                    dependencia_a:'',
                    departamento_id:'',
                    prioridad_id: '',
                    categoria_id: '',
                    tipo_archivo: '',
                    archivo: [],
                    tipoDocumento: '',
                    departamento: '',
                    prioridad: '',
                    categoria: '',
                    estatus: '',
                    notas: [],
                    evidencias: [],
                    tiene_archivo: ''
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
            }
        },
        created(){
            this.getDocumentosArchivo()
        },
        computed: {
            documentos() {
                return this.$store.getters.getDocumentosArchivo
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
            async getDocumentosArchivo() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/documentos-archivo')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentosArchivo', response.data.documentosArchivo)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el listado de documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el listado de documentos')
                }
                this.loading = false

            },
            openModal(documento) {
                this.documento.no_documento = documento.no_documento
                this.documento.tipoDocumento = documento.tipoDocumento
                this.documento.fecha_emision = documento.fecha_emision
                this.documento.fecha_recepcion = documento.fecha_recepcion.trim()
                this.documento.emisor = documento.emisor
                this.documento.cargo = documento.cargo
                this.documento.asunto = documento.asunto
                this.documento.dependencia = documento.dependencia
                this.documento.dirigido_a = documento.dirigido_a
                this.documento.cargo_a = documento.cargo_a
                this.documento.observaciones_a = documento.observaciones_a
                this.documento.dependencia_a = documento.dependencia_a
                this.documento.departamento = documento.departamento
                this.documento.prioridad = documento.prioridad
                this.documento.categoria = documento.categoria
                this.documento.archivo = documento.archivo
                this.documento.estatus = documento.estatus
                this.documento.tiene_archivo = documento.tiene_archivo
                this.documento.notas = documento.notas
                this.documento.evidencias = documento.evidencias
                this.dialogMostrarInfo = true
            },
            verArchivo(documento) {
                // console.log(documento)
                var extension = documento.archivo.split('.')
                if(documento.archivo){
                    if(extension[1] == 'pdf'){
                        this.archivo = documento.archivo
                        this.dialogPdf = true
                    }
                    else{
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
            closeModal() {
                this.dialogMostrarInfo = false
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
        }
    })
</script>