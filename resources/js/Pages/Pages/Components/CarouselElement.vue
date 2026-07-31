<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    element: { type: Object, required: true },
    readonly: { type: Boolean, default: false },
})

const idx = ref(0)

const total = computed(() => props.element.images?.length ?? 0)
const current = computed(() => {
    if (total.value === 0) return null
    return props.element.images[idx.value % total.value]
})

function next() {
    if (total.value === 0) return
    idx.value = (idx.value + 1) % total.value
}

function prev() {
    if (total.value === 0) return
    idx.value = (idx.value - 1 + total.value) % total.value
}
</script>

<template>
    <div class="relative h-full w-full overflow-hidden rounded bg-gray-100">
        <img
            v-if="current"
            :src="current.url"
            class="h-full w-full object-cover"
            draggable="false"
            :class="{ 'pointer-events-none': readonly }"
            alt=""
        />
        <div
            v-else
            class="flex h-full w-full items-center justify-center text-gray-400 text-xs"
        >
            Sin imágenes
        </div>

        <button
            v-if="!readonly && total > 1"
            type="button"
            class="absolute left-1 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-1.5 text-white shadow transition hover:bg-black/70"
            @mousedown.stop
            @click.stop="prev"
            title="Anterior"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            v-if="!readonly && total > 1"
            type="button"
            class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-1.5 text-white shadow transition hover:bg-black/70"
            @mousedown.stop
            @click.stop="next"
            title="Siguiente"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div
            v-if="total > 1"
            class="absolute bottom-1 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-2 py-0.5 text-xs text-white"
        >
            {{ idx + 1 }} / {{ total }}
        </div>
    </div>
</template>
