<script setup>
import AppHeader from '../Components/layout/AppHeader.vue'
import AppFooter from '../Components/layout/AppFooter.vue'
</script>

<template>
    <div
        class="flex min-h-screen flex-col"
        :style="{ backgroundColor: 'var(--bg)', color: 'var(--text)' }"
    >
        <AppHeader />

        <main class="flex-1">
            <slot />
        </main>

        <AppFooter />
    </div>
</template>
