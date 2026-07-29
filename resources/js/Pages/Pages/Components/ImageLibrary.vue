<script setup>
defineProps({
    images: { type: Array, required: true },
})

defineEmits(['addImageToCanvas'])
</script>

<template>
    <div class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            Imágenes
        </h3>
        <div class="grid grid-cols-2 gap-2">
            <button
                v-for="img in images"
                :key="img.id"
                class="group relative overflow-hidden rounded-lg border-2 border-transparent transition hover:border-indigo-400 active:scale-95"
                @click="$emit('addImageToCanvas', img.url)"
                :title="'Agregar ' + img.label"
            >
                <img
                    :src="img.url"
                    :alt="img.label"
                    class="h-20 w-full object-cover"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/20">
                    <span class="scale-0 text-xs font-medium text-white transition group-hover:scale-100">
                        +
                    </span>
                </div>
            </button>
        </div>
    </div>
</template>
