import axios from 'axios'

export async function getEditorData() {
    const response = await axios.get('/canvas')
    return response.data
}

export async function savePage(data) {
    console.log('[canvas.service] savePage:', data)
    try {
        const response = await axios.post('/pages', data)
        console.log('[canvas.service] savePage OK:', response.data)
        return response.data
    } catch (e) {
        console.error('[canvas.service] savePage ERROR:', {
            status: e?.response?.status,
            data: e?.response?.data,
            message: e?.message,
        })
        throw e
    }
}

export async function generateQR(params) {
    console.log('[canvas.service] generateQR:', params)
    try {
        const response = await axios.post('/qr/generate', params)
        console.log('[canvas.service] generateQR OK:', response.data)
        return response.data
    } catch (e) {
        console.error('[canvas.service] generateQR ERROR:', {
            status: e?.response?.status,
            statusText: e?.response?.statusText,
            data: e?.response?.data,
            message: e?.message,
        })
        throw e
    }
}
