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
    <div v-if="element" class="w-64 border-l p-4" :style="{ backgroundColor: 'var(--surface)', borderColor: 'var(--border)' }">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider" :style="{ color: 'var(--text-muted)' }">
            {{ element.type === 'shape' ? 'Shape properties' : 'Text properties' }}
        </h3>

        <div class="space-y-3">
            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Content</label>
                <input
                    :value="element.content"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    :style="{ backgroundColor: 'var(--bg)', borderColor: 'var(--border)', color: 'var(--text)' }"
                    @input="emitChange('content', $event.target.value)"
                />
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Font</label>
                <select
                    :value="element.fontFamily"
                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                    :style="{ backgroundColor: 'var(--bg)', borderColor: 'var(--border)', color: 'var(--text)' }"
                    @change="emitChange('fontFamily', $event.target.value)"
                >
                    <option v-for="f in FONT_FAMILIES" :key="f.value" :value="f.value">{{ f.label }}</option>
                </select>
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Size</label>
                <input
                    :value="element.fontSize"
                    type="range"
                    min="8"
                    max="120"
                    class="w-full accent-indigo-500"
                    @input="emitChange('fontSize', Number($event.target.value))"
                />
                <span class="text-xs" :style="{ color: 'var(--text-dim)' }">{{ element.fontSize }}px</span>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Shape</label>
                <p class="text-sm font-medium capitalize" :style="{ color: 'var(--text)' }">
                    {{ element.shape }}
                </p>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Width</label>
                <input
                    :value="element.width"
                    type="range"
                    min="40"
                    max="300"
                    class="w-full accent-indigo-500"
                    @input="emitChange('width', Number($event.target.value))"
                />
                <span class="text-xs" :style="{ color: 'var(--text-dim)' }">{{ element.width }}px</span>
            </div>

            <div v-if="element.type === 'shape'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Height</label>
                <input
                    :value="element.height"
                    type="range"
                    min="40"
                    max="300"
                    class="w-full accent-indigo-500"
                    @input="emitChange('height', Number($event.target.value))"
                />
                <span class="text-xs" :style="{ color: 'var(--text-dim)' }">{{ element.height }}px</span>
            </div>

            <div>
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Color</label>
                <input
                    :value="element.color"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border"
                    :style="{ borderColor: 'var(--border)' }"
                    @input="emitChange('color', $event.target.value)"
                />
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Style</label>
                <div class="flex gap-1.5">
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition"
                        :style="{
                            borderColor: element.fontWeight === 'bold' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.fontWeight === 'bold' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.fontWeight === 'bold' ? '#818cf8' : 'var(--text-muted)',
                        }"
                        @click="emitChange('fontWeight', element.fontWeight === 'bold' ? 'normal' : 'bold')"
                        title="Bold"
                    >
                        <strong>B</strong>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm italic transition"
                        :style="{
                            borderColor: element.fontStyle === 'italic' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.fontStyle === 'italic' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.fontStyle === 'italic' ? '#818cf8' : 'var(--text-muted)',
                        }"
                        @click="emitChange('fontStyle', element.fontStyle === 'italic' ? 'normal' : 'italic')"
                        title="Italic"
                    >
                        I
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm underline transition"
                        :style="{
                            borderColor: element.textDecoration === 'underline' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.textDecoration === 'underline' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.textDecoration === 'underline' ? '#818cf8' : 'var(--text-muted)',
                        }"
                        @click="emitChange('textDecoration', element.textDecoration === 'underline' ? 'none' : 'underline')"
                        title="Underline"
                    >
                        U
                    </button>
                </div>
            </div>

            <div v-if="element.type === 'text'">
                <label class="mb-1 block text-xs" :style="{ color: 'var(--text-dim)' }">Alignment</label>
                <div class="flex gap-1.5">
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition"
                        :style="{
                            borderColor: element.textAlign === 'left' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.textAlign === 'left' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.textAlign === 'left' ? '#818cf8' : 'var(--text-muted)',
                        }"
                        @click="emitChange('textAlign', 'left')"
                        title="Align left"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14" /></svg>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition"
                        :style="{
                            borderColor: element.textAlign === 'center' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.textAlign === 'center' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.textAlign === 'center' ? '#818cf8' : 'var(--text-muted)',
                        }"
                        @click="emitChange('textAlign', 'center')"
                        title="Align center"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M8 12h8M6 18h12" /></svg>
                    </button>
                    <button
                        class="rounded-lg border px-3 py-1.5 text-sm transition"
                        :style="{
                            borderColor: element.textAlign === 'right' ? '#6366f1' : 'var(--border)',
                            backgroundColor: element.textAlign === 'right' ? 'rgba(99,102,241,0.15)' : 'transparent',
                            color: element.textAlign === 'right' ? '#818cf8' : 'var(--text-muted)',
                        }"
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
