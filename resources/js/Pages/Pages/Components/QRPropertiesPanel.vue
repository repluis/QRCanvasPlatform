<script setup>
const props = defineProps({
    element: { type: Object, default: null },
})

const emit = defineEmits(['update'])

function getQrImageUrl(text, fg = '000000', bg = 'ffffff', size = 200) {
    return `https://quickchart.io/qr?text=${encodeURIComponent(text)}&size=${size}&margin=2&dark=${fg.replace('#', '')}&light=${bg.replace('#', '')}`
}

function emitChange(field, value) {
    if (!props.element) return
    emit('update', props.element.id, { [field]: value })
}

function regenerate() {
    if (!props.element?.content) return
    const imageUrl = getQrImageUrl(
        props.element.content,
        props.element.foregroundColor || '#000000',
        props.element.backgroundColor || '#ffffff',
        Math.max(props.element.width, props.element.height)
    )
    emit('update', props.element.id, { qrImageUrl: imageUrl })
}
</script>

<template>
    <div v-if="element?.type === 'qr'" class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            QR Properties
        </h3>

        <div class="space-y-3">
            <p class="text-xs text-gray-400">Link: <code class="text-indigo-600">{{ element.content }}</code></p>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Foreground Color</label>
                <input
                    :value="element.foregroundColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    @input="emitChange('foregroundColor', $event.target.value); regenerate()"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Background Color</label>
                <input
                    :value="element.backgroundColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    @input="emitChange('backgroundColor', $event.target.value); regenerate()"
                />
            </div>

            <div v-if="element.qrImageUrl" class="mt-2 rounded-lg border p-2">
                <img :src="element.qrImageUrl" class="mx-auto h-24 w-24 object-contain" alt="QR preview" />
            </div>
        </div>
    </div>
</template>
