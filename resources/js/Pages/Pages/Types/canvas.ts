export interface CanvasElement {
    id: string
    type: 'text' | 'image'
    x: number
    y: number
    width: number
    height: number
    content: string
    fontSize?: number
    fontWeight?: 'normal' | 'bold'
    color?: string
}

export interface EditorImage {
    id: number
    url: string
    label: string
}
