<script setup>
import CanvasElement from './CanvasElement.vue'

const props = defineProps({
    elements: { type: Array, required: true },
    selectedId: { type: [String, null], default: null },
    background: { type: String, default: '#ffffff' },
    canvasWidth: { type: Number, default: 800 },
    canvasHeight: { type: Number, default: 600 },
})

const emit = defineEmits(['select', 'move', 'remove', 'update', 'deselect'])

function onCanvasClick(e) {
    if (e.target === e.currentTarget) {
        emit('deselect')
    }
}

function onMove(id, x, y) {
    emit('move', id, x, y)
}
</script>

<template>
    <div
        class="relative flex-1 overflow-hidden bg-gray-100"
        @mousedown="onCanvasClick"
    >
        <div
            class="relative mx-auto my-8 shadow-lg"
            :style="{
                background: background,
                width: canvasWidth + 'px',
                height: canvasHeight + 'px',
            }"
        >
            <CanvasElement
                v-for="el in elements"
                :key="el.id"
                :element="el"
                :is-selected="el.id === selectedId"
                @select="emit('select', $event)"
                @move="onMove"
                @remove="emit('remove', $event)"
            />

            <div
                v-if="elements.length === 0"
                class="pointer-events-none absolute inset-0 flex items-center justify-center text-gray-400"
            >
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <p class="text-sm">Add text or images from the toolbar</p>
                </div>
            </div>
        </div>
    </div>
</template>
