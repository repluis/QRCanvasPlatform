<script setup>
const props = defineProps({
    phrases: { type: Array, default: () => [] },
})

const emit = defineEmits(['addText'])
</script>

<template>
    <div class="space-y-2">
        <button
            v-for="(phrase, i) in phrases"
            :key="i"
            class="w-full rounded-lg border-2 border-transparent px-4 py-3 text-left text-sm transition hover:border-rose-300 hover:bg-rose-50 active:scale-95"
            @click="emit('addText', phrase)"
        >
            <span class="text-gray-700">{{ phrase }}</span>
        </button>
    </div>
</template>
