import { ref, computed } from 'vue'

let nextId = 1

function syncNextId(elements) {
    const max = elements.reduce((m, e) => Math.max(m, Number(e.id) || 0), 0)
    if (max >= nextId) nextId = max + 1
}

const SHAPES = ['heart', 'star', 'circle', 'moon', 'diamond', 'triangle', 'hexagon', 'cloud', 'arrow-right', 'arrow-left', 'arrow-up', 'arrow-down', 'cross', 'plus', 'check', 'lightning']

function createCanvas(width = 800, height = 600) {
    return { elements: [], background: '#ffffff', width, height, visible: true }
}

export function useCanvas(initialCanvases) {
    const canvases = ref(initialCanvases && initialCanvases.length
        ? initialCanvases.map(c => ({
            elements: [...c.elements],
            background: c.background,
            width: c.width || 800,
            height: c.height || 600,
            visible: c.visible !== false,
        }))
        : [createCanvas()]
    )

    if (canvases.value[0]?.elements?.length) syncNextId(canvases.value[0].elements)

    const activeIndex = ref(0)

    const activeCanvas = computed(() => canvases.value[activeIndex.value])

    const elements = computed(() => activeCanvas.value?.elements ?? [])
    const background = computed(() => activeCanvas.value?.background ?? '#ffffff')

    const selectedId = ref(null)

    const selectedElement = computed(() =>
        elements.value.find((el) => el.id === selectedId.value) ?? null
    )

    function addText(content) {
        const id = String(nextId++)
        canvases.value[activeIndex.value].elements.push({
            id,
            type: 'text',
            x: 50 + (elements.value.length * 20) % 300,
            y: 50 + (elements.value.length * 20) % 300,
            width: 200,
            height: 40,
            content: content || 'Texto',
            fontSize: 20,
            fontWeight: 'normal',
            fontStyle: 'normal',
            textDecoration: 'none',
            textAlign: 'center',
            fontFamily: 'sans-serif',
            color: '#1f2937',
        })
        selectedId.value = id
    }

    function addImage(url) {
        const id = String(nextId++)
        canvases.value[activeIndex.value].elements.push({
            id,
            type: 'image',
            x: 50 + (elements.value.length * 30) % 300,
            y: 50 + (elements.value.length * 30) % 300,
            width: 150,
            height: 150,
            content: url,
        })
        selectedId.value = id
    }

    function addCarousel(urls) {
        if (!urls || urls.length === 0) return
        const id = String(nextId++)
        canvases.value[activeIndex.value].elements.push({
            id,
            type: 'carousel',
            x: 50 + (elements.value.length * 30) % 300,
            y: 50 + (elements.value.length * 30) % 300,
            width: 300,
            height: 220,
            images: urls.map((url, i) => ({ id: `img-${i}`, url })),
        })
        selectedId.value = id
    }

    function addShape(shape) {
        const id = String(nextId++)
        canvases.value[activeIndex.value].elements.push({
            id,
            type: 'shape',
            shape,
            x: 50 + (elements.value.length * 30) % 300,
            y: 50 + (elements.value.length * 30) % 300,
            width: 120,
            height: 120,
            color: '#ef4444',
        })
        selectedId.value = id
    }

    function addQR(qrData) {
        console.log('[addQR] agregando elemento QR:', qrData)
        const id = String(nextId++)
        const el = {
            id,
            type: 'qr',
            x: 50 + (elements.value.length * 30) % 300,
            y: 50 + (elements.value.length * 30) % 300,
            width: 150,
            height: 150,
            content: qrData.text || '',
            qrImageUrl: qrData.image_url || '',
            foregroundColor: qrData.foreground_color || '#000000',
            backgroundColor: qrData.background_color || '#ffffff',
            errorCorrectionLevel: qrData.error_correction_level || 'medium',
        }
        console.log('[addQR] elemento a insertar:', el)
        console.log('[addQR] canvas actual:', activeIndex.value, 'elements antes:', elements.value.length)
        canvases.value[0].elements.push(el)
        selectedId.value = id
        console.log('[addQR] elements después:', elements.value.length, 'selectedId:', id)
    }

    function addAnimation(animationData) {
        const id = String(nextId++)
        const el = {
            id,
            type: 'animation',
            animation: animationData.id || 'float-heart',
            content: animationData.emoji || '💖',
            x: 50 + (elements.value.length * 30) % 300,
            y: 50 + (elements.value.length * 30) % 300,
            width: animationData.fontSize || 56,
            height: animationData.fontSize || 56,
            color: animationData.color || '#ef4444',
            fontSize: animationData.fontSize || 56,
        }
        canvases.value[activeIndex.value].elements.push(el)
        selectedId.value = id
    }

    function removeSelected() {
        if (!selectedId.value) return
        canvases.value[activeIndex.value].elements = elements.value.filter((el) => el.id !== selectedId.value)
        selectedId.value = null
    }

    function select(id) {
        selectedId.value = id
    }

    function updateElement(id, props) {
        const el = elements.value.find((e) => e.id === id)
        if (el) Object.assign(el, props)
    }

    function moveElement(id, dx, dy) {
        const el = elements.value.find((e) => e.id === id)
        if (el) {
            el.x += dx
            el.y += dy
        }
    }

    function bringForward(id) {
        const arr = canvases.value[activeIndex.value].elements
        const idx = arr.findIndex((e) => e.id === id)
        if (idx < arr.length - 1) {
            const el = arr.splice(idx, 1)[0]
            arr.splice(idx + 1, 0, el)
        }
    }

    function sendBackward(id) {
        const arr = canvases.value[activeIndex.value].elements
        const idx = arr.findIndex((e) => e.id === id)
        if (idx > 0) {
            const el = arr.splice(idx, 1)[0]
            arr.splice(idx - 1, 0, el)
        }
    }

    function clearCanvas() {
        canvases.value[activeIndex.value].elements = []
        selectedId.value = null
    }

    function setBackground(value) {
        canvases.value[activeIndex.value].background = value
    }

    function addCanvas(width = 800, height = 600) {
        canvases.value.push(createCanvas(width, height))
        activeIndex.value = canvases.value.length - 1
        selectedId.value = null
    }

    function toggleCanvasVisibility(index) {
        if (canvases.value[index]) {
            canvases.value[index].visible = !canvases.value[index].visible
        }
    }

    function removeCanvas(index) {
        if (canvases.value.length <= 1) return
        canvases.value.splice(index, 1)
        if (activeIndex.value >= canvases.value.length) {
            activeIndex.value = canvases.value.length - 1
        }
        selectedId.value = null
    }

    function switchCanvas(index) {
        if (index >= 0 && index < canvases.value.length) {
            activeIndex.value = index
            selectedId.value = null
        }
    }

    return {
        canvases,
        activeIndex,
        elements,
        background,
        selectedId,
        selectedElement,
        SHAPES,
        addText,
        addImage,
        addShape,
        addCarousel,
        addQR,
        addAnimation,
        removeSelected,
        select,
        updateElement,
        moveElement,
        bringForward,
        sendBackward,
        clearCanvas,
        setBackground,
        addCanvas,
        removeCanvas,
        switchCanvas,
        toggleCanvasVisibility,
    }
}
