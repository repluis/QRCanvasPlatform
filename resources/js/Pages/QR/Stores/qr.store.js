import { defineStore } from 'pinia'
import { ref } from 'vue'
import { getQRUrl } from '../Services/qr.service'

export const useQRStore = defineStore('qr', () => {
    const qrImageUrl = ref('')
    const isModalOpen = ref(false)

    function generateQR(text, size = 200) {
        qrImageUrl.value = getQRUrl(text, size)
    }

    function openModal() {
        isModalOpen.value = true
    }

    function closeModal() {
        isModalOpen.value = false
    }

    function toggleModal() {
        isModalOpen.value = !isModalOpen.value
    }

    return {
        qrImageUrl,
        isModalOpen,
        generateQR,
        openModal,
        closeModal,
        toggleModal,
    }
})
