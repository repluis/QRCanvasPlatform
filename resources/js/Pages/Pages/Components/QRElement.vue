<script setup>
const props = defineProps({
    element: { type: Object, required: true },
})
</script>

<template>
    <img
        v-if="element.qrImageUrl"
        :src="element.qrImageUrl"
        class="h-full w-full object-contain pointer-events-none"
        draggable="false"
        alt="QR Code"
    />
    <div v-else class="flex h-full w-full items-center justify-center text-gray-400 text-xs">
        No QR generated
    </div>
</template>
