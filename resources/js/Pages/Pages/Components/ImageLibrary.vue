<script setup>
import { ref } from 'vue'

const props = defineProps({
    images: { type: Array, required: true },
    shapes: { type: Array, default: () => [] },
    phrases: { type: Array, default: () => [] },
})

const emit = defineEmits(['addImageToCanvas', 'addShape', 'addText', 'setBackground'])

const tab = ref('images')

const SHAPE_ICONS = {
    heart: 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z',
    star: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
    circle: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z',
    moon: 'M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.z',
    diamond: 'M12 2L2 12l10 10 10-10L12 2z',
    triangle: 'M12 2L2 22h20L12 2z',
    hexagon: 'M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z',
    cloud: 'M19.35 10.04A7.49 7.49 0 0012 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 000 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z',
}

const SOLID_COLORS = [
    '#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4',
    '#3b82f6', '#8b5cf6', '#ec4899', '#64748b', '#1e293b',
    '#dc2626', '#ea580c', '#ca8a04', '#16a34a', '#0891b2',
    '#2563eb', '#7c3aed', '#db2777', '#475569', '#0f172a',
]

const PASTEL_COLORS = [
    '#fce4ec', '#f3e5f5', '#e8eaf6', '#e3f2fd', '#e0f7fa',
    '#e0f2f1', '#e8f5e9', '#fff9c4', '#fff3e0', '#fbe9e7',
    '#fce4ec', '#f1f8e9', '#fff8e1', '#e1f5fe', '#f3e5f5',
]

const GRADIENTS = [
    { label: 'Purple Blue', value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' },
    { label: 'Pink Red', value: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' },
    { label: 'Blue Cyan', value: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' },
    { label: 'Green Teal', value: 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)' },
    { label: 'Pink Yellow', value: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)' },
    { label: 'Lavender', value: 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)' },
    { label: 'Peach', value: 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)' },
    { label: 'Sky Blue', value: 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)' },
    { label: 'Cream Sky', value: 'linear-gradient(135deg, #fddb92 0%, #d1fdff 100%)' },
    { label: 'Silver', value: 'linear-gradient(135deg, #c3cfe2 0%, #f5f7fa 100%)' },
    { label: 'Sunset', value: 'linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%)' },
    { label: 'Ocean', value: 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)' },
]

const bgSubtab = ref('solids')

function applyBg(val) {
    emit('setBackground', val)
}
</script>

<template>
    <div class="flex w-64 flex-col border-l bg-white">
        <div class="flex border-b">
            <button
                v-for="t in ['images', 'shapes', 'phrases', 'backgrounds']"
                :key="t"
                class="flex-1 px-2 py-2.5 text-xs font-semibold uppercase tracking-wider transition"
                :class="tab === t
                    ? 'border-b-2 border-indigo-500 text-indigo-600'
                    : 'text-gray-400 hover:text-gray-600'"
                @click="tab = t"
            >
                {{ t === 'images' ? 'Img' : t === 'shapes' ? 'Shapes' : t === 'phrases' ? 'Text' : 'Bg' }}
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-3">
            <div v-if="tab === 'images'" class="grid grid-cols-2 gap-2">
                <button
                    v-for="img in images"
                    :key="img.id"
                    class="group relative overflow-hidden rounded-lg border-2 border-transparent transition hover:border-indigo-400 active:scale-95"
                    @click="$emit('addImageToCanvas', img.url)"
                    :title="'Add ' + img.label"
                >
                    <img
                        :src="img.url"
                        :alt="img.label"
                        class="h-20 w-full object-cover"
                    />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/20">
                        <span class="scale-0 text-xs font-medium text-white transition group-hover:scale-100">+</span>
                    </div>
                </button>
            </div>

            <div v-if="tab === 'shapes'" class="grid grid-cols-3 gap-2">
                <button
                    v-for="s in shapes"
                    :key="s"
                    class="flex flex-col items-center gap-1 rounded-lg border-2 border-transparent p-3 transition hover:border-pink-400 hover:bg-pink-50 active:scale-95"
                    @click="$emit('addShape', s)"
                    :title="'Add ' + s"
                >
                    <svg class="h-7 w-7 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="SHAPE_ICONS[s]" />
                    </svg>
                    <span class="text-xs capitalize text-gray-500">{{ s }}</span>
                </button>
            </div>

            <div v-if="tab === 'phrases'" class="space-y-2">
                <button
                    v-for="(phrase, i) in phrases"
                    :key="i"
                    class="w-full rounded-lg border-2 border-transparent px-4 py-3 text-left text-sm transition hover:border-rose-300 hover:bg-rose-50 active:scale-95"
                    @click="$emit('addText', phrase)"
                >
                    <span class="text-gray-700">{{ phrase }}</span>
                </button>
            </div>

            <div v-if="tab === 'backgrounds'">
                <div class="mb-3 flex gap-1">
                    <button
                        v-for="st in ['solids', 'pastels', 'gradients']"
                        :key="st"
                        class="flex-1 rounded px-2 py-1 text-xs font-medium transition"
                        :class="bgSubtab === st
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'text-gray-500 hover:bg-gray-100'"
                        @click="bgSubtab = st"
                    >
                        {{ st === 'solids' ? 'Solid' : st === 'pastels' ? 'Pastel' : 'Gradient' }}
                    </button>
                </div>

                <div v-if="bgSubtab === 'solids'" class="grid grid-cols-5 gap-1.5">
                    <button
                        v-for="c in SOLID_COLORS"
                        :key="c"
                        class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                        :style="{ backgroundColor: c }"
                        @click="applyBg(c)"
                        :title="c"
                    ></button>
                </div>

                <div v-if="bgSubtab === 'pastels'" class="grid grid-cols-5 gap-1.5">
                    <button
                        v-for="c in PASTEL_COLORS"
                        :key="c"
                        class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                        :style="{ backgroundColor: c }"
                        @click="applyBg(c)"
                        :title="c"
                    ></button>
                </div>

                <div v-if="bgSubtab === 'gradients'" class="space-y-2">
                    <button
                        v-for="g in GRADIENTS"
                        :key="g.label"
                        class="h-12 w-full rounded-lg border border-gray-200 transition hover:scale-[1.02] active:scale-95"
                        :style="{ background: g.value }"
                        @click="applyBg(g.value)"
                        :title="g.label"
                    >
                        <span class="text-xs font-medium drop-shadow-md"
                            :class="g.label === 'Cream Sky' || g.label === 'Silver' ? 'text-gray-700' : 'text-white'"
                        >{{ g.label }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
