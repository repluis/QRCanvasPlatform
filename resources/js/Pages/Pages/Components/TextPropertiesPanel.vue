<script setup>
const props = defineProps({
    element: { type: Object, default: null },
})

const emit = defineEmits(['update'])

function emitChange(field, value) {
    if (!props.element) return
    emit('update', props.element.id, { [field]: value })
}

const FONT_FAMILIES = [
    { value: 'sans-serif', label: 'Sans-serif' },
    { value: 'serif', label: 'Serif' },
    { value: 'monospace', label: 'Monospace' },
    { value: 'Georgia', label: 'Georgia' },
    { value: 'Arial', label: 'Arial' },
    { value: 'Times New Roman', label: 'Times New Roman' },
    { value: 'Courier New', label: 'Courier New' },
    { value: 'Verdana', label: 'Verdana' },
    { value: 'Impact', label: 'Impact' },
    { value: 'Comic Sans MS', label: 'Comic Sans' },
    { value: 'cursive', label: 'Cursive' },
    { value: 'fantasy', label: 'Fantasy' },
]
</script>

<template>
    <div v-if="element" class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            {{ element.type === 'shape' ? 'Shape properties' : 'Text properties' }}
        </h3>

        <div class="space-y-3">
            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs text-gray-400">Content</label>
                <input
                    :value="element.content"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    @input="emitChange('content', $event.target.value)"
                />
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs text-gray-400">Font</label>
                <select
                    :value="element.fontFamily"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    @change="emitChange('fontFamily', $event.target.value)"
                >
                    <option v-for="f in FONT_FAMILIES" :key="f.value" :value="f.value">{{ f.label }}</option>
                </select>
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs text-gray-400">Size</label>
                <input
                    :value="element.fontSize"
                    type="range"
                    min="8"
                    max="120"
                    class="w-full"
                    @input="emitChange('fontSize', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.fontSize }}px</span>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs text-gray-400">Shape</label>
                <p class="text-sm font-medium capitalize" :style="{ color: 'var(--text)' }">
                    {{ element.shape }}
                </p>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs text-gray-400">Width</label>
                <input
                    :value="element.width"
                    type="range"
                    min="40"
                    max="300"
                    class="w-full"
                    @input="emitChange('width', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.width }}px</span>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs text-gray-400">Height</label>
                <input
                    :value="element.height"
                    type="range"
                    min="40"
                    max="300"
                    class="w-full"
                    @input="emitChange('height', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.height }}px</span>
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

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs text-gray-400">Style</label>
                <div class="flex gap-1.5">
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 font-bold text-indigo-700': element.fontWeight === 'bold' }"
                        @click="emitChange('fontWeight', element.fontWeight === 'bold' ? 'normal' : 'bold')"
                        title="Bold"
                    >
                        <strong>B</strong>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm italic transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 text-indigo-700': element.fontStyle === 'italic' }"
                        @click="emitChange('fontStyle', element.fontStyle === 'italic' ? 'normal' : 'italic')"
                        title="Italic"
                    >
                        I
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm underline transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 font-bold text-indigo-700': element.textDecoration === 'underline' }"
                        @click="emitChange('textDecoration', element.textDecoration === 'underline' ? 'none' : 'underline')"
                        title="Underline"
                    >
                        U
                    </button>
                </div>
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs text-gray-400">Alignment</label>
                <div class="flex gap-1.5">
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 text-indigo-700': element.textAlign === 'left' }"
                        @click="emitChange('textAlign', 'left')"
                        title="Align left"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14" /></svg>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 text-indigo-700': element.textAlign === 'center' }"
                        @click="emitChange('textAlign', 'center')"
                        title="Align center"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M8 12h8M6 18h12" /></svg>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition hover:bg-gray-100"
                        :class="{ 'border-indigo-500 bg-indigo-50 text-indigo-700': element.textAlign === 'right' }"
                        @click="emitChange('textAlign', 'right')"
                        title="Align right"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
