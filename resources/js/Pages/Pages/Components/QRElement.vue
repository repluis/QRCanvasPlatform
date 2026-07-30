<script setup>
const props = defineProps({
    element: { type: Object, required: true },
})
</script>

<template>
    <img
        v-if="element.qrImageUrl"
        :src="element.qrImageUrl"
        class="pointer-events-none h-full w-full rounded object-contain"
        :style="{ border: `4px solid ${element.foregroundColor || '#000000'}` }"
        draggable="false"
        alt="QR Code"
    />
    <div v-else class="flex h-full w-full items-center justify-center text-gray-400 text-xs">
        No QR generated
    </div>
</template>
