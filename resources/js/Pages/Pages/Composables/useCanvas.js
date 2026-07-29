import { ref, computed } from 'vue'

let nextId = 1

function syncNextId(elements) {
    const max = elements.reduce((m, e) => Math.max(m, Number(e.id) || 0), 0)
    if (max >= nextId) nextId = max + 1
}

const SHAPES = ['heart', 'star', 'circle', 'moon', 'diamond', 'triangle', 'hexagon', 'cloud']

function createCanvas() {
    return { elements: [], background: '#ffffff' }
}

export function useCanvas(initialCanvases) {
    const canvases = ref(initialCanvases && initialCanvases.length
        ? initialCanvases.map(c => ({ elements: [...c.elements], background: c.background }))
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

    function addCanvas() {
        canvases.value.push(createCanvas())
        activeIndex.value = canvases.value.length - 1
        selectedId.value = null
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
    }
}
