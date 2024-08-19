<template>
      <div class="container my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Catalogo de Departamentos</h2>
            <v-btn
                color="#6a73a0"
                class="mt-4 mb-2 boton-nuevo"
                large
                title="Nuevo Departamento"
                @click="nuevodepto"
                append-icon="mdi-plus"
            >
                Nuevo Departamento
            </v-btn>
        </div>

        <div class="buscador-data-table text-right">
            <div class="buscador-data-table">
                <input type="search" v-model="buscar" placeholder="Buscar..." autocomplete="off">
            </div>
        </div>

        <div class="my-2 mb-12 py-6">
            <div class="container-fluid">
                <table class="table">
                    <thead class="headers-table">
                        <tr>
                            <th class="titulo-columna borde-izquierdo">#</th>
                            <th class="titulo-columna">Departamento</th>
                            <th class="titulo-columna borde-derecho">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <th colspan="3">
                                <p class="text-center texto-cargando-datos">Cargando datos...</p>
                                <div class="linear-activity">
                                    <div class="indeterminate"></div>
                                </div>
                            </th>
                        </tr>
                        <tr v-else v-for="departamento in datosPaginados" :key="departamento.id">
                            <td class="text-center">
                                {{departamento.numero_registro}}
                            </td>
                            <td class="text-center">
                                {{departamento.nombre}}
                            </td>
                            <td>
                                <div class="text-center row justify-content-center">
                                    <div>
                                        <v-icon 
                                            title="Editar Departamento"
                                            @click="editarDepto(departamento)"
                                            class="mr-1"
                                        >
                                            mdi-text-box-edit-outline
                                        </v-icon>
                                        
                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Editar Departamento</span>
                                        </v-tooltip>
                                    </div>

                                    <div>

                                        <v-icon 
                                            title="Eliminar Departamento"
                                            @click="eliminarDepto(departamento)"
                                            class="ml-1"
                                        >
                                            mdi-trash-can
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Eliminar Departamento</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template v-if="departamentos.length > 0">
                <div class="row justify-content-between container">
                    <div>
                        <p class="text-resultados mt-2">
                            Mostrando
                            <span>{{from}}</span>
                            -
                            <span>{{to}}</span>
                            de
                            <span>{{departamentos.length}}</span>
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
            v-model="dialogNuevoDepto"
            max-width="800px"
            persistent
            >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Agregar Departamento</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formNuevoDepto">
                                <v-text-field
                                    v-model="depto.nombre"
                                    label="Nombre del departamento"
                                    :rules="nombreRules"
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
                            @click="cancelarAgregarDepto()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarNuevoDepto()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="dialogEditarDepto"
            max-width="800px"
            persistent
            >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Editar Departamento</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formEditarDepto">
                                <v-text-field
                                    v-model="depto.nombre"
                                    label="Nombre del departamento"
                                    :rules="nombreRules"
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
                            @click="cancelarEditarDepto()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarCambiosEditarDepto()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { defineComponent } from "vue"
    import { errorSweetAlert, successSweetAlert } from "../helpers/sweetAlertGlobals"

    export default defineComponent({
        name: 'catalogo-depto',
        data () {
            return { 
                dialogNuevoDepto: false,
                dialogEditarDepto: false,
                depto: {
                    id: null,
                    nombre: '',
                },
                loading:false,
                elementosPorPagina: 10,
                paginaActual: 1,
                datosPaginados: [],
                mostrar: false,
                from: '',
                to: '',
                numShown: 5,
                current: 1,
                buscar: '',
                nombreRules: [
                    v => !!v || 'El nombre del departamento es requerido',
                ],
            }
        },
        computed:{
            departamentos(){
                return this.$store.getters.getCatalogoDepartamentos
            },
            pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
            },
        },
        created() {
            this.getCatalogoDepartamentos()
        },
        watch: {
            buscar: function () {
                if (!this.buscar.length == 0) {
                    this.datosPaginados = this.departamentos.filter(item => {
                        return item.nombre.toLowerCase().includes(this.buscar.toLowerCase())
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
            nuevodepto() {
                this.dialogNuevoDepto = true
            },
            cancelarAgregarDepto(){
                this.dialogNuevoDepto = false,
                this.depto.id = '',
                this.depto.nombre = ''
            },
            async getCatalogoDepartamentos() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/departamentos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoDepartamentos', response.data.departamentos)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de departamentos')
                }
                this.loading = false
            },
            totalPaginas() {
                return Math.ceil(this.departamentos.length / this.elementosPorPagina)
            },
            getDataPagina(noPagina) {
                this.paginaActual = noPagina
                this.datosPaginados = []

                let ini = (noPagina * this.elementosPorPagina) - this.elementosPorPagina
                let fin = (noPagina * this.elementosPorPagina)

                for (let index = ini; index < fin; index++) {
                    if (this.departamentos[index]) {
                        this.datosPaginados.push(this.departamentos[index])
                    }
                }

                // Para el texto "Mostrando 1 - 10 de 20 resultados"
                this.from = ini+1
                if (noPagina < this.totalPaginas()) {
                    this.to = fin
                } else {
                    this.to = this.departamentos.length
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
            // Guardar nuevo departamento 
            async guardarNuevoDepto() {
                const { valid } = await this.$refs.formNuevoDepto.validate()
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nuevo departamento?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/requisito/guardar-depto', this.depto)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar el nuevo departamento.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoDepartamentos', result.value.data.departamentos)
                                    this.cancelarAgregarDepto()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar el nuevo departamento.')
                            }
                        }
                    })
                }
            },
            // Abrir modal de editar usuario ya con los datos cargados
            editarDepto(depto) {
                this.dialogEditarDepto = true
                this.depto.id = depto.id
                this.depto.nombre = depto.nombre
            },
            // boton para cerrar el modal
            cancelarEditarDepto() {
                this.dialogEditarDepto = false
                this.depto.id = null,
                this.depto.nombre = ''
            },
            // Guardar Cambios de usuario 
            async guardarCambiosEditarDepto() {
                const { valid } = await this.$refs.formEditarDepto.validate()
                if (valid) {
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
                                let response = await axios.post('/api/requisito/editar-depto', this.depto)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al actualizar los datos del departamento.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoDepartamentos', result.value.data.deptos)
                                    this.cancelarEditarDepto()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al actualizar los datos del departamento.')
                            }
                        }
                    })
                }
            },
            // "elimina" cambia estatus del usuario 
            eliminarDepto(departamentos) {
                Swal.fire({
                    title: 'Este departamento se ocupa en diferentes documentos, ¿Realmente necesita eliminarlo?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085D6',
                    cancelButtonColor: '#D33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            let response = await axios.post('/api/requisito/eliminar-depto', departamentos)
                            return response
                        } catch (error) {
                            errorSweetAlert('Ocurrió un error al eliminar este departamento.')
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value.status === 200) {
                            if (result.value.data.status === "ok") {
                                successSweetAlert(result.value.data.message)
                                this.$store.commit('setCatalogoDepartamentos', result.value.data.departamentos)
                                this.getDataPagina(1)
                            } else {
                                errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                            }
                        } else {
                            errorSweetAlert('Ocurrió un error al eliminar este departamento.')
                        }
                    }
                })
            },
        },
    })
</script>