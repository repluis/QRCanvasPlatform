<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    pages: { type: Array, default: () => [] },
})

defineEmits(['close'])
</script>

<template>
    <aside
        class="flex h-full w-64 flex-col border-r"
        :style="{ backgroundColor: 'var(--bg)', borderColor: 'var(--border)' }"
    >
        <div
            class="flex items-center justify-between border-b px-4 py-3"
            :style="{ borderColor: 'var(--border)' }"
        >
            <h3
                class="text-xs font-semibold uppercase tracking-wider"
                :style="{ color: 'var(--text-dim)' }"
            >
                Pages
            </h3>
            <Link
                href="/canvas"
                class="rounded-lg px-3 py-1 text-xs font-medium transition"
                :style="{
                    backgroundColor: 'var(--primary)',
                    color: '#fff',
                }"
            >
                + New
            </Link>
        </div>

        <div class="flex-1 overflow-y-auto p-2">
            <Link
                v-for="p in pages"
                :key="p.uuid"
                :href="'/page?uuid=' + p.uuid"
                class="mb-1 block rounded-lg px-3 py-2 text-sm transition"
                :style="{ color: 'var(--text-muted)' }"
                :hover-style="{ backgroundColor: 'var(--surface-alt)', color: 'var(--text)' }"
            >
                <span class="truncate font-medium">{{ p.title }}</span>
            </Link>

            <p
                v-if="pages.length === 0"
                class="px-3 py-6 text-center text-sm"
                :style="{ color: 'var(--text-dim)' }"
            >
                No pages yet
            </p>
        </div>
    </aside>
</template>
