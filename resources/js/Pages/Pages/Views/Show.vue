<script setup>
import { computed, defineOptions, onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'

defineOptions({ layout: null })

const { page } = usePage().props

const viewportWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1280)

function updateViewport() {
    viewportWidth.value = window.innerWidth
}

// Keep the printed title clean — browsers print document.title in the header.
// Strip the autoprint/card query params from what the browser sees.
function cleanTitle() {
    if (typeof document === 'undefined') return
    document.title = page.title || 'Untitled'
}

watchEffect(cleanTitle)
onMounted(cleanTitle)

onMounted(() => {
    window.addEventListener('resize', updateViewport)
    const params = new URLSearchParams(window.location.search)
    if (params.get('autoprint') === '1') {
        setTimeout(() => window.print(), 500)
    }
})

onUnmounted(() => {
    window.removeEventListener('resize', updateViewport)
})

function canvasScale(canvas) {
    const w = canvas.width || 800
    const maxWidth = Math.min(viewportWidth.value - 32, w)
    return Math.max(0.4, maxWidth / w)
}

const canvases = computed(() => {
    if (page.canvases?.length) return page.canvases
    return [{ elements: page.elements ?? [], background: page.background ?? '#ffffff', width: 800, height: 600, visible: true }]
})

const visibleCanvases = computed(() => {
    const params = new URLSearchParams(window.location.search)
    const cardIdx = params.get('card')
    if (cardIdx !== null) {
        const c = canvases.value[Number(cardIdx)]
        return c ? [c] : []
    }
    return canvases.value.filter(c => c.visible !== false)
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
    'arrow-right': 'M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z',
    'arrow-left': 'M12 20l1.41-1.41L7.83 13H20v-2H7.83l5.58-5.59L12 4l-8 8z',
    'arrow-up': 'M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8z',
    'arrow-down': 'M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.59-5.58L4 12l8 8z',
    cross: 'M10 2h4v8h8v4h-8v8h-4v-8H2v-4h8z',
    plus: 'M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z',
    check: 'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z',
    lightning: 'M13 2L3 14h7l-1 8 10-12h-7z',
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
        fontStyle: el.fontStyle,
        textDecoration: el.textDecoration,
        textAlign: el.textAlign,
        fontFamily: el.fontFamily,
        color: el.color || '#1f2937',
    }
}
</script>

<template>
    <Head :title="page.title" />

    <div class="flex min-h-screen flex-col items-center gap-6 overflow-x-hidden bg-[var(--bg)] p-2 pt-8 sm:p-4 print:bg-white print:gap-4 print:p-2" :style="{ paddingTop: '2rem' }">


        <div
            v-for="(canvas, ci) in visibleCanvases"
            :key="ci"
            class="relative shrink-0 print:break-after-page"
            :style="{
                width: ((canvas.width || 800) * canvasScale(canvas)) + 'px',
                height: ((canvas.height || 600) * canvasScale(canvas)) + 'px',
                maxWidth: '100%',
            }"
        >
            <div
                class="relative shadow-lg overflow-hidden print:shadow-none"
                :style="{
                    width: (canvas.width || 800) + 'px',
                    height: (canvas.height || 600) + 'px',
                    background: canvas.background || '#ffffff',
                    borderRadius: '8px',
                    transform: `scale(${canvasScale(canvas)})`,
                    transformOrigin: 'top left',
                }"
            >
                <div class="relative h-full w-full">
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
                        <img
                            v-else-if="el.type === 'qr' && el.qrImageUrl"
                            :src="el.qrImageUrl"
                            class="h-full w-full rounded object-contain"
                            :style="{ border: `4px solid ${el.foregroundColor || '#000000'}` }"
                            draggable="false"
                        />
                        <div
                            v-else-if="el.type === 'animation'"
                            class="pointer-events-none flex h-full w-full items-center justify-center"
                            :style="{
                                fontSize: `${el.fontSize}px`,
                                color: el.color,
                                animation: `${el.animation} 2.4s ease-in-out infinite`,
                                lineHeight: 1,
                            }"
                        >{{ el.content }}</div>
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

        <p v-if="visibleCanvases.length === 0" class="text-gray-400">No hay tarjetas visibles</p>
    </div>
</template>

<style>
/* Force background colors and images to actually print.
   Browsers strip these by default to save ink; users have to enable
   "Print backgrounds" in their browser settings. These hints tell the
   browser the user explicitly wants backgrounds. */
@media print {
    html, body, * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}

/* Default page setup — smaller margins so cards have room. */
@page {
    margin: 8mm;
    size: auto;
}
</style>
