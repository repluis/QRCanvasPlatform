<script setup>
import { watch } from 'vue'

const props = defineProps({
    element: { type: Object, default: null },
})

const emit = defineEmits(['update'])

function emitChange(field, value) {
    if (!props.element) return
    emit('update', props.element.id, { [field]: value })
}
</script>

<template>
    <div v-if="element && element.type === 'text'" class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            Propiedades de texto
        </h3>

        <div class="space-y-3">
            <div>
                <label class="mb-1 block text-xs text-gray-400">Contenido</label>
                <input
                    :value="element.content"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    @input="emitChange('content', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Tamaño</label>
                <input
                    :value="element.fontSize"
                    type="range"
                    min="10"
                    max="80"
                    class="w-full"
                    @input="emitChange('fontSize', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.fontSize }}px</span>
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Color</label>
                <input
                    :value="element.color"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    @input="emitChange('color', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Estilo</label>
                <div class="flex gap-2">
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 font-bold text-indigo-700': element.fontWeight === 'bold' }"
                        @click="emitChange('fontWeight', element.fontWeight === 'bold' ? 'normal' : 'bold')"
                    >
                        <strong>B</strong>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
