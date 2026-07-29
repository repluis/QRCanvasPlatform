<script setup>
defineProps({
    padding: { type: String, default: 'md' },
    hover: { type: Boolean, default: false },
})
</script>

<template>
    <div
        class="rounded-xl border transition"
        :class="{ 'hover:shadow-lg': hover }"
        :style="{
            backgroundColor: 'var(--surface)',
            borderColor: 'var(--border)',
            padding: padding === 'sm' ? '1rem' : padding === 'lg' ? '1.5rem' : '1.25rem',
        }"
    >
        <slot />
    </div>
</template>
