export interface QRData {
    image_url: string
    text: string
}

export interface GenerateQRPayload {
    text: string
    size?: number
}
