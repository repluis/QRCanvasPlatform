<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { defineOptions } from 'vue'

defineOptions({ layout: null })

const { page } = usePage().props

const CANVAS_W = 800
const CANVAS_H = 600

const vw = ref(typeof window !== 'undefined' ? window.innerWidth : 1024)
const vh = ref(typeof window !== 'undefined' ? window.innerHeight : 768)

const scale = computed(() => {
    const pad = 32
    const availW = vw.value - pad
    const availH = vh.value - pad
    return Math.min(1, availW / CANVAS_W, availH / CANVAS_H)
})

const containerW = computed(() => CANVAS_W * scale.value)
const containerH = computed(() => CANVAS_H * scale.value)

function onResize() {
    vw.value = window.innerWidth
    vh.value = window.innerHeight
}

onMounted(() => {
    window.addEventListener('resize', onResize)
})

onUnmounted(() => {
    window.removeEventListener('resize', onResize)
})

const canvases = computed(() => {
    if (page.canvases?.length) return page.canvases
    return [{ elements: page.elements ?? [], background: page.background ?? '#ffffff' }]
})

const SHAPE_PATHS = {
    heart: 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z',
    star: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
    circle: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z',
    moon: 'M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.z',
    diamond: 'M12 2L2 12l10 10 10-10L12 2z',
    triangle: 'M12 2L2 22h20L12 2z',
    hexagon: 'M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z',
    cloud: 'M19.35 10.04A7.49 7.49 0 0012 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 000 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z',
}

function elementStyle(el) {
    return {
        position: 'absolute',
        left: `${el.x}px`,
        top: `${el.y}px`,
        width: `${el.width}px`,
        height: `${el.height}px`,
        fontSize: el.fontSize ? `${el.fontSize}px` : undefined,
        fontWeight: el.fontWeight,
        color: el.color,
    }
}
</script>

<template>
    <Head :title="page.title" />

    <div class="flex min-h-screen flex-col items-center gap-8 overflow-hidden p-4" :style="{ backgroundColor: 'var(--bg)', paddingTop: '2rem' }">
        <div
            v-for="(canvas, ci) in canvases"
            :key="ci"
            class="relative shadow-lg overflow-hidden shrink-0"
            :style="{
                width: containerW + 'px',
                height: containerH + 'px',
                background: canvas.background || '#ffffff',
                borderRadius: '8px',
            }"
        >
            <div
                class="absolute origin-top-left"
                :style="{
                    width: CANVAS_W + 'px',
                    height: CANVAS_H + 'px',
                    transform: `scale(${scale})`,
                }"
            >
                <div v-for="el in canvas.elements" :key="el.id" :style="elementStyle(el)" class="select-none">
                    <img
                        v-if="el.type === 'image'"
                        :src="el.content"
                        class="h-full w-full object-cover"
                        draggable="false"
                    />
                    <svg
                        v-else-if="el.type === 'shape'"
                        class="h-full w-full"
                        :style="{ color: el.color }"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path :d="SHAPE_PATHS[el.shape] || ''" />
                    </svg>
                    <div v-else-if="el.type === 'text'">
                        {{ el.content }}
                    </div>
                </div>

                <div
                    v-if="!canvas.elements || canvas.elements.length === 0"
                    class="absolute inset-0 flex items-center justify-center"
                    :style="{ color: 'var(--text-dim)' }"
                >
                    <p>Canvas {{ ci + 1 }} is empty</p>
                </div>
            </div>
        </div>
    </div>
</template>
