import { createWebHistory, createRouter } from "vue-router"
import store from './store'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import GestionDocumentos from './pages/GestionDocumentos.vue'
import CatalogoUsuarios from './pages/CatalogoUsuarios.vue'
import CatalogoDepto from './pages/CatalogoDepto.vue'
import CatalogoCategorias from './pages/CatalogoCategorias.vue'
import Archivo from './pages/Archivo.vue'
import CatalogoTipoDoc from './pages/CatalogoTipoDoc.vue'
import Reportes from './pages/Reportes.vue'
import Totales from './pages/Totales.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/gestion-documentos',
        name: 'GestionDocumentos',
        component: GestionDocumentos,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/catalogo-usuarios',
        name: 'CatalogoUsuarios',
        component: CatalogoUsuarios,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/catalogo-depto',
        name: 'CatalogoDepto',
        component: CatalogoDepto,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/catalogo-categorias',
        name: 'CatalogoCategorias',
        component: CatalogoCategorias,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/archivo',
        name: 'Archivo',
        component: Archivo,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/catalogo-tipoDoc',
        name: 'CatalogoTipoDoc',
        component: CatalogoTipoDoc,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/reportes',
        name: 'Reportes',
        component: Reportes,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/total',
        name: 'Totales',
        component: Totales,
        meta: {
            requiresAuth: true
        }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const currentUser = store.state.user

    if (requiresAuth && !currentUser) {
        next('/login')
    } else if (to.path == '/login' && currentUser) {
        next('/home')
    } else {
        next()
    }
})

export default router