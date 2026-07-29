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
    <Head title="Declaración de Amor" />

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-pink-100 via-red-50 to-rose-100 p-4">
        <div class="relative w-full max-w-2xl rounded-2xl bg-white/80 p-8 shadow-xl backdrop-blur sm:p-12">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-3xl font-bold text-pink-600 sm:text-4xl">
                        Para ti ❤️
                    </h1>
                    <p class="mt-4 text-lg leading-relaxed text-gray-700">
                        Eres la razón por la que mi mundo tiene sentido.
                        Cada día a tu lado es un regalo que atesoro.
                        Tu sonrisa ilumina mis mañanas y tu amor
                        llena de calor cada rincón de mi ser.
                    </p>
                    <p class="mt-2 text-lg leading-relaxed text-gray-700">
                        Gracias por existir, por ser tú, por amarme.
                        Este pequeño detalle es solo una muestra
                        de lo inmenso que es mi amor por ti.
                    </p>
                    <p class="mt-6 text-xl font-semibold text-pink-500">
                        Te amo con todo mi corazón 💖
                    </p>
                </div>

                <div class="flex flex-col items-center gap-3">
                    <button
                        class="rounded-xl bg-pink-500 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-pink-600 active:scale-95"
                        @click="toggleQR"
                    >
                        {{ showQR ? 'Ocultar QR' : 'Ver QR' }}
                    </button>
                    <p class="text-xs text-gray-400">
                        Escanea para ver esta página
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
