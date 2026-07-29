<script setup>
import { ref } from 'vue'

const props = defineProps({
    element: { type: Object, default: null },
})

const emit = defineEmits(['update'])

const generating = ref(false)

const ERROR_LEVELS = [
    { value: 'low', label: 'Low (7%)' },
    { value: 'medium', label: 'Medium (15%)' },
    { value: 'quartile', label: 'Quartile (25%)' },
    { value: 'high', label: 'High (30%)' },
]

function getQrImageUrl(text, fg = '000000', bg = 'ffffff', size = 200) {
    return `https://quickchart.io/qr?text=${encodeURIComponent(text)}&size=${size}&margin=2&dark=${fg.replace('#', '')}&light=${bg.replace('#', '')}`
}

function emitChange(field, value) {
    if (!props.element) return
    emit('update', props.element.id, { [field]: value })
}

function handleGenerate() {
    if (!props.element?.content) {
        console.warn('[QRPropertiesPanel] no content to generate')
        return
    }
    console.log('[QRPropertiesPanel] generate QR', { content: props.element.content })
    const imageUrl = getQrImageUrl(
        props.element.content,
        props.element.foregroundColor || '#000000',
        props.element.backgroundColor || '#ffffff',
        Math.max(props.element.width, props.element.height)
    )
    console.log('[QRPropertiesPanel] QR image URL:', imageUrl)
    emit('update', props.element.id, { qrImageUrl: imageUrl })
    console.log('[QRPropertiesPanel] QR updated')
}
</script>

<template>
    <div v-if="element?.type === 'qr'" class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            QR Properties
        </h3>

        <div class="space-y-3">
            <div>
                <label class="mb-1 block text-xs text-gray-400">Content URL / Text</label>
                <input
                    :value="element.content"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    @input="emitChange('content', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Error Correction</label>
                <select
                    :value="element.errorCorrectionLevel"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    @change="emitChange('errorCorrectionLevel', $event.target.value)"
                >
                    <option v-for="lvl in ERROR_LEVELS" :key="lvl.value" :value="lvl.value">{{ lvl.label }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Foreground Color</label>
                <input
                    :value="element.foregroundColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    @input="emitChange('foregroundColor', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Background Color</label>
                <input
                    :value="element.backgroundColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    @input="emitChange('backgroundColor', $event.target.value)"
                />
            </div>

            <button
                class="w-full rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-600 active:scale-95 disabled:opacity-50"
                :disabled="generating || !element.content"
                @click="handleGenerate"
            >
                {{ generating ? 'Generating...' : 'Generate QR' }}
            </button>

            <div v-if="element.qrImageUrl" class="mt-2 rounded-lg border p-2">
                <img :src="element.qrImageUrl" class="mx-auto h-24 w-24 object-contain" alt="QR preview" />
            </div>
        </div>
    </div>
</template>
