<script setup>
import { ref } from 'vue'
import ImagesTab from './ImagesTab.vue'
import ShapesTab from './ShapesTab.vue'
import PhrasesTab from './PhrasesTab.vue'
import AnimationsTab from './AnimationsTab.vue'
import CarouselTab from './CarouselTab.vue'
import BackgroundsTab from './BackgroundsTab.vue'

const props = defineProps({
    images: { type: Array, default: () => [] },
    shapes: { type: Array, default: () => [] },
    phrases: { type: Array, default: () => [] },
})

const emit = defineEmits(['addImageToCanvas', 'addShape', 'addText', 'setBackground', 'addAnimation', 'addCarousel'])

const tab = ref('images')

const TABS = [
    { id: 'images',      label: 'Img',    icon: '🖼️' },
    { id: 'shapes',      label: 'Shapes', icon: '🔷' },
    { id: 'phrases',     label: 'Text',   icon: '✏️' },
    { id: 'animations',  label: 'Anim',   icon: '✨' },
    { id: 'carousel',    label: 'Car',    icon: '🎠' },
    { id: 'backgrounds', label: 'Bg',     icon: '🎨' },
]
</script>

<template>
    <div class="flex h-full w-64 flex-col border-l bg-white">
        <!-- Vertical tab bar -->
        <div class="flex flex-col border-b">
            <button
                v-for="t in TABS"
                :key="t.id"
                class="flex items-center gap-2 px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider transition"
                :class="tab === t.id
                    ? 'bg-indigo-50 text-indigo-600 border-l-3 border-indigo-500'
                    : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600 border-l-3 border-transparent'"
                @click="tab = t.id"
            >
                <span>{{ t.icon }}</span>
                <span>{{ t.label }}</span>
            </button>
        </div>

        <!-- Tab content -->
        <div class="flex-1 overflow-y-auto p-3">
            <ImagesTab
                v-if="tab === 'images'"
                @add-image="emit('addImageToCanvas', $event)"
            />
            <ShapesTab
                v-else-if="tab === 'shapes'"
                :shapes="shapes"
                @add-shape="emit('addShape', $event)"
            />
            <PhrasesTab
                v-else-if="tab === 'phrases'"
                :phrases="phrases"
                @add-text="emit('addText', $event)"
            />
            <AnimationsTab
                v-else-if="tab === 'animations'"
                @add-animation="emit('addAnimation', $event)"
            />
            <CarouselTab
                v-else-if="tab === 'carousel'"
                @add-carousel="emit('addCarousel', $event)"
            />
            <BackgroundsTab
                v-else-if="tab === 'backgrounds'"
                @set-background="emit('setBackground', $event)"
            />
        </div>
    </div>
</template>
