<script setup>
const props = defineProps({
    element: { type: Object, required: true },
})

const emit = defineEmits(['navigate'])
</script>

<template>
    <div class="flex h-full w-full items-center gap-2 pointer-events-none">
        <button
            v-for="(item, i) in element.items"
            :key="i"
            class="flex-shrink-0 rounded px-4 py-2 text-sm font-semibold whitespace-nowrap pointer-events-none"
            :style="{
                backgroundColor: element.buttonColor || '#d97706',
                color: element.buttonTextColor || '#ffffff',
                borderRadius: `${element.borderRadius || 8}px`,
            }"
        >
            {{ item.label }}
        </button>
    </div>
</template>
