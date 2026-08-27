<script setup>
const emit = defineEmits(['addAnimation'])

const ANIMATIONS = [
    { id: 'float-heart',     label: 'Heart float',     emoji: '💖', color: '#ef4444', fontSize: 56 },
    { id: 'spin-star',       label: 'Spinning star',   emoji: '⭐', color: '#fbbf24', fontSize: 52 },
    { id: 'pulse-heart',     label: 'Pulse heart',     emoji: '❤️', color: '#dc2626', fontSize: 56 },
    { id: 'bounce-circle',   label: 'Bouncing ball',   emoji: '🔴', color: '#ef4444', fontSize: 48 },
    { id: 'twinkle-star',    label: 'Twinkling star',  emoji: '✨', color: '#facc15', fontSize: 52 },
    { id: 'drift-cloud',     label: 'Floating cloud',  emoji: '☁️', color: '#94a3b8', fontSize: 52 },
    { id: 'float-flower',    label: 'Floating flower', emoji: '🌸', color: '#f472b6', fontSize: 52 },
    { id: 'spin-moon',       label: 'Spinning moon',   emoji: '🌙', color: '#fbbf24', fontSize: 52 },
]
</script>

<template>
    <div class="space-y-2">
        <p class="text-xs text-gray-400">
            Elementos con animación CSS — se reproducen continuamente al ver la página.
        </p>
        <button
            v-for="a in ANIMATIONS"
            :key="a.id"
            class="group flex w-full items-center gap-3 rounded-lg border-2 border-transparent px-3 py-2 text-left transition hover:border-indigo-400 hover:bg-indigo-50 active:scale-95"
            @click="emit('addAnimation', a)"
            :title="'Add ' + a.label"
        >
            <span
                class="text-2xl"
                :style="{
                    color: a.color,
                    animation: `${a.id} 2.4s ease-in-out infinite`,
                    display: 'inline-block',
                }"
            >{{ a.emoji }}</span>
            <span class="flex-1 text-sm font-medium text-gray-700">{{ a.label }}</span>
        </button>
    </div>
</template>
