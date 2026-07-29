<script setup>
import { ref } from 'vue'

const emit = defineEmits(['addText', 'addImage', 'addShape', 'remove', 'bringForward', 'sendBackward'])

defineProps({
    hasSelection: { type: Boolean, default: false },
    selectedType: { type: String, default: null },
    shapes: { type: Array, default: () => [] },
})

const showShapes = ref(false)

function toggleShapes() {
    showShapes.value = !showShapes.value
}

function pickShape(shape) {
    emit('addShape', shape)
    showShapes.value = false
}
</script>

<template>
    <div class="flex flex-wrap items-center gap-2 border-b bg-white px-4 py-3 shadow-sm">
        <button
            class="flex items-center gap-1.5 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-600 active:scale-95"
            @click="$emit('addText')"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Text
        </button>

        <button
            class="flex items-center gap-1.5 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 active:scale-95"
            @click="$emit('addImage')"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Image
        </button>

        <div class="relative">
            <button
                class="flex items-center gap-1.5 rounded-lg bg-pink-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-pink-600 active:scale-95"
                @click="toggleShapes"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                Shape
            </button>

            <div
                v-if="showShapes"
                class="absolute left-0 top-full z-50 mt-1 grid grid-cols-4 gap-1 rounded-xl border bg-white p-2 shadow-xl"
                :style="{ borderColor: 'var(--border)' }"
            >
                <button
                    v-for="s in shapes"
                    :key="s"
                    class="flex flex-col items-center gap-1 rounded-lg px-3 py-2 text-xs text-gray-600 transition hover:bg-pink-50 hover:text-pink-600"
                    @click="pickShape(s)"
                >
                    <svg v-if="s === 'heart'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    <svg v-else-if="s === 'star'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <svg v-else-if="s === 'circle'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <svg v-else-if="s === 'moon'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.1z" />
                    </svg>
                    <svg v-else-if="s === 'diamond'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 12l10 10 10-10L12 2z" />
                    </svg>
                    <svg v-else-if="s === 'triangle'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 22h20L12 2z" />
                    </svg>
                    <svg v-else-if="s === 'hexagon'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z" />
                    </svg>
                    <svg v-else-if="s === 'cloud'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.35 10.04A7.49 7.49 0 0012 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 000 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" />
                    </svg>
                    <span class="capitalize">{{ s }}</span>
                </button>
            </div>
        </div>

        <div class="mx-2 h-6 w-px bg-gray-300"></div>

        <button
            v-if="hasSelection"
            class="flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-amber-600 active:scale-95"
            @click="$emit('bringForward')"
            title="Bring to front"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>

        <button
            v-if="hasSelection"
            class="flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-amber-600 active:scale-95"
            @click="$emit('sendBackward')"
            title="Send to back"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div v-if="hasSelection" class="mx-2 h-6 w-px bg-gray-300"></div>

        <button
            v-if="hasSelection"
            class="flex items-center gap-1.5 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-600 active:scale-95"
            @click="$emit('remove')"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete
        </button>
    </div>
</template>
