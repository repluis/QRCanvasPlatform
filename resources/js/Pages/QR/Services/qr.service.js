import axios from 'axios'

const BASE_QR_URL = 'https://quickchart.io/qr'

export function getQRUrl(text, size = 200) {
    const params = new URLSearchParams({ text, size: String(size) })
    return `${BASE_QR_URL}?${params.toString()}`
}

export async function generateQR(text, size = 200) {
    const response = await axios.post('/qr/generate', { text, size })
    return response.data
}
