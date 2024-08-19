<template>
    <div :class="user ? 'otherColor' : 'back-color'">
        <header class="page-header" v-if="user">
            <div class="logo">
                <picture>
                    <!-- <source srcset="views/build/img/logo.avif" type="imagen/avif"> 
                    <source srcset="views/build/img/logo.webp" type="imagen/webp">  -->
                    <img loading="lazy" width="300"  src="../../../public/img/logo.png" alt="Logo Poder Judicial del Estado de Puebla">
                </picture> 
            </div>   
            
            <div class="separador-m">&nbsp;</div>
            <div class="separador-v">
                <i class="fa fa-bars m-1" @click="showNav ? showNav = false : showNav = true"></i>  
            </div>

            <div v-if="showNav" >
                <ul class="bg-light shadow-3 py-2 px-3" style="background-color: #6a73a0!important;">
                    <v-divider></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;">
                        <button :class="currentRoute == 'Home' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/')">Inicio</button>
                    </li>
                    <v-divider v-if="user.user.tipo_usuario_id != 3 && user.user.departamento_id != 1 && user.user.departamento_id != 10"></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;" v-if="user.user.tipo_usuario_id != 3">
                        <button :class="currentRoute == 'GestionDocumentos' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/gestion-documentos')">Gestión</button>
                    </li>
                    <v-divider></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;">
                        <button :class="currentRoute == 'Archivo' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/archivo')">Archivo</button>
                    </li>
                    <v-divider v-if="user.user.tipo_usuario_id == 1"></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;" v-if="user.user.tipo_usuario_id == 1">
                        <button :class="currentRoute == 'CatalogoUsuarios' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/catalogo-usuarios')">Usuarios</button>
                    </li>
                    <v-divider v-if="user.user.tipo_usuario_id == 1"></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;" v-if="user.user.tipo_usuario_id == 1">
                        <button :class="currentRoute == 'CatalogoDepto' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/catalogo-depto')">Departamentos</button>
                    </li>
                    <v-divider v-if="user.user.tipo_usuario_id == 1"></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;" v-if="user.user.tipo_usuario_id == 1">
                        <button :class="currentRoute == 'Totales' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/total')">Totales</button>
                    </li>
                    <!-- <v-divider v-if="user.user.tipo_usuario_id != 3"></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;" v-if="user.user.tipo_usuario_id == 1">
                        <button :class="currentRoute == 'CatalogoTipoDoc' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/catalogo-tipoDoc')">Tipo Documento</button>
                    </li> -->
                    <v-divider></v-divider>
                    <li class="btn btn-link btn-block m-0 p-2" style="background-color: #6a73a0!important;">
                        <button class="text-white" @click="logout()">Salir</button>
                    </li>
                </ul>
            </div>
            
            <div class="separador-menu">
                <div class="info-menu">
                    <i class="fa fa-bars m-2"></i>
                    <nav class="navegacion">
                        <ul class="navbar-nav pr-2">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'Home' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/')">Inicio</button>
                            </li>
                        </ul>
                        <ul class="navbar-nav pr-2" v-if="user.user.tipo_usuario_id != 3  && user.user.departamento_id == 1 || user.user.departamento_id == 10">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'GestionDocumentos' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/gestion-documentos')">Gestión</button>
                            </li>
                        </ul>
                        <ul class="navbar-nav pr-2">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'Archivo' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/archivo')">Archivo</button>
                            </li>
                        </ul>

                        <ul class="navbar-nav pr-2" v-if="user.user.tipo_usuario_id == 1">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'CatalogoUsuarios' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/catalogo-usuarios')">Usuarios</button>
                            </li>
                        </ul>
                        <ul class="navbar-nav pr-2" v-if="user.user.tipo_usuario_id == 1">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'CatalogoDepto' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/catalogo-depto')">Departamentos</button>
                            </li>
                        </ul>
                        <!-- <li class="navbar-nav nav-item dropdown pr-2" v-if="user.user.tipo_usuario_id == 1">
                            <a class="ml-2 nav-link dropdown-toggle" :class="currentRoute == 'CatalogoDepto' || currentRoute == 'CatalogoCategorias' || currentRoute == 'CatalogoTipoDoc' ? 'texto-green-2' : 'text-white'" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Catálogos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" @click="this.$router.push('/catalogo-depto')">Departamentos</a>
                               
                            </div>
                        </li> -->
                        <ul class="navbar-nav pr-2" v-if="user.user.tipo_usuario_id != 3">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'Reportes' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/reportes')">Reportes</button>
                            </li>
                        </ul>
                        <ul class="navbar-nav pr-2" v-if="user.user.tipo_usuario_id == 1">
                            <li class="nav-item">
                                <button class="ml-2" :class="currentRoute == 'Totales' ? 'texto-green-2' : 'text-white'" @click="this.$router.push('/total')">Totales</button>
                            </li>
                        </ul>
                        <ul class="navbar-nav pr-4">
                            <li class="nav-item">
                                <button class="text-white ml-2" @click="logout()">Salir</button>
                            </li>
                        </ul>
                    </nav>
                    
                    <div class="datos-usuario">
                        <div class="info">
                            <div class="img-usuario">
                                <picture>
                                    <!-- <source srcset="views/build/img/usuarios/usuario.avif" type="imagen/avif"> 
                                    <source srcset="views/build/img/usuarios/usuario.webp" type="imagen/webp">  -->
                                    <img loading="lazy" width="200" height="300" src="../../../public/img/usuario.png" alt="">
                                </picture>
                            </div>

                            <p>{{ user.departamento }}</p>
                            <p>{{ user.user.nombre }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <router-view></router-view>

        <!-- <footer class="footer seccion" v-if="token != 0">    
            <p class="copyright">&copy; 2023 Poder Judicial del Estado de Puebla.</p>
        </footer> -->
    </div>
</template>

<script>
    import { defineComponent } from "vue";
    import { errorSweetAlert } from "../helpers/sweetAlertGlobals"

    export default defineComponent({
        name: 'app',
        data() {
            return {
                showNav: false,
            }
        },
        created() {
            const userInfo = localStorage.getItem('user')
            if (userInfo) {
                const userData = JSON.parse(userInfo)
                this.$store.commit('setUserData', userData)
            }
            axios.interceptors.response.use(
                response => response,
                error => {
                    if (error.response.status === 401) {
                        this.$store.dispatch('logout')
                    }
                    return Promise.reject(error)
                }
            )
            if (this.user) {
                this.getCatalogoDepartamentos()
                // this.getCatalogoCategorias()}}}}
                // this.getCatalogoPrioridades()
                // this.getCatalogoEstatus()
                // this.getCatalogoTiposDocumentos()
            }
        },
        computed: {
            currentRoute() {
                return this.$route.name
            },
            user() {
                return this.$store.getters.user
            }
        },
        methods: {
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
           
            logout() {
                this.$store.dispatch('logout')
            }
        }
    })
</script>

<style scoped>
    .back-color {
        background-color: #353535;
    }

    .otherColor {
        background-color: white;
    }
</style>