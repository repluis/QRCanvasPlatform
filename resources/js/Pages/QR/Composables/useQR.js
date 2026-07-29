import { ref } from 'vue'
import { getQRUrl } from '../Services/qr.service'

export function useQR() {
    const qrImageUrl = ref('')
    const isVisible = ref(false)

    function generate(text, size = 200) {
        qrImageUrl.value = getQRUrl(text, size)
        isVisible.value = true
    }

    function toggle() {
        isVisible.value = !isVisible.value
    }

    function show() {
        isVisible.value = true
    }

    function hide() {
        isVisible.value = false
    }

    return {
        qrImageUrl,
        isVisible,
        generate,
        toggle,
        show,
        hide,
    }
}
