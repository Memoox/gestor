<template>
    <div class="container my-6">
        <div class="text-center my-6">
            <h2 class="titulo-catalogos">Catalogo de Categorías</h2>
            <v-btn
                color="#6a73a0"
                class="mt-4 mb-2 boton-nuevo"
                large
                title="Nueva Categoría"
                @click="nuevacategoria"
                append-icon="mdi-plus"
            >
                Nueva Categoría
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
                            <th class="titulo-columna">Categoría</th>
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
                        <tr v-else v-for="categoria in datosPaginados" :key="categoria.id">
                            <td class="text-center">
                                {{categoria.numero_registro}}
                            </td>
                            <td class="text-center">
                                {{categoria.nombre}}
                            </td>
                            <td>
                                <div class="text-center row justify-content-center">
                                    <div>
                                        <v-icon 
                                            title="Editar Categoría"
                                            @click="EditarCategoria(categoria)"
                                            class="mr-1"
                                        >
                                            mdi-text-box-edit-outline
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Editar Categoría</span>
                                        </v-tooltip>
                                    </div>

                                    <div>
                                        <v-icon 
                                            title="Eliminar Categoría"
                                            @click="eliminarCategoria(categoria)"
                                            class="ml-1"
                                        >
                                            mdi-trash-can
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Eliminar Categoría</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template v-if="categorias.length > 0">
                <div class="row justify-content-between container">
                    <div>
                        <p class="text-resultados mt-2">
                            Mostrando
                            <span>{{from}}</span>
                            -
                            <span>{{to}}</span>
                            de
                            <span>{{categorias.length}}</span>
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
            v-model="dialogNuevaCategoria"
            max-width="800px"
            persistent
            >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Agregar Categoría</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formNuevaCategoria">
                                <v-text-field
                                    v-model="categoria.nombre"
                                    label="Nombre de la categoría"
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
                            @click="cancelarAgregarCategoria()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarNuevoCategoria()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="dialogEditarCategoria"
            max-width="800px"
            persistent
        >
            <v-card>
                <v-card-title>
                    <h3 class="mt-2">Editar Categoría</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formEditarCategoria">
                                <v-text-field
                                    v-model="categoria.nombre"
                                    label="Nombre de la categoría"
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
                            @click="CancelarEditarcategoria()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarCambiosEditarCategoria()"
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
        name: 'catalogo-categorias',
        data () {
            return { 
                dialogNuevaCategoria: false,
                dialogEditarCategoria: false,
                categoria: {
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
                    v => !!v || 'El nombre de la categoría es requerido',
                ],
            }
        },
        computed:{
            categorias(){
                return this.$store.getters.getCatalogoCategorias
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
            this.getCatalogoCategorias()
        },
        watch: {
            buscar: function () {
                if (!this.buscar.length == 0) {
                    this.datosPaginados = this.categorias.filter(item => {
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
            nuevacategoria() {
                this.dialogNuevaCategoria = true
            },
            cancelarAgregarCategoria(){
                this.dialogNuevaCategoria = false,
                this.categoria.id = '',
                this.categoria.nombre = ''
            },
            async getCatalogoCategorias() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/categorias')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoCategorias', response.data.categorias)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de categorias')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de categorias')
                }
                this.loading = false
            },
            totalPaginas() {
                return Math.ceil(this.categorias.length / this.elementosPorPagina)
            },
            getDataPagina(noPagina) {
                this.paginaActual = noPagina
                this.datosPaginados = []

                let ini = (noPagina * this.elementosPorPagina) - this.elementosPorPagina
                let fin = (noPagina * this.elementosPorPagina)

                for (let index = ini; index < fin; index++) {
                    if (this.categorias[index]) {
                        this.datosPaginados.push(this.categorias[index])
                    }
                }

                // Para el texto "Mostrando 1 - 10 de 20 resultados"
                this.from = ini+1
                if (noPagina < this.totalPaginas()) {
                    this.to = fin
                } else {
                    this.to = this.categorias.length
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
            async guardarNuevoCategoria() {
                const { valid } = await this.$refs.formNuevaCategoria.validate()
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nueva categoría?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/requisito/guardar-categoria', this.categoria)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar la categoria.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoCategorias', result.value.data.categorias)
                                    this.cancelarAgregarCategoria()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar la categoria.')
                            }
                        }
                    })
                }
            },
            // Abrir modal de editar usuario ya con los datos cargados
            EditarCategoria(categoria) {
                this.dialogEditarCategoria = true
                this.categoria.id = categoria.id
                this.categoria.nombre = categoria.nombre
            },
            // boton para cerrar el modal
            CancelarEditarcategoria() {
                this.dialogEditarCategoria = false
                this.categoria.id = null,
                this.categoria.nombre = ''
            },
            // Guardar Cambios de usuario 
            async guardarCambiosEditarCategoria() {
                const { valid } = await this.$refs.formEditarCategoria.validate()
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
                                let response = await axios.post('/api/requisito/editar-categoria', this.categoria)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al actualizar los datos de la categoria.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoCategorias', result.value.data.categorias)
                                    this.CancelarEditarcategoria()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al actualizar los datos del la categoria.')
                            }
                        }
                    })
                }
            },
            eliminarCategoria(categorias) {
                Swal.fire({
                  title: 'Esta categoría esta relacionada a diferentes documentos, ¿Realmente necesita eliminarla?',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085D6',
                  cancelButtonColor: '#D33',
                  confirmButtonText: 'Si, eliminar',
                  cancelButtonText: 'Cancelar',
                  showLoaderOnConfirm: true,
                  preConfirm: async () => {
                      try {
                          let response = await axios.post('/api/requisito/eliminar-categoria',categorias)
                          return response
                      } catch (error) {
                          errorSweetAlert('Ocurrió un error al eliminar esta categoria.')
                      }
                  },
                  allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                  if (result.isConfirmed) {
                      if (result.value.status === 200) {
                            if (result.value.data.status === "ok") {
                                successSweetAlert(result.value.data.message)
                                this.$store.commit('setCatalogoCategorias', result.value.data.categorias)
                                this.getDataPagina(1)
                            } else {
                                errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                            }
                      } else {
                          errorSweetAlert('Ocurrió un error al eliminar esta categoria.')
                      }
                  }
              })
            },
        },
    })
</script>