export default {
    state: {
        documentos: [],
        documentosArchivo: [],
        documentosReporte:[],
        // documentosFiltro:[],
    },
    getters: {
        getDocumentos(state) {
            return state.documentos
        },
        getDocumentosArchivo(state) {
            return state.documentosArchivo
        },
        getDocumentosReporte(state) {
            return state.documentosReporte
        },
        // getDocumentosFiltro(state) {
        //     return state.documentosFiltro
        // },
        
    },
    mutations: {
        setDocumentos(state, payload) {
            state.documentos = payload
        },
        setDocumentosArchivo(state, payload) {
            state.documentosArchivo = payload
        },
        setDocumentosReporte(state, payload) {
            state.documentosReporte = payload
        },
        // setDocumentosFiltro(state, payload) {
        //     state.documentosFiltro = payload
        // }
    }
}