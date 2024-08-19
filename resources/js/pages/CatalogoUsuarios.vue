<template>
    <div class="container my-6">
        <div class="text-center my-6">
            <h2>Gestor de usuarios</h2>
            <div>
                <v-btn
                    color="#6a73a0"
                    class="mt-4 mb-2 boton-nuevo"
                    large
                    title="Nuevo usuario"
                    @click="AgregarUsuario"
                    append-icon="mdi-plus"
                >
                    Nuevo Usuario
                </v-btn>
            </div>
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
                            <th class="titulo-columna">Nombre</th>
                            <th class="titulo-columna">Departamento</th>
                            <th class="hidden-column titulo-columna">Correo</th>
                            <th class="hidden-column titulo-columna">Usuario</th>
                            <th class="titulo-columna borde-derecho">Acciones</th>
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
                        <tr v-else v-for="usuario in datosPaginados" :key="usuario.id">
                            <td class="text-center">
                                {{usuario.numero_registro}}
                            </td>
                            <td class="text-center">
                                {{usuario.nombre}} {{usuario.apellido}}
                            </td>
                            <td class="text-center">
                                {{usuario.departamento}}
                            </td>
                            <td class="text-center">
                                {{usuario.email}}
                            </td>
                            <td class="text-center">
                                {{usuario.username}}
                            </td>
                            <td>
                                <div class="text-center row justify-content-center">
                                    <div>
                                        <v-icon 
                                        title="Editar Usuario"
                                        @click="EditarUsuario(usuario)"
                                        class="mr-1"
                                        >
                                            mdi-text-box-edit-outline
                                        </v-icon>

                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Editar Usuario</span>
                                        </v-tooltip>
                                    </div>
                                    
                                    <div>
                                        <v-icon 
                                            title="Eliminar Usuario"
                                            @click="eliminarUsuario(usuario)"
                                            class="ml-1"
                                        >
                                            mdi-trash-can
                                        </v-icon>
                                        <v-tooltip
                                            activator="parent"
                                            location="bottom"
                                            >
                                            <span style="font-size: 15px;">Eliminar Usuario</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template v-if="usuarios.length > 0">
                <div class="row justify-content-between container">
                    <div>
                        <p class="text-resultados mt-2">
                            Mostrando
                            <span>{{from}}</span>
                            -
                            <span>{{to}}</span>
                            de
                            <span>{{usuarios.length}}</span>
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
            v-model="dialogAgregarUsuarios"
            max-width="800px"
            persistent
            >
            <v-card>
                <v-card-title>
                  <h3 class="mt-2">Agregar Usuario</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formAgregarUsuario">
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.nombre"
                                            label="Nombre"
                                            :rules="nombreRules"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.apellido"
                                            label="Apellido(s)"
                                            :rules="apellidoRules"
                                        ></v-text-field>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-autocomplete
                                            v-model="usuario.departamento_id"
                                            label="Departamento"
                                            :items="departamentos"
                                            item-title="nombre"
                                            item-value="id"
                                            key="departamento"
                                            persistent-hint
                                            :rules="departamentoRules"
                                        ></v-autocomplete>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-autocomplete
                                            v-model="usuario.tipo_usuario_id"
                                            label="Tipo usuario"
                                            :items="tipoUsuario"
                                            item-title="nombre"
                                            item-value="id"
                                            key="tipoUsuario"
                                            persistent-hint
                                            :rules="tipoUsuarioRules"
                                        ></v-autocomplete>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.email"
                                            :rules="[rules.email]"
                                            label="Correo Electrónico"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.email_confirm"
                                            :rules="[rules.email, rules.confirm]"
                                            label="Confirma el Correo Electrónico"
                                        ></v-text-field>
                                    </div>
                                </div>
                                <v-text-field
                                    v-model="usuario.username"
                                    label="Nombre de Usuario"
                                    :rules="usernameRules"
                                ></v-text-field>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.password"
                                            label="Contraseña"
                                            :rules="passwordRules"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.password_confirm"
                                            label="Confirma la Contraseña"
                                            :rules="passwordConfirmRules"
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-form>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="flat"
                            color="#881001"
                            @click="cancelarAgregarNuevoUsuario()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarNuevoUsuario()"
                        >
                            <span style="color: #eaeaed;">Guardar</span>
                        </v-btn>
                </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="dialogEditarUsuario"
            max-width="800px"
            >
            <v-card>
                <v-card-title>
                  <h3 class="mt-2">Editar Usuario</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-form class="col-12" ref="formEditarUsuario">
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.nombre"
                                            label="Nombre"
                                            :rules="nombreRules"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.apellido"
                                            label="Apellido(s)"
                                            :rules="apellidoRules"
                                        ></v-text-field>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-autocomplete
                                            v-model="usuario.departamento_id"
                                            label="Departamento"
                                            :items="departamentos"
                                            item-title="nombre"
                                            item-value="id"
                                            key="departamento"
                                            persistent-hint
                                            :rules="departamentoRules"
                                        ></v-autocomplete>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-autocomplete
                                            v-model="usuario.tipo_usuario_id"
                                            label="Tipo usuario"
                                            :items="tipoUsuario"
                                            item-title="nombre"
                                            item-value="id"
                                            key="tipoUsuario"
                                            persistent-hint
                                            :rules="tipoUsuarioRules"
                                        ></v-autocomplete>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.email"
                                            :rules="[rules.email]"
                                            label="Correo Electrónico"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.email_confirm"
                                            :rules="[rules.email, rules.confirm]"
                                            label="Confirma el Correo Electrónico"
                                        ></v-text-field>
                                    </div>
                                </div>
                                <v-text-field
                                    v-model="usuario.username"
                                    label="Nombre de Usuario"
                                    :rules="usernameRules"
                                ></v-text-field>
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.password"
                                            label="Contraseña"
                                            :rules="passwordRules"
                                        ></v-text-field>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <v-text-field
                                            v-model="usuario.password_confirm"
                                            label="Confirma la Contraseña"
                                            :rules="passwordConfirmRules"
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-form>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="flat"
                            color="#881001"
                            @click="CancelarEditarUsuario()"
                        >
                            <span style="color: #eaeaed;">Cancelar</span>
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="#6a73a0"
                            @click="guardarCambiosEditarUsuario()"
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
        name: 'catalogo-usuarios',
        data () {
            return { 
                dialogAgregarUsuarios: false,
                dialogEditarUsuario: false,
                usuario: {
                    id: null,
                    nombre: '',
                    apellido:'',
                    email:'',
                    email_confirm: '',
                    password:'',
                    password_confirm: '',
                    departamento_id: null,
                    tipo_usuario_id: null,
                    username:'',
                },
                loading: false,
                rules:{
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'El campo no contiene un correo electrónico valido'
                    },
                    confirm: value => {
                        return value == this.usuario.email || 'El correo debe coincidir'
                    }
                },
                nombreRules: [
                    v => !!v || 'El nombre es requerido',
                ],
                apellidoRules: [
                    v => !!v || 'El apellido es requerido',
                ],
                departamentoRules: [
                    v => !!v || 'El departamento es requerido',
                ],
                tipoUsuarioRules: [
                    v => !!v || 'El tipo de usuario es requerido',
                ],
                usernameRules: [
                    v => !!v || 'El nombre de usuario es requerido',
                ],
                passwordRules: [
                    v => !!v || 'La contraseña es requerida',
                ],
                passwordConfirmRules: [
                    v => v == this.usuario.password || 'La contraseña debe coincidir',
                ],
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
        created() {
            this.getCatalogoUsuarios()
            this.getCatalogoDepartamentos()
            this.getCatalogoTiposUsuarios()
        },
        computed: {
            usuarios(){
                return this.$store.getters.getCatalogoUsuarios
            },
            departamentos() {
                return this.$store.getters.getCatalogoDepartamentos
            },
            tipoUsuario() {
                return this.$store.getters.getCatalogoTiposUsuarios
            },
            pages() {
                const numShown = Math.min(this.numShown, this.totalPaginas())
                let first = this.current - Math.floor(numShown / 2)
                first = Math.max(first, 1)
                first = Math.min(first, this.totalPaginas() - numShown + 1)
                return [...Array(numShown)].map((k, i) => i + first)
            }
        },
        watch: {
            buscar: function () {
                if (!this.buscar.length == 0) {
                    this.datosPaginados = this.usuarios.filter(item => {
                        return item.username.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.nombre.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.apellido.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.email.toLowerCase().includes(this.buscar.toLowerCase())
                        || item.departamento.toLowerCase().includes(this.buscar.toLowerCase())
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
            AgregarUsuario() {
                this.dialogAgregarUsuarios = true
            },
            cancelarAgregarNuevoUsuario() {
                this.dialogAgregarUsuarios = false,
                this.usuario.id = '',
                this.usuario.nombre = '',
                this.usuario.apellido = ''
                this.usuario.email = ''
                this.usuario.departamento_id = ''
                this.usuario.tipo_usuario_id = ''
                this.usuario.username = ''
                this.usuario.password = ''               
            },
            async guardarNuevoUsuario() {
                const { valid } = await this.$refs.formAgregarUsuario.validate()
                if (valid) {
                    Swal.fire({
                        title: '¿Guardar nuevo usuario?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#D33',
                        confirmButtonText: 'Si, guardar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                let response = await axios.post('/api/usuarios/agregar-usuario', this.usuario)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al guardar al nuevo usuario.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoUsuarios', result.value.data.usuarios)
                                    this.cancelarAgregarNuevoUsuario()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al guardar al nuevo usuario.')
                            }
                        }
                    })
                }
            },
            // Abrir modal de editar usuario ya con los datos cargados
            EditarUsuario(usuario) {
                this.dialogEditarUsuario = true
                this.usuario.id = usuario.id
                this.usuario.nombre = usuario.nombre
                this.usuario.apellido = usuario.apellido
                this.usuario.email = usuario.email
                this.usuario.password = usuario.password
                this.usuario.tipo_usuario_id = usuario.tipo_usuario_id
                this.usuario.departamento_id = usuario.departamento_id
                this.usuario.username = usuario.username
                this.usuario.email_confirm = usuario.email
                this.usuario.password_confirm = usuario.password
            },
            // boton para cerrar el modal
            CancelarEditarUsuario() {
                this.dialogEditarUsuario = false
                this.usuario.id = null,
                this.usuario.nombre = ''
                this.usuario.apellido = ''
                this.usuario.email = ''
                this.usuario.password = ''
                this.usuario.tipo_usuario_id = ''
                this.usuario.departamento_id = ''
                this.usuario.username = ''
                this.usuario.email_confirm = ''
                this.usuario.password_confirm = ''
            },
            // Guardar Cambios de usuario 
            async guardarCambiosEditarUsuario() {
                const { valid } = await this.$refs.formEditarUsuario.validate()
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
                                let response = await axios.post('/api/usuarios/actualizar-usuario', this.usuario)
                                return response
                            } catch (error) {
                                errorSweetAlert('Ocurrió un error al actualizar los datos del usuario.')
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value.status === 200) {
                                if (result.value.data.status === "ok") {
                                    successSweetAlert(result.value.data.message)
                                    this.$store.commit('setCatalogoUsuarios', result.value.data.usuarios)
                                    this.CancelarEditarUsuario()
                                    this.getDataPagina(1)
                                } else {
                                    errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                                }
                            } else {
                                errorSweetAlert('Ocurrió un error al actualizar los datos del usuario.')
                            }
                        }
                    })
                }
            },
            // "elimina" cambia estatus del usuario 
            eliminarUsuario(usuario) {
                Swal.fire({
                  title: '¿Eliminar Usuario?',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085D6',
                  cancelButtonColor: '#D33',
                  confirmButtonText: 'Si, eliminar',
                  cancelButtonText: 'Cancelar',
                  showLoaderOnConfirm: true,
                  preConfirm: async () => {
                      try {
                          let response = await axios.post('/api/usuarios/eliminar-usuario', usuario)
                          return response
                      } catch (error) {
                          errorSweetAlert('Ocurrió un error al eliminar este usuario.')
                      }
                  },
                  allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                  if (result.isConfirmed) {
                      if (result.value.status === 200) {
                          if (result.value.data.status === "ok") {
                              successSweetAlert(result.value.data.message)
                              this.$store.commit('setCatalogoUsuarios', result.value.data.usuarios)
                              this.getDataPagina(1)
                          } else {
                              errorSweetAlert(`${result.value.data.message}<br>Error: ${result.value.data.error}<br>Location: ${result.value.data.location}<br>Line: ${result.value.data.line}`)
                          }
                      } else {
                          errorSweetAlert('Ocurrió un error al eliminar este usuario.')
                      }
                  }
              })

            },
            async getCatalogoUsuarios() {
                this.loading = true
                try {
                    let response = await axios.get('/api/catalogos/usuarios')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoUsuarios', response.data.usuarios)
                            this.mostrar = true
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de usuarios')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de usuarios')
                }
                this.loading = false
            },
            totalPaginas() {
                return Math.ceil(this.usuarios.length / this.elementosPorPagina)
            },
            getDataPagina(noPagina) {
                this.paginaActual = noPagina
                this.datosPaginados = []

                let ini = (noPagina * this.elementosPorPagina) - this.elementosPorPagina
                let fin = (noPagina * this.elementosPorPagina)

                for (let index = ini; index < fin; index++) {
                    if (this.usuarios[index]) {
                        this.datosPaginados.push(this.usuarios[index])
                    }
                }

                // Para el texto "Mostrando 1 - 10 de 20 resultados"
                this.from = ini+1
                if (noPagina < this.totalPaginas()) {
                    this.to = fin
                } else {
                    this.to = this.usuarios.length
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
            async getCatalogoTiposUsuarios() {
                try {
                    let response = await axios.get('/api/catalogos/tipos-usuarios')
                    if (response.status === 200) {
                        if (response.data.status === "ok") {
                            this.$store.commit('setCatalogoTiposUsuarios', response.data.tipoUsuario)
                        } else {
                            errorSweetAlert(`${response.data.message}<br>Error: ${response.data.error}<br>Location: ${response.data.location}<br>Line: ${response.data.line}`)
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener el catalogo de tipos de usuario')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener el catalogo de tipos de usuario')
                }
            },
        },
    })
</script>