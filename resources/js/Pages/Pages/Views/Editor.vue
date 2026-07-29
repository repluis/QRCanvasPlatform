<script setup>
import { ref } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { defineOptions } from 'vue'
import { useCanvas } from '../Composables/useCanvas'

defineOptions({ layout: null })
import EditorToolbar from '../Components/EditorToolbar.vue'
import EditorCanvas from '../Components/EditorCanvas.vue'
import ImageLibrary from '../Components/ImageLibrary.vue'
import TextPropertiesPanel from '../Components/TextPropertiesPanel.vue'
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

const saving = ref(false)
const saved = ref(false)
const currentUuid = ref(page?.uuid ?? '')
const currentPageId = ref(page?.id ?? null)
const showPagesList = ref(true)

const   {
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
} = useCanvas(page?.canvases)

function onMove(id, x, y) {
    updateElement(id, { x, y })
}

function onDeselect() {
    select(null)
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
    canvases.value = [{ elements: [], background: '#ffffff' }]
    activeIndex.value = 0
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
</script>

<template>
    <Head title="Page Editor" />

    <div class="flex h-screen flex-col bg-gray-50">
        <div class="flex items-center justify-between border-b bg-white px-4">
            <EditorToolbar
                :has-selection="!!selectedId"
                :selected-type="selectedElement?.type ?? null"
                :shapes="SHAPES"
                @add-text="addText"
                @add-image="addImage"
                @add-shape="addShape"
                @remove="removeSelected"
                @bring-forward="bringForward(selectedId)"
                @send-backward="sendBackward(selectedId)"
            />
            <div class="flex items-center gap-2">
                <button
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="showPagesList = !showPagesList"
                >
                    {{ showPagesList ? 'Hide' : 'My pages' }}
                </button>
                <button
                    v-if="currentUuid"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="viewPage"
                >
                    Preview
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
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">My pages</h3>
                    <button
                        class="rounded-lg bg-indigo-500 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-indigo-600"
                        @click="newPage"
                    >
                        + New
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <button
                        v-for="p in userPages"
                        :key="p.id"
                        class="w-full rounded-lg px-3 py-2.5 text-left text-sm transition hover:bg-indigo-50"
                        :class="{ 'bg-indigo-50 ring-1 ring-indigo-300': p.uuid === currentUuid }"
                        @click="loadPage(p)"
                    >
                        <span class="block font-medium text-gray-800 truncate">{{ p.title }}</span>
                        <span class="block text-xs text-gray-400 mt-0.5">{{ p.updated_at }}</span>
                    </button>
                    <p v-if="userPages.length === 0" class="px-3 py-6 text-center text-sm text-gray-400">
                        You don't have any pages yet
                    </p>
                </div>
            </div>

            <div class="flex flex-1 flex-col overflow-hidden">
                <div class="flex items-center gap-1 border-b bg-gray-100 px-3 py-1">
                    <button
                        v-for="(c, i) in canvases"
                        :key="i"
                        class="flex items-center gap-1 rounded-t-lg px-4 py-2 text-xs font-medium transition"
                        :class="i === activeIndex
                            ? 'bg-white text-indigo-700 shadow-sm'
                            : 'text-gray-500 hover:bg-gray-200'"
                        @click="switchCanvas(i)"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Canvas {{ i + 1 }}
                    </button>

                    <button
                        class="ml-1 flex h-7 w-7 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-300 hover:text-gray-700"
                        title="Add canvas"
                        @click="addCanvas"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>

                    <div class="ml-auto flex items-center gap-1">
                        <button
                            v-if="canvases.length > 1"
                            class="rounded px-2 py-1 text-xs text-red-400 transition hover:bg-red-50 hover:text-red-600"
                            @click="removeCanvas(activeIndex)"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <EditorCanvas
                    :key="activeIndex"
                    :elements="elements"
                    :selected-id="selectedId"
                    :background="background"
                    @select="select"
                    @move="onMove"
                    @remove="removeSelected"
                    @deselect="onDeselect"
                />
            </div>

            <ImageLibrary
                :images="images"
                :shapes="SHAPES"
                :phrases="LOVE_PHRASES"
                @add-image-to-canvas="addImage"
                @add-shape="addShape"
                @add-text="addText"
                @set-background="setBackground"
            />

            <TextPropertiesPanel
                v-if="selectedElement?.type === 'text' || selectedElement?.type === 'shape'"
                :element="selectedElement"
                @update="(id, props) => updateElement(id, props)"
            />
        </div>
    </div>
</template>
