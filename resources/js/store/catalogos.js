export default {
    state: {
        usuarios: [],
        departamentos: [],
        departamentosUsuarios: [],
        categorias: [],
        categorias2: [],
        prioridades: [],
        estatus: [],
        tiposDocumentos: [],
        tipoUsuario:[],
    },
    getters: {
        getCatalogoUsuarios(state) {
            return state.usuarios
        },
        getCatalogoDepartamentos(state) {
            return state.departamentos
        },
        getCatalogoDepartamentosUsuarios(state) {
            return state.departamentosUsuarios
        },
        getCatalogoCategorias(state) {
            return state.categorias
        },
        getCatalogoCategorias2(state) {
            return state.categorias2
        },
        getCatalogoPrioridades(state) {
            return state.prioridades
        },
        getCatalogoEstatus(state) {
            return state.estatus
        },
        getCatalogoTiposDocumentos(state) {
            return state.tiposDocumentos
        },
        getCatalogoTiposUsuarios(state) {
            return state.tipoUsuario
        }
    },
    mutations: {
        setCatalogoUsuarios(state, payload) {
            state.usuarios = payload
        },
        setCatalogoDepartamentos(state, payload) {
            state.departamentos = payload
        },
        setCatalogoDepartamentosUsuarios(state, payload) {
            state.departamentosUsuarios = payload
        },
        setCatalogoCategorias(state, payload) {
            state.categorias = payload
        },
        setCatalogoCategorias2(state, payload) {
            state.categorias2 = payload
        },
        setCatalogoPrioridades(state, payload) {
            state.prioridades = payload
        },
        setCatalogoEstatus(state, payload) {
            state.prioridades = payload
        },
        setCatalogoEstatus(state, payload) {
            state.estatus = payload
        },
        setCatalogoTiposDocumentos(state, payload) {
            state.tiposDocumentos = payload
        },
        setCatalogoTiposUsuarios(state, payload) {
            state.tipoUsuario = payload
        }
    }
}