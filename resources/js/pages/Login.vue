<template>
    <div class="conteiner-seg">
        <div class="imagen animate__animated animate__slideInLeft"></div>
        <div class="app animate__animated animate__zoomIn">
            <form class="form-login" autocomplete="off" @submit.prevent>    
                <br>
                <h1 class="titulo-bienvenido">Bienvenido</h1> 
                <p>Oficialía<br></p>    
                <div class="col-md-12 form-group">
                    <div class="inner-addon left-addon">
                        <i class="fa fa-user"></i>   
                        <input type="text" name="usuario" id="usuario"  class="form-control form-control-lg user" autocomplete="off" placeholder="Usuario" required autofocus v-model="form.usuario">
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="inner-addon left-addon">
                        <i class="fa fa-lock"></i>                                    
                        <input type="password" name="password" id="password" class="form-control form-control-lg pass" autocomplete="off" placeholder="Contraseña" required v-model="form.password" @keyup.enter="login()">
                    </div>       
                </div>
                <div class="form-group" style="margin-left: 5rem; transform: scale(0.9)">
                    <vue-recaptcha v-show="showRecaptcha" sitekey="6LdY6ygkAAAAAC3dL13YHpsc75y12t31MKfRMnn4"
                            @verify="recaptchaVerified"
                            @expire="recaptchaExpired"
                            ref="vueRecaptcha">
                    </vue-recaptcha> 
                    <label v-if="mostrarlabel" style="color: white">Porfavor verifica que no seas UN ROBOT</label>       
                </div>
                <div class="col-md-12 form-group">
                    <button class="btn" type="submit" @click="login()">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from "vue";
    import  vueRecaptcha  from 'vue3-recaptcha2';

    export default defineComponent({
        name: 'login',
        components: {
            vueRecaptcha
        },
        data() {
            return {
                mostrarlabel: false,
                form: {
                    usuario: '',
                    password: '',
                },
                valid: true,
                show: false,
                loading: false,
                usuarioRules: [
                    v => !!v || 'El nombre de usuario es requerido'
                ],
                passwordRules: [
                    v => !!v || 'La contraseña es requerida'
                ],
            }
        },
        created() {
            this.automaticLogin()
        },
        computed: {
            showRecaptcha() {
                return this.$store.getters.getShowRecaptcha
            },
            cont() {
                return this.$store.getters.getContRecaptcha
            }
        },
        methods: {
            automaticLogin() {
                var urlParams = new URLSearchParams(window.location.search)
                if (urlParams.size > 0) {
                    var dataStringBase64Safe = urlParams.get('data')
    
                    var dataStringBase64 = decodeURIComponent(dataStringBase64Safe)
                    var dataString = window.atob(dataStringBase64)
                    var data = JSON.parse(dataString)

                    this.form.usuario = data.user
                    this.form.password = data.password
                    this.login()
                }
            },
            recaptchaVerified(response) {
                this.vueRecaptcha = response
            },
            recaptchaExpired() {
                this.$refs.vueRecaptcha.reset();
            },
            login() {
                this.loading = true
                if (this.cont < 3) {
                    this.$store.dispatch('login', {
                        usuario: this.form.usuario,
                        password: this.form.password                        
                    }).then(() => {
                        // console.log("udnudnud")
                    }).catch(err => {
                        console.log(err)
                    })
                } else {
                    if (this.vueRecaptcha) {
                        this.$store.dispatch('login', {
                            usuario: this.form.usuario,
                            password: this.form.password
                        }).then(() => {

                        }).catch(err => {
                            console.log(err)
                        })
                    } else {
                        this.mostrarlabel = true
                    }
                }
                this.loading = false
            }
        }
    })
</script>

<style scoped>
    body {
        background-color: black;
    }
</style>