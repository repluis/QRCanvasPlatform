<script setup>
import { ref } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { useCanvas } from '../Composables/useCanvas'

defineOptions({ layout: null })
import EditorToolbar from '../Components/EditorToolbar.vue'
import EditorCanvas from '../Components/EditorCanvas.vue'
import ImageLibrary from '../Components/ImageLibrary.vue'
import TextPropertiesPanel from '../Components/TextPropertiesPanel.vue'
import QRPropertiesPanel from '../Components/QRPropertiesPanel.vue'
import { savePage } from '../Services/canvas.service'

const { images, userPages, page } = usePage().props

const LOVE_PHRASES = [
    'Te amo',
    'Eres mi todo',
    'Mi amor eterno',
    'Siempre juntos',
    'Corazón mío',
    'Eres mi vida',
    'Te quiero',
    'Para siempre',
    'Mi media naranja',
    'Amor infinito',
    'Eres única',
    'Contigo siempre',
    'Mi razón de ser',
    'Te adoro',
    'Eres mi sol',
]

const fileInput = ref(null)
const saving = ref(false)
const saved = ref(false)
const currentUuid = ref(page?.uuid ?? '')
const currentPageId = ref(page?.id ?? null)
const showPagesList = ref(true)
const showPagesDropdown = ref(false)

const origin = typeof window !== 'undefined' ? window.location.origin : ''

function isDarkBg(bg) {
    if (!bg || bg === '#ffffff') return false
    const hex = bg.replace('#', '')
    if (hex.length < 6) return false
    const r = parseInt(hex.substring(0, 2), 16)
    const g = parseInt(hex.substring(2, 4), 16)
    const b = parseInt(hex.substring(4, 6), 16)
    return r * 0.299 + g * 0.587 + b * 0.114 < 128
}

const {
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
} = useCanvas(page?.canvases)

function onMove(id, x, y) {
    updateElement(id, { x, y })
}

function onDeselect() {
    select(null)
}

function handlePaste(e) {
    const items = e.clipboardData?.items
    if (!items) return
    for (const item of items) {
        if (item.type.startsWith('image/')) {
            const blob = item.getAsFile()
            if (blob) {
                const reader = new FileReader()
                reader.onload = (ev) => addImage(ev.target.result)
                reader.readAsDataURL(blob)
            }
            break
        }
    }
}

function handleImageUpload(e) {
    const file = e.target.files?.[0]
    if (!file) return
    if (!file.type.startsWith('image/')) return
    const reader = new FileReader()
    reader.onload = (ev) => {
        addImage(ev.target.result)
        e.target.value = ''
    }
    reader.readAsDataURL(file)
}

function loadPage(page) {
    router.visit('/editor', {
        method: 'get',
        data: { uuid: page.uuid },
        preserveScroll: true,
    })
}

function newPage() {
    currentUuid.value = ''
    currentPageId.value = null
    canvases.value = [{ elements: [], background: '#ffffff', width: 800, height: 600, visible: true }]
    activeIndex.value = 0
}

const showCreateDialog = ref(false)
const newCanvasWidth = ref(800)
const newCanvasHeight = ref(600)
const createAsFirst = ref(true)

function openCreateDialog() {
    newCanvasWidth.value = 800
    newCanvasHeight.value = 600
    createAsFirst.value = true
    showCreateDialog.value = true
}

function confirmCreateCanvas() {
    const newCanvas = { elements: [], background: '#ffffff', width: newCanvasWidth.value, height: newCanvasHeight.value, visible: true }
    if (createAsFirst.value) {
        canvases.value.unshift(newCanvas)
        activeIndex.value = 0
    } else {
        canvases.value.push(newCanvas)
        activeIndex.value = canvases.value.length - 1
    }
    selectedId.value = null
    showCreateDialog.value = false
}

async function handleSave() {
    saving.value = true
    saved.value = false
    try {
        const slug = currentUuid.value || 'page-' + Date.now()
        const data = {
            title: 'My page',
            canvases: canvases.value.map(c => ({
                elements: c.elements,
                background: c.background,
                width: c.width,
                height: c.height,
                visible: c.visible,
            })),
            slug,
        }
        if (currentPageId.value) {
            data.id = currentPageId.value
        }
        const res = await savePage(data)
        currentUuid.value = res.page.uuid
        saved.value = true
        router.reload({ only: ['userPages'] })
        setTimeout(() => (saved.value = false), 3000)
    } catch (e) {
        console.error('Save error:', e)
        const msg = e?.response?.data?.message || e?.message || 'Error saving page'
        alert(msg)
    } finally {
        saving.value = false
    }
}

function viewPage() {
    if (currentUuid.value) {
        window.open(`/page?uuid=${currentUuid.value}`, '_blank')
    }
}

function printCard(index) {
    if (!currentUuid.value) return
    window.open(`/page?uuid=${currentUuid.value}&autoprint=1&card=${index}`, '_blank')
}

function printAllVisible() {
    if (!currentUuid.value) return
    window.open(`/page?uuid=${currentUuid.value}&autoprint=1`, '_blank')
}

function getQrImageUrl(text, fg = '000000', bg = 'ffffff', size = 200) {
    return `https://quickchart.io/qr?text=${encodeURIComponent(text)}&size=${size}&margin=2&dark=${fg.replace('#', '')}&light=${bg.replace('#', '')}`
}

function handleAddQR(qrConfig) {
    console.log('[handleAddQR] iniciado', qrConfig)
    const imageUrl = getQrImageUrl(qrConfig.text, qrConfig.foreground_color, qrConfig.background_color, 200)
    console.log('[handleAddQR] image URL:', imageUrl)
    addQR({
        text: qrConfig.text,
        image_url: imageUrl,
        foreground_color: qrConfig.foreground_color,
        background_color: qrConfig.background_color,
        error_correction_level: qrConfig.error_correction_level,
    })
    console.log('[handleAddQR] QR agregado al canvas')
}
</script>

<template>
    <Head title="Page Editor" />

    <div class="flex h-screen flex-col bg-gray-50">
        <div class="flex items-center justify-between border-b bg-white px-4">
            <button
                class="mr-2 flex items-center gap-1.5 rounded-lg bg-rose-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-600 active:scale-95"
                @click="openCreateDialog"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Tarjeta
            </button>
            <EditorToolbar
                :has-selection="!!selectedId"
                :selected-type="selectedElement?.type ?? null"
                :shapes="SHAPES"
                @add-text="addText"
                @add-image="addImage"
                @add-shape="addShape"
                @add-qr="handleAddQR({ text: origin + '/page?uuid=' + currentUuid, foreground_color: '#000000', background_color: '#ffffff', error_correction_level: 'medium' })"
                @remove="removeSelected"
                @bring-forward="bringForward(selectedId)"
                @send-backward="sendBackward(selectedId)"
            />
            <div class="flex items-center gap-2">
                <button
                    class="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="fileInput?.click()"
                    title="Upload image"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload
                </button>
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleImageUpload"
                />
                <button
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="showPagesList = !showPagesList"
                    :title="showPagesList ? 'Hide sidebar' : 'Show sidebar'"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <button
                    v-if="currentUuid"
                    class="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="viewPage"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Preview
                </button>
                <button
                    v-if="currentUuid"
                    class="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="printAllVisible"
                    title="Imprimir todas las tarjetas visibles"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir todas
                </button>
                <button
                    :disabled="saving"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 active:scale-95 disabled:opacity-50"
                    @click="handleSave"
                >
                    <span v-if="saving">Saving...</span>
                    <span v-else-if="saved">✓ Saved</span>
                    <span v-else>Save</span>
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <div
                v-if="showPagesList"
                class="flex w-64 flex-col border-r bg-white"
            >
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tarjetas</h3>
                        <div class="relative" v-if="userPages.length">
                            <button
                                class="rounded px-2 py-0.5 text-xs text-indigo-500 transition hover:bg-indigo-50"
                                @click.stop="showPagesDropdown = !showPagesDropdown"
                            >
                                Pages
                            </button>
                            <div
                                v-if="showPagesDropdown"
                                class="absolute left-0 top-full z-50 mt-1 w-48 rounded-lg border bg-white py-1 shadow-xl"
                            >
                                <button
                                    v-for="p in userPages"
                                    :key="p.id"
                                    class="w-full px-3 py-2 text-left text-xs transition hover:bg-indigo-50"
                                    :class="{ 'font-semibold text-indigo-600': p.uuid === currentUuid }"
                                    @click="loadPage(p)"
                                >
                                    <span class="block truncate">{{ p.title }}</span>
                                </button>
                                <hr class="my-1" />
                                <button
                                    class="w-full px-3 py-2 text-left text-xs text-gray-500 transition hover:bg-gray-50"
                                    @click="newPage"
                                >
                                    + New page
                                </button>
                            </div>
                        </div>
                    </div>
                    <button
                        class="rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-rose-600"
                        @click="openCreateDialog"
                    >
                        + Tarjeta
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <div
                        v-for="(c, i) in canvases"
                        :key="i"
                        class="flex w-full cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm transition hover:bg-indigo-50"
                        :class="i === activeIndex ? 'bg-indigo-50 ring-1 ring-indigo-300' : ''"
                        @click="switchCanvas(i)"
                    >
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded border text-xs font-bold"
                            :style="{ borderColor: 'var(--border)', background: c.background || '#fff' }"
                        >
                            <span class="text-gray-500" :style="{ color: isDarkBg(c.background) ? '#fff' : '#6b7280' }">
                                {{ c.elements.length }}
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block truncate font-medium text-gray-800">Tarjeta {{ i + 1 }}</span>
                            <span class="block text-xs text-gray-400">{{ c.width }}x{{ c.height }} · {{ c.elements.length }} elements</span>
                        </div>
                        <button
                            type="button"
                            class="shrink-0 rounded p-1 transition"
                            :class="c.visible ? 'text-gray-300 hover:text-indigo-500' : 'text-gray-200'"
                            @click.stop="toggleCanvasVisibility(i)"
                            :title="c.visible ? 'Hide' : 'Show'"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="c.visible" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path v-if="c.visible" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path v-if="!c.visible" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19 12 19c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.453 10.453 0 01-2.77 4.772M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="shrink-0 rounded p-1 text-gray-300 transition hover:text-indigo-500"
                            @click.stop="printCard(i)"
                            title="Imprimir tarjeta"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        <button
                            v-if="canvases.length > 1"
                            type="button"
                            class="shrink-0 rounded p-1 text-gray-300 transition hover:bg-red-50 hover:text-red-500"
                            @click.stop="removeCanvas(i)"
                            title="Delete canvas"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-1 flex-col overflow-hidden" @paste="handlePaste">
                <EditorCanvas
                    :key="activeIndex"
                    :elements="elements"
                    :selected-id="selectedId"
                    :background="background"
                    :canvas-width="canvases[activeIndex]?.width || 800"
                    :canvas-height="canvases[activeIndex]?.height || 600"
                    @select="select"
                    @move="onMove"
                    @remove="removeSelected"
                    @deselect="onDeselect"
                />
            </div>

            <ImageLibrary
                v-if="!selectedId"
                :images="images"
                :shapes="SHAPES"
                :phrases="LOVE_PHRASES"
                @add-image-to-canvas="addImage"
                @add-shape="addShape"
                @add-text="addText"
                @add-animation="addAnimation"
                @set-background="setBackground"
            />

            <TextPropertiesPanel
                v-if="selectedElement?.type === 'text' || selectedElement?.type === 'shape'"
                :element="selectedElement"
                @update="(id, props) => updateElement(id, props)"
            />

            <QRPropertiesPanel
                v-if="selectedElement?.type === 'qr'"
                :element="selectedElement"
                @update="(id, props) => updateElement(id, props)"
            />
        </div>
    </div>

    <div
        v-if="showCreateDialog"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @click.self="showCreateDialog = false"
    >
        <div class="w-96 rounded-xl bg-white p-6 shadow-2xl">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Crear Nueva Tarjeta</h3>

            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm text-gray-500">Ancho (px)</label>
                    <input
                        v-model.number="newCanvasWidth"
                        type="number"
                        min="100"
                        max="2000"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-gray-500">Alto (px)</label>
                    <input
                        v-model.number="newCanvasHeight"
                        type="number"
                        min="100"
                        max="2000"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input v-model="createAsFirst" type="checkbox" class="rounded" />
                    Colocar en primer lugar
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button
                    class="rounded-lg border px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50"
                    @click="showCreateDialog = false"
                >
                    Cancelar
                </button>
                <button
                    class="rounded-lg bg-rose-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-600"
                    @click="confirmCreateCanvas"
                >
                    Crear
                </button>
            </div>
        </div>
    </div>
</template>
