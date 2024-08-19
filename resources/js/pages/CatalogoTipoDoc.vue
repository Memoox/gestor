<template>
    <div class="container my-6">
      <div class="text-center my-6">
          <h2 class="titulo-catalogos">Catalogo de Tipo de Documentos</h2>
          <v-btn
              color="#6a73a0"
              class="mt-4 mb-2 boton-nuevo"
              large
              title="Nuevo Tipo de Documento"
              @click="nuevodepto"
              append-icon="mdi-plus"
          >
              Nuevo Tipo de Documento
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
                          <th class="titulo-columna">Tipo de Documento</th>
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
                      <tr v-else v-for="item in datosPaginados" :key="item.id">
                          <td class="text-center">
                              {{item.numero_registro}}
                          </td>
                          <td class="text-center">
                              {{item.nombre}}
                          </td>
                          <td>
                              <div class="text-center row justify-content-center">
                                  <div>
                                      <v-icon 
                                          title="Editar Tipo de Documento"
                                          @click="editarTipoDoc(item)"
                                          class="mr-1"
                                      >
                                          mdi-text-box-edit-outline
                                      </v-icon>
                                      
                                      <v-tooltip
                                          activator="parent"
                                          location="bottom"
                                          >
                                          <span style="font-size: 15px;">Editar Tipo de Documento</span>
                                      </v-tooltip>
                                  </div>

                                  <div>

                                      <v-icon 
                                          title="Eliminar Tipo de Documento"
                                          @click="eliminarTipoDoc(item)"
                                          class="ml-1"
                                      >
                                          mdi-trash-can
                                      </v-icon>

                                      <v-tooltip
                                          activator="parent"
                                          location="bottom"
                                          >
                                          <span style="font-size: 15px;">Eliminar Tipo de Docuemento</span>
                                      </v-tooltip>
                                  </div>
                              </div>
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>
          <template v-if="tipos_documentos.length > 0">
              <div class="row justify-content-between container">
                  <div>
                      <p class="text-resultados mt-2">
                          Mostrando
                          <span>{{from}}</span>
                          -
                          <span>{{to}}</span>
                          de
                          <span>{{tipos_documentos.length}}</span>
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
          v-model="dialogNuevoTipoDoc"
          max-width="800px"
          persistent
          >
          <v-card>
              <v-card-title>
                  <h3 class="mt-2">Agregar Tipo Documento</h3>
              </v-card-title>
              <v-divider></v-divider>
              <v-card-text>
                  <v-container>
                      <v-row>
                          <v-form class="col-12" ref="formNuevoDepto">
                              <v-text-field
                                  v-model="tipoDoc.nombre"
                                  label="Nombre del Tipo de Documento"
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
                          @click="cancelarAgregarTipoDoc()"
                      >
                          <span style="color: #eaeaed;">Cancelar</span>
                      </v-btn>
                      <v-btn
                          variant="flat"
                          color="#6a73a0"
                          @click="guardarNuevoTipoDoc()"
                      >
                          <span style="color: #eaeaed;">Guardar</span>
                      </v-btn>
              </v-card-actions>
              </v-card-text>
          </v-card>
      </v-dialog>

      <v-dialog
          v-model="dialogEditarTipoDoc"
          max-width="800px"
          persistent
          >
          <v-card>
              <v-card-title>
                  <h3 class="mt-2">Editar Tipo Documento</h3>
              </v-card-title>
              <v-divider></v-divider>
              <v-card-text>
                  <v-container>
                      <v-row>
                          <v-form class="col-12" ref="formEditarTipoDoc">
                              <v-text-field
                                  v-model="tipoDoc.nombre"
                                  label="Nombre del Tipo de Documento"
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
                          @click="cancelareditarTipoDoc()"
                      >
                          <span style="color: #eaeaed;">Cancelar</span>
                      </v-btn>
                      <v-btn
                          variant="flat"
                          color="#6a73a0"
                          @click="guardarCambioseditarTipoDoc()"
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
      name: 'catalogo-TipoDoc',
      data () {
          return { 
              dialogNuevoTipoDoc: false,
              dialogEditarTipoDoc: false,
              tipoDoc: {
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
                  v => !!v || 'El nombre del Tipo de  Documento  es requerido',
              ],
          }
      },
      computed:{
         
          tipos_documentos(){
              return this.$store.getters.getCatalogoTiposDocumentos
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
          this.getCatalogoTiposDocumentos()
      },
      watch: {
          buscar: function () {
              if (!this.buscar.length == 0) {
                  this.datosPaginados = this.tipos_documentos.filter(item => {
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
        async getCatalogoTiposDocumentos() {
            this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/tipos-documentos')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoTiposDocumentos', response.data.tipos_documentos)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de tipos de documentos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de tipos de documentos')
                }
                this.loading = false
             },
          nuevodepto() {
              this.dialogNuevoTipoDoc = true
          },
          cancelarAgregarTipoDoc(){
              this.dialogNuevoTipoDoc = false,
              this.tipoDoc.id = '',
              this.tipoDoc.nombre = ''
          },          
      
          totalPaginas() {
              return Math.ceil(this.tipos_documentos.length / this.elementosPorPagina)
          },
          getDataPagina(noPagina) {
            
              this.paginaActual = noPagina
              this.datosPaginados = []

              let ini = (noPagina * this.elementosPorPagina) - this.elementosPorPagina
              let fin = (noPagina * this.elementosPorPagina)

              for (let index = ini; index < fin; index++) {
                
                  if (this.tipos_documentos[index]) {
                      this.datosPaginados.push(this.tipos_documentos[index])
                  }
              }

              // Para el texto "Mostrando 1 - 10 de 20 resultados"
              this.from = ini+1
              if (noPagina < this.totalPaginas()) {
                  this.to = fin
              } else {
                  this.to = this.tipos_documentos.length
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
          async guardarNuevoTipoDoc() {
              const { valid } = await this.$refs.formNuevoDepto.validate()
              if (valid) {
                  Swal.fire({
                      title: '¿Guardar nuevo Tipo de Documento?',
                      icon: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#3085D6',
                      cancelButtonColor: '#D33',
                      confirmButtonText: 'Si, guardar',
                      cancelButtonText: 'Cancelar',
                      showLoaderOnConfirm: true,
                      preConfirm: async () => {
                          try {
                              let response = await axios.post('/api/tipos-documentos/guardar-nuevo', this.tipoDoc)
                              return response
                          } catch (error) {
                              errorSweetAlert('Ocurrió un error al guardar el nuevo tipo de documento.')
                          }
                      },
                      allowOutsideClick: () => !Swal.isLoading()
                  }).then((result) => {
                      if (result.isConfirmed) {
                          if (result.value.status === 200) {
                              if (result.value.data.status === "ok") {
                                  successSweetAlert(result.value.data.message)
                                  this.$store.commit('setCatalogoTiposDocumentos', result.value.data.tipos_documentos)
                                  this.cancelarAgregarTipoDoc()
                                  this.getDataPagina(1)
                              } else {
                                  errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                              }
                          } else {
                              errorSweetAlert('Ocurrió un error al guardar el nuevo tipo de Documento.')
                          }
                      }
                  })
              }
          },
          // Abrir modal de editar usuario ya con los datos cargados
          editarTipoDoc(depto) {
              this.dialogEditarTipoDoc = true
              this.tipoDoc.id = depto.id
              this.tipoDoc.nombre = depto.nombre
          },
          // boton para cerrar el modal
          cancelareditarTipoDoc() {
              this.dialogEditarTipoDoc = false
              this.tipoDoc.id = null,
              this.tipoDoc.nombre = ''
          },
          // Guardar Cambios de usuario 
          async guardarCambioseditarTipoDoc() {
              const { valid } = await this.$refs.formEditarTipoDoc.validate()
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
                              let response = await axios.post('/api/tipos-documentos/actualizar-tipoDoc', this.tipoDoc)
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
                                  this.$store.commit('setCatalogoTiposDocumentos', result.value.data.tipos_documentos)
                                  this.cancelareditarTipoDoc()
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
          eliminarTipoDoc(tipoDoc) {
              Swal.fire({
                  title: 'Eliminar este tipo de Documento',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085D6',
                  cancelButtonColor: '#D33',
                  confirmButtonText: 'Si, eliminar',
                  cancelButtonText: 'Cancelar',
                  showLoaderOnConfirm: true,
                  preConfirm: async () => {
                      try {
                          let response = await axios.post('/api/tipos-documentos/eliminar-tipoDoc', tipoDoc)
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
                              this.$store.commit('setCatalogoTiposDocumentos', result.value.data.tipos_documentos)
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