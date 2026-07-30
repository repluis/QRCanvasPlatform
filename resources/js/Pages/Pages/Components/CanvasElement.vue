<script setup>
import { ref, computed } from 'vue'
import QRElement from './QRElement.vue'

const props = defineProps({
    element: { type: Object, required: true },
    isSelected: { type: Boolean, default: false },
})

const emit = defineEmits(['select', 'move', 'update', 'remove'])

const dragging = ref(false)
const dragStart = ref({ x: 0, y: 0 })
const elStart = ref({ x: 0, y: 0 })

const style = computed(() => ({
    left: `${props.element.x}px`,
    top: `${props.element.y}px`,
    width: `${props.element.width}px`,
    height: `${props.element.height}px`,
    zIndex: props.isSelected ? 1000 : 'auto',
    cursor: dragging.value ? 'grabbing' : 'grab',
}))

const shapeViewBox = computed(() => {
    const s = props.element.shape
    if (s === 'heart') return '0 0 24 24'
    if (s === 'star') return '0 0 24 24'
    if (s === 'circle') return '0 0 24 24'
    if (s === 'moon') return '0 0 24 24'
    if (s === 'diamond') return '0 0 24 24'
    if (s === 'triangle') return '0 0 24 24'
    if (s === 'hexagon') return '0 0 24 24'
    if (s === 'cloud') return '0 0 24 24'
    return '0 0 24 24'
})

const shapePath = computed(() => {
    const s = props.element.shape
    if (s === 'heart') return 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'
    if (s === 'star') return 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z'
    if (s === 'circle') return 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z'
    if (s === 'moon') return 'M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.1z'
    if (s === 'diamond') return 'M12 2L2 12l10 10 10-10L12 2z'
    if (s === 'triangle') return 'M12 2L2 22h20L12 2z'
    if (s === 'hexagon') return 'M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z'
    if (s === 'cloud') return 'M19.35 10.04A7.49 7.49 0 0012 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 000 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z'
    if (s === 'arrow-right') return 'M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z'
    if (s === 'arrow-left') return 'M12 20l1.41-1.41L7.83 13H20v-2H7.83l5.58-5.59L12 4l-8 8z'
    if (s === 'arrow-up') return 'M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8z'
    if (s === 'arrow-down') return 'M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.59-5.58L4 12l8 8z'
    if (s === 'cross') return 'M10 2h4v8h8v4h-8v8h-4v-8H2v-4h8z'
    if (s === 'plus') return 'M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'
    if (s === 'check') return 'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'
    if (s === 'lightning') return 'M13 2L3 14h7l-1 8 10-12h-7z'
    return ''
})

function onMouseDown(e) {
    e.stopPropagation()
    emit('select', props.element.id)
    dragging.value = true
    dragStart.value = { x: e.clientX, y: e.clientY }
    elStart.value = { x: props.element.x, y: props.element.y }

    function onMouseMove(ev) {
        const dx = ev.clientX - dragStart.value.x
        const dy = ev.clientY - dragStart.value.y
        emit('move', props.element.id, dx + elStart.value.x, dy + elStart.value.y)
    }

    function onMouseUp() {
        dragging.value = false
        window.removeEventListener('mousemove', onMouseMove)
        window.removeEventListener('mouseup', onMouseUp)
    }

    window.addEventListener('mousemove', onMouseMove)
    window.addEventListener('mouseup', onMouseUp)
}
</script>

<template>
    <div
        class="absolute cursor-grab select-none rounded"
        :class="{
            'ring-2 ring-indigo-500 ring-offset-2': isSelected,
            'hover:ring-1 hover:ring-indigo-300': !isSelected,
        }"
        :style="style"
        @mousedown="onMouseDown"
    >
        <img
            v-if="element.type === 'image'"
            :src="element.content"
            class="h-full w-full rounded object-cover pointer-events-none"
            draggable="false"
        />
        <svg
            v-else-if="element.type === 'shape'"
            class="h-full w-full pointer-events-none"
            :viewBox="shapeViewBox"
            :style="{ color: element.color }"
            fill="currentColor"
        >
            <path :d="shapePath" />
        </svg>
        <QRElement
            v-else-if="element.type === 'qr'"
            :element="element"
        />
        <div
            v-else
            class="flex h-full w-full items-center rounded px-2 pointer-events-none"
            :style="{
                fontSize: `${element.fontSize}px`,
                fontWeight: element.fontWeight,
                fontStyle: element.fontStyle,
                textDecoration: element.textDecoration,
                textAlign: element.textAlign,
                fontFamily: element.fontFamily,
                color: element.color || '#1f2937',
            }"
        >
            {{ element.content }}
        </div>

        <button
            v-if="isSelected"
            class="absolute -right-2 -top-2 z-50 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white shadow hover:bg-red-600 pointer-events-auto"
            @mousedown.stop
            @click.stop="$emit('remove', element.id)"
        >
            ×
        </button>
    </div>
</template>
