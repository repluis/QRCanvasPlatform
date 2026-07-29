<script setup>
import { ref } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { useCanvas } from '../Composables/useCanvas'
import EditorToolbar from '../Components/EditorToolbar.vue'
import EditorCanvas from '../Components/EditorCanvas.vue'
import ImageLibrary from '../Components/ImageLibrary.vue'
import TextPropertiesPanel from '../Components/TextPropertiesPanel.vue'
import { savePage } from '../Services/canvas.service'

const { images, savedSlug } = usePage().props
const saving = ref(false)
const saved = ref(false)
const currentSlug = ref(savedSlug ?? '')

const {
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
} = useCanvas()

function onMove(id, x, y) {
    updateElement(id, { x, y })
}

function onDeselect() {
    select(null)
}

async function handleSave() {
    saving.value = true
    saved.value = false
    try {
        const slug = currentSlug.value || 'page-' + Date.now()
        await savePage({ title: 'Mi página', elements: elements.value, slug })
        currentSlug.value = slug
        saved.value = true
        setTimeout(() => (saved.value = false), 3000)
    } catch (e) {
        alert('Error al guardar')
    } finally {
        saving.value = false
    }
}

function viewPage() {
    if (currentSlug.value) {
        window.open(`/p/${currentSlug.value}`, '_blank')
    }
}
</script>

<template>
    <Head title="Editor de Páginas" />

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
                    v-if="currentSlug"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 active:scale-95"
                    @click="viewPage"
                >
                    Vista previa
                </button>
                <button
                    :disabled="saving"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 active:scale-95 disabled:opacity-50"
                    @click="handleSave"
                >
                    <span v-if="saving">Guardando...</span>
                    <span v-else-if="saved">✓ Guardado</span>
                    <span v-else>Guardar</span>
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
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
