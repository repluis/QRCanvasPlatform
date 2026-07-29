<template>
    <footer
        class="border-t py-6 text-center text-xs"
        :style="{
            backgroundColor: 'var(--bg)',
            borderColor: 'var(--border)',
            color: 'var(--text-dim)',
        }"
    >
        &copy; {{ new Date().getFullYear() }} QRCanvasPlatform. All rights reserved.
    </footer>
</template>
