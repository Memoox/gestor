<template>
    <div class="container-fluid my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Reportes</h2>
        </div>

        <!-- <div class="buscador-data-table text-right">
            <div class="buscador-data-table">
                <input type="search" v-model="buscar" placeholder="Buscar..." autocomplete="off">
            </div>
        </div> -->
        <v-form ref="formfiltro">
            <v-card class="col-md-12 col-12">
                
                <v-container fluid>
                    <v-row >
                        <div class="col-md-2 col-12">
                            <v-text-field
                            type="date"
                            label="Fecha emision"
                            variant="solo"
                            v-model="filtro.f_inicial"
                            :rules="campoRules"
                            ></v-text-field>
                        </div>
                        <div class="col-md-2 col-12">
                            <v-text-field
                            type="date"
                            label="Fecha recepcion"
                            variant="solo"
                            v-model="filtro.f_final"
                            :rules="campoRules"
                            
                            ></v-text-field>
                        </div>
                        <div class="col-md-2 col-12">
                            <v-select :items="this.prioridades"  :rules="campoRules" item-title="name" item-value="id" label="Prioridad"  variant="solo" v-model="filtro.prioridad">
                            </v-select>
                        </div>
                        <div class="col-md-2 col-12">
                            <v-select variant="solo" v-model="filtro.estatus"  :rules="campoRules" :items="this.estatus" item-title="name" item-value="id" label="Estatus">
                            </v-select>
                        </div>
                        <div class="col-md-4 col-12">
                            <v-select variant="solo" v-model="filtro.dpto" :rules="campoRules"   :items="this.dptos" item-title="nombre" item-value="id" label="Áreas">
                            </v-select>
                        </div>
                        
                    </v-row>
                    <v-row>
                        <div class="col-md-2 col-12">
                            <v-checkbox v-model="filtro.historico">
                                <template v-slot:label>
                            <span id="checkboxLabel">Mostrar Histórico</span>
                                </template>
                            </v-checkbox>
                        </div>
                    </v-row>
                </v-container>
            </v-card>

        </v-form>

        <div class="my-6">
            <div class="text-left my-6">
                <h3 class="titulo-catalogos">Documentos Recibidos</h3>
            </div>
        </div>
            


        <div class="my-2 mb-12 py-6">
            <div class="col-md-12 col-12">
                <table class="table">
                    <thead class="headers-table">
                        <tr>
                            <!-- <th class="borders-header ocultar-titulo"></th> -->
                            <!-- <th class="borders-header titulo-columna borde-izquierdo">#</th> -->
                            <th class="borders-header titulo-columna borde-izquierdo">Folio</th>
                            <th class="borders-header hidden-column titulo-columna">Documento</th>
                            <th class="borders-header titulo-columna">Asunto</th>
                            <th class="borders-header hidden-column titulo-columna">Dependencia</th>
                            <th class="borders-header hidden-column titulo-columna">Turnado a</th>
                            <th class="borders-header hidden-column titulo-columna">Fecha Emision</th>
                            <th class="borders-header hidden-column titulo-columna">Fecha Recepción</th>
                            <th class="borders-header titulo-columna">Prioridad</th>
                            <th class="borders-header titulo-columna borde-derecho">Status</th>
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
                            <!-- <p>
                                <i style="font-size: 13px;" class="fa fa-certificate" aria-hidden="true" :class="documento.estatus_id == 1 ? 'texto-estatus-iniciado' : documento.estatus_id == 2 ? 'texto-estatus-proceso' : documento.estatus_id == 3 ? 'texto-estatus-atendido' : documento.estatus_id == 4 ? 'texto-estatus-sin-atender' : ''"></i>
                                <v-tooltip
                                    activator="parent"
                                    location="bottom"
                                    >
                                    <span style="font-size: 15px;">{{documento.estatus}}</span>
                                </v-tooltip>
                            </p> -->
                            <!-- <td class="texto-campo-table">
                                {{documento.numero_registro}}
                            </td> -->
                            <td class="hidden-column text-center">
                                {{documento.folio}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.no_documento}}
                            </td>
                            <td class="text-center">
                                {{documento.asunto}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.dependencia}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.dirigido_a}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.fecha_emision}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.fecha_recepcion}}
                            </td>
                            <td class="text-center">
                                    <p class="m-0" :class="documento.prioridad == 'Normal' ? 'texto-prioridad-normal' : documento.prioridad == 'Urgente' ? 'texto-prioridad-urgente' : documento.prioridad == 'Extra Urgente' ? 'texto-prioridad-extra-urgente' : ''"><span>{{documento.prioridad}}</span></p>
                            </td>
                            
                            <td class="hidden-column text-center">
                                {{documento.estatus}}
                            </td>
                                <!-- <div class="text-center">
                                    <v-icon
                                        title="Ver Documento"
                                        @click="openModal(documento)"
                                        class="mr-1"
                                    >
                                        
                                    </v-icon>

                                    <v-tooltip
                                        activator="parent"
                                        location="bottom"
                                        >
                                        <span style="font-size: 15px;">Ver Documento</span>
                                    </v-tooltip>
                                </div> -->
                            
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
        <v-card-actions v-if="documentos.length > 0">
            <v-spacer></v-spacer>
                    <v-btn
                        variant="flat"
                        color="#008f39"
                        @click="descargarTabla()"
                    >
                    <span style="color: #eaeaed;">Descargar</span>
                    </v-btn>
        </v-card-actions>

    </div>
</template>

<script>
    import { defineComponent } from "vue"
    import { successSweetAlert, errorSweetAlert,warningSweetAlert } from '../helpers/sweetAlertGlobals'

    export default defineComponent({
        name:'documento',
        data() {
            return { 
                prioridades:[
                    {
                    id: 1,
                    name: 'Extra Urgente'
                    },
                    {
                    id: 2,
                    name: 'Urgente'
                    },
                    {
                    id: 3,
                    name: 'Normal'
                    },
                    {
                    id: 4,
                    name: 'Conocimiento'
                    },
                ],
                estatus:[
                    {
                    id: 1,
                    name: 'Iniciado'
                    },
                    {
                    id: 2,
                    name: 'En proceso'
                    },
                    {
                    id: 3,
                    name: 'Atendido'
                    },
                    {
                    id: 4,
                    name: 'Sin Atender'
                    },
                ],
                // dialogMostrarInfo: false,
                loading: false,
                // dialogPdf: false,
                // archivo: '',
                documento: {
                    id: null,
                    folio:'',
                    no_documento: '',
                    // tipo_documento: '',
                    fecha_emision: '',
                    fecha_recepcion: '',
                    // emisor:'',
                    // cargo:'',
                    asunto: '',
                    dependencia:'',
                    // dirigido_a:'',
                    // cargo_a:'',
                    // observaciones_a:'',
                    // dependencia_a:'',
                    // departamento_id:'',
                    prioridad_id: '',
                    // categoria_id: '',
                    // tipo_archivo: '',
                    // archivo: '',
                    tipoDocumento: '',
                    // departamento: '',
                    prioridad: '',
                    // categoria: '',
                    estatus: '',
                    // notas: [],
                    // evidencias: [],
                },
                filtro:{
                    f_inicial: '',
                    f_final: '',
                    prioridad: '',
                    estatus: '',
                    historico: '',
                    dpto: ''
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
                f_final: new Date().toISOString().slice(0,10),
                campoRules: [
                    v => !!v || 'Campo requerido',
                ],
                export:{
                    documentos: []
                },
                dptos:[]
            }
        },
        created(){
            this.getDocumentosReporte()
            this.getDepartamentosConUsuario()
        },
        computed: {
            documentos() {
                return this.$store.getters.getDocumentosReporte
            },
            // documentos() {
            //     return this.$store.getters.getDocumentosFiltro
            // },

            pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
            },
            departamentos() {
                return this.$store.getters.getCatalogoDepartamentosUsuarios
            },
        },
        watch: {
            'filtro.historico': function () {
                // console.log(this.filtro)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }
            },
            'filtro.estatus': function(){
                // console.log(this.filtro)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                   
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }

            },
            'filtro.f_inicial': function(){
                // console.log(this.filtro)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                   
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }

            },
            'filtro.f_final': function(){
                // console.log(this.filtro)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                   
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }

            },
            'filtro.prioridad': function(){
                // console.log(this.filtro)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                   
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }

            },
            'filtro.dpto': function(){
                // console.log(this.filtro.dpto)
                if(this.filtro.historico){
                    // Consulta con historico
                    this.getDocumentosFiltroHistorico()
                   
                }else{
                    // consulta normal
                    this.getDocumentosFiltro()
                }

            },
            mostrar: function () {
                if (this.mostrar) {
                    this.getDataPagina(1)
                }
            }
        },
        methods: {
            async getDocumentosReporte() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/documentos-reporte')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setDocumentosReporte', response.data.documentosReporte)
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
            async getDocumentosFiltroHistorico() 
            {
                const { valid } = await this.$refs.formfiltro.validate()
                if(valid)
                {
                        this.loading = true
                    try {
                        let response = await axios.post('/api/catalogos/documentos-filtro-historico', this.filtro)
                        if (response.status === 200) {
                            if (response.data.status === "ok") {
                                this.$store.commit('setDocumentosReporte', response.data.documentosReporte)
                                this.getDataPagina(1)
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

                }
              

            },
            async getDocumentosFiltro() {
                const { valid } = await this.$refs.formfiltro.validate()
                if(valid)
                {
                        this.loading = true
                    try {
                        let response = await axios.post('/api/catalogos/documentos-filtros', this.filtro)
                        if (response.status === 200) {
                            if (response.data.status === "ok") {
                                // documentos = response.data.documentosFiltro
                                this.$store.commit('setDocumentosReporte', response.data.documentosReporte)
                                // this.mostrar = true
                                this.getDataPagina(1)
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
                }
               

            },
            async descargarTabla()
            {
                this.export.documentos = this.documentos
                console.log(this.export)
                let response = await axios.post('/api/reporte-exportar',this.export,{
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
                // this.loading2 = false 
            },
            async getDepartamentosConUsuario() {
                try {
                    let response = await axios.get('/api/catalogos/departamentos-con-usuario')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoDepartamentosUsuarios', response.data.departamentos)
                            // console.log(response.data.departamentos)
                            this.dptos = response.data.departamentos
                            this.dptos.push({id: 999,nombre: 'TODAS',numero_registro: 999},)
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

<style>
#checkboxLabel {
   color: black;
   font-size: 16px;
}
</style>