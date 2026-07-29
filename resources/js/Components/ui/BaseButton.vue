<script setup>
defineProps({
    variant: { type: String, default: 'primary' },
    size: { type: String, default: 'md' },
    disabled: { type: Boolean, default: false },
})

defineEmits(['click'])
</script>

<template>
    <button
        class="inline-flex items-center justify-center rounded-lg font-medium transition active:scale-95 disabled:pointer-events-none disabled:opacity-50"
        :style="[
            variant === 'primary' && {
                backgroundColor: 'var(--primary)',
                color: '#fff',
            },
            variant === 'secondary' && {
                backgroundColor: 'var(--surface-alt)',
                color: 'var(--text)',
                border: '1px solid var(--border)',
            },
            variant === 'ghost' && {
                backgroundColor: 'transparent',
                color: 'var(--text-muted)',
            },
            variant === 'danger' && {
                backgroundColor: 'var(--danger)',
                color: '#fff',
            },
            size === 'sm' && { padding: '0.375rem 0.75rem', fontSize: '0.75rem' },
            size === 'md' && { padding: '0.5rem 1rem', fontSize: '0.875rem' },
            size === 'lg' && { padding: '0.75rem 1.5rem', fontSize: '1rem' },
        ]"
        @click="$emit('click')"
    >
        <slot />
    </button>
</template>
