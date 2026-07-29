<script setup>
defineProps({
    imageUrl: { type: String, required: true },
    text: { type: String, default: '' },
})

defineEmits(['close'])
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="rounded-xl bg-white p-8 shadow-2xl">
            <div class="flex flex-col items-center gap-4">
                <img
                    :src="imageUrl"
                    :alt="'QR: ' + text"
                    class="h-64 w-64"
                />
                <p v-if="text" class="max-w-64 break-all text-center text-sm text-gray-500">
                    {{ text }}
                </p>
                <button
                    class="rounded-lg bg-gray-200 px-6 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300"
                    @click="$emit('close')"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>
