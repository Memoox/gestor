<template>
    <div class="container-fluid my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Totales por Áreas</h2>
        </div>

        <v-form ref="formtotales">
            <!-- <v-card class="col-md-6 col-12"></v-card> -->
            <v-card class="col-md-12 col-12">
                <v-container fluid>
                    <v-row>
                        <div class="col-md-6 col-12"></div>
                        <div class="col-md-2 col-12 mt-4">
                            <v-text-field
                            type="date"
                            label="Fecha inicio"
                            variant="solo"
                            :rules="campoRules"
                            v-model="fechas.inicio"
                            ></v-text-field>
                        </div>
                        <div class="col-md-2 col-12 mt-4">
                            <v-text-field
                            type="date"
                            label="Fecha fin"
                            variant="solo"
                            :rules="campoRules"
                            v-model="fechas.fin"
                            ></v-text-field>
                        </div>
                        
                        <div class="col-md-2 col-12">
                            <v-btn
                            color="#6a73a0"
                            class="mt-6 mb-2 boton-nuevo"
                            large
                            @click="DocumentosTotales"
                            
                            >Buscar</v-btn>
                        </div>

                    </v-row>
                </v-container>
            </v-card>
        </v-form>

        <div class="my-6">
            <div class="text-left my-6">
                <h3 class="titulo-catalogos">Resultado</h3>
            </div>
        </div>

        <div class="my-2 mb-12 py-6">
            <div class="col-md-12 col-12">
                <table class="table">
                    <thead class="headers-table">
                        <tr>
                            <th class="borders-header titulo-columna borde-izquierdo">Áreas</th>
                            <th class="borders-header hidden-column titulo-columna">Turnados</th>
                            <th class="borders-header hidden-column titulo-columna">Sin Atender</th>
                            <th class="borders-header hidden-column titulo-columna">Iniciados</th>
                            <th class="borders-header hidden-column titulo-columna">En Proceso</th>
                            <th class="borders-header titulo-columna borde-derecho">Atendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <th colspan="6">
                                <p class="text-center texto-cargando-datos">Cargando datos...</p>
                                <div class="linear-activity">
                                    <div class="indeterminate"></div>
                                </div>
                            </th>
                        </tr>
                        <tr v-else v-for="documento in datosPaginados" :key="documento.id">
                            <td class="hidden-column ">
                                {{documento.nombre}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.total}}
                            </td>
                            <td class="text-center">
                                {{documento.sin_atender}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.iniciados}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.en_proceso}}
                            </td>
                            <td class="hidden-column text-center">
                                {{documento.atendidos}}
                            </td>
                            <!-- <td class="hidden-column text-center">
                                {{documento.fecha_recepcion}}
                            </td> -->
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
        
    </div>
</template>

<script>
    import { defineComponent } from "vue"
    import { successSweetAlert, errorSweetAlert,warningSweetAlert } from '../helpers/sweetAlertGlobals'

export default defineComponent({
    name:'documento',
    data(){
        return{
            documentos:[],
            loading: false,
            elementosPorPagina: 10,
            paginaActual: 1,
            datosPaginados: [],
            mostrar: false,
            from: '',
            to: '',
            numShown: 5,
            current: 1,
            fechas:{
                inicio: '',
                fin: ''
            },
            campoRules: [
                    v => !!v || 'Campo requerido',
                ],
        }
    },
    created(){
      
    },
    computed:{
        // documentos(){
        //     return this.$store.getters.getDocumentosTotales
        // },
        pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
            },
    },
    watch:{
        mostrar: function () {
                if (this.mostrar) {
                    this.getDataPagina(1)
                }
            }
    },
    methods:{
        
        async DocumentosTotales() 
            {
                const { valid } = await this.$refs.formtotales.validate()
                if(valid)
                {
                        this.loading = true
                    try {
                        let response = await axios.post('/api/documentos/totales', this.fechas)
                        if (response.status === 200) {
                            if (response.data.status === "ok") {
                                this.documentos = response.data.documentosTotales
                                // console.log(response.data.documentosTotales)
                                // this.$store.commit('setDocumentosTotales', response.data.documentosTotales)
                                this.getDataPagina(1)
                                this.mostrar = true
                                //  this.fechas = ''
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
    },
})
</script>
