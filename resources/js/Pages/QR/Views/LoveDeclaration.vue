<script setup>
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import QRDisplay from '../Components/QRDisplay.vue'

const props = defineProps({
    qrImageUrl: { type: String, required: true },
    pageUrl: { type: String, required: true },
})

const showQR = ref(false)

function toggleQR() {
    showQR.value = !showQR.value
}
</script>

<template>
    <Head title="Love Declaration" />

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-pink-100 via-red-50 to-rose-100 p-4">
        <div class="relative w-full max-w-2xl rounded-2xl bg-white/80 p-8 shadow-xl backdrop-blur sm:p-12">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-3xl font-bold text-pink-600 sm:text-4xl">
                        For you ❤️
                    </h1>
                    <p class="mt-4 text-lg leading-relaxed text-gray-700">
                        You are the reason my world makes sense.
                        Every day by your side is a treasure I hold dear.
                        Your smile lights up my mornings and your love
                        fills every corner of my being with warmth.
                    </p>
                    <p class="mt-2 text-lg leading-relaxed text-gray-700">
                        Thank you for existing, for being you, for loving me.
                        This small gesture is just a glimpse
                        of how immense my love is for you.
                    </p>
                    <p class="mt-6 text-xl font-semibold text-pink-500">
                        I love you with all my heart 💖
                    </p>
                </div>

                <div class="flex flex-col items-center gap-3">
                    <button
                        class="rounded-xl bg-pink-500 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-pink-600 active:scale-95"
                        @click="toggleQR"
                    >
                        {{ showQR ? 'Hide QR' : 'View QR' }}
                    </button>
                    <p class="text-xs text-gray-400">
                        Scan to view this page
                    </p>
                </div>
            </div>

            <QRDisplay
                v-if="showQR"
                :image-url="qrImageUrl"
                :text="pageUrl"
                @close="showQR = false"
            />
        </div>
    </div>
</template>
