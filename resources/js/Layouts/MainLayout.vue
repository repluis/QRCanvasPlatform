<script setup>
import AppHeader from '../components/layout/AppHeader.vue'
import AppFooter from '../components/layout/AppFooter.vue'
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
