import axios from 'axios'

export async function getEditorData() {
    const response = await axios.get('/editor')
    return response.data
}

export async function savePage(data) {
    const response = await axios.post('/pages', data)
    return response.data
}
