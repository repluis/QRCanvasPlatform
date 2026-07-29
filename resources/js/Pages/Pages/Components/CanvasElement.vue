<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    element: { type: Object, required: true },
    isSelected: { type: Boolean, default: false },
})

const emit = defineEmits(['select', 'move', 'update'])

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
        <div
            v-else
            class="flex h-full w-full items-center justify-center rounded px-2 pointer-events-none"
            :style="{
                fontSize: `${element.fontSize}px`,
                fontWeight: element.fontWeight,
                color: element.color,
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
