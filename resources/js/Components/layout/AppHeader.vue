<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'

const user = usePage().props.auth?.user

function logout() {
    router.post('/logout')
}
</script>

<template>
    <header
        class="sticky top-0 z-40 border-b"
        :style="{ backgroundColor: 'var(--bg)', borderColor: 'var(--border)' }"
    >
        <div class="mx-auto flex h-14 max-w-7xl items-center justify-between px-4">
            <div class="flex items-center gap-6">
                <Link
                    href="/"
                    class="text-lg font-bold"
                    :style="{ color: 'var(--primary)' }"
                >
                    QRCanvas
                </Link>
                <nav class="hidden items-center gap-4 sm:flex">
                    <Link
                        href="/canvas"
                        class="text-sm transition"
                        :style="{ color: 'var(--text-muted)' }"
                        :hover-style="{ color: 'var(--text)' }"
                    >
                        Canvas
                    </Link>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="text-sm"
                    :style="{ color: 'var(--text-muted)' }"
                >
                    {{ user?.name }}
                </span>
                <button
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                    :style="{
                        backgroundColor: 'var(--surface-alt)',
                        color: 'var(--text-muted)',
                        borderColor: 'var(--border)',
                    }"
                    @click="logout"
                >
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>
