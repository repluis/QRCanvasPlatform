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
const saving = ref(false)
const saved = ref(false)
const currentUuid = ref(page?.uuid ?? '')
const currentPageId = ref(page?.id ?? null)
const showPagesList = ref(true)

const   {
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
} = useCanvas(page?.elements)

function onMove(id, x, y) {
    updateElement(id, { x, y })
}

function onDeselect() {
    select(null)
}

function loadPage(page) {
    currentUuid.value = page.uuid
    currentPageId.value = page.id
    elements.value = page.elements || []
}

function newPage() {
    currentUuid.value = ''
    currentPageId.value = null
    elements.value = []
}

async function handleSave() {
    saving.value = true
    saved.value = false
    try {
        const slug = currentUuid.value || 'page-' + Date.now()
        const data = { title: 'My page', elements: elements.value, slug }
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
                @add-text="addText"
                @add-image="addImage"
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

            <EditorCanvas
                :elements="elements"
                :selected-id="selectedId"
                @select="select"
                @move="onMove"
                @remove="removeSelected"
                @deselect="onDeselect"
            />

            <ImageLibrary
                :images="images"
                @add-image-to-canvas="addImage"
            />

            <TextPropertiesPanel
                v-if="selectedElement?.type === 'text'"
                :element="selectedElement"
                @update="(id, props) => updateElement(id, props)"
            />
        </div>
    </div>
</template>
