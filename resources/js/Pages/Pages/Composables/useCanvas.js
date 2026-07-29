import { ref, computed } from 'vue'

let nextId = 1

function syncNextId(elements) {
    const max = elements.reduce((m, e) => Math.max(m, Number(e.id) || 0), 0)
    if (max >= nextId) nextId = max + 1
}

export function useCanvas(initialElements) {
    const elements = ref(initialElements ?? [])
    if (initialElements?.length) syncNextId(initialElements)
    const selectedId = ref(null)

    const selectedElement = computed(() =>
        elements.value.find((el) => el.id === selectedId.value) ?? null
    )

    function addText() {
        const id = String(nextId++)
        elements.value.push({
            id,
            type: 'text',
            x: 50 + (elements.value.length * 20) % 300,
            y: 50 + (elements.value.length * 20) % 300,
            width: 200,
            height: 40,
            content: 'Texto',
            fontSize: 20,
            fontWeight: 'normal',
            color: '#1f2937',
        })
        selectedId.value = id
    }

    function addImage(url) {
        const id = String(nextId++)
        elements.value.push({
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

    function removeSelected() {
        if (!selectedId.value) return
        elements.value = elements.value.filter((el) => el.id !== selectedId.value)
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
        const idx = elements.value.findIndex((e) => e.id === id)
        if (idx < elements.value.length - 1) {
            const el = elements.value.splice(idx, 1)[0]
            elements.value.splice(idx + 1, 0, el)
        }
    }

    function sendBackward(id) {
        const idx = elements.value.findIndex((e) => e.id === id)
        if (idx > 0) {
            const el = elements.value.splice(idx, 1)[0]
            elements.value.splice(idx - 1, 0, el)
        }
    }

    function clearCanvas() {
        elements.value = []
        selectedId.value = null
    }

    return {
        elements,
        selectedId,
        selectedElement,
        addText,
        addImage,
        removeSelected,
        select,
        updateElement,
        moveElement,
        bringForward,
        sendBackward,
        clearCanvas,
    }
}
