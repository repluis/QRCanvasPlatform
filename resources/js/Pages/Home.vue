<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3'

const { userPages } = usePage().props
const user = usePage().props.auth?.user

function navigateToCanvas() {
    router.visit('/canvas')
}
</script>

<template>
    <Head title="Home" />

    <div class="mx-auto max-w-4xl px-4 py-12">
        <div class="mb-10 text-center">
            <h1
                class="text-4xl font-bold"
                :style="{ color: 'var(--text)' }"
            >
                Welcome, {{ user?.name }} 👋
            </h1>
            <p class="mt-2" :style="{ color: 'var(--text-muted)' }">
                QRCanvasPlatform — Create and share beautiful pages
            </p>
        </div>

        <div class="mb-8 flex items-center justify-between">
            <h2
                class="text-xl font-semibold"
                :style="{ color: 'var(--text)' }"
            >
                Your pages
            </h2>
            <Link
                href="/canvas"
                class="rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow transition hover:opacity-90 active:scale-95"
                :style="{ backgroundColor: 'var(--primary)' }"
            >
                + New page
            </Link>
        </div>

        <div
            v-if="userPages.length === 0"
            class="rounded-2xl border-2 border-dashed p-16 text-center"
            :style="{
                borderColor: 'var(--border)',
                backgroundColor: 'var(--surface)',
            }"
        >
            <p class="text-lg" :style="{ color: 'var(--text-dim)' }">
                You don't have any pages yet
            </p>
            <button
                class="mt-4 inline-block rounded-lg px-6 py-3 text-sm font-semibold text-white shadow transition hover:opacity-90"
                :style="{ backgroundColor: 'var(--primary)' }"
                @click="navigateToCanvas"
            >
                Create your first page
            </button>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2">
            <div
                v-for="p in userPages"
                :key="p.uuid"
                class="group rounded-xl border p-5 shadow-sm transition hover:shadow-md"
                :style="{
                    backgroundColor: 'var(--surface)',
                    borderColor: 'var(--border)',
                }"
            >
                <div class="mb-3">
                    <h3
                        class="truncate text-lg font-semibold"
                        :style="{ color: 'var(--text)' }"
                    >
                        {{ p.title }}
                    </h3>
                    <p class="text-xs" :style="{ color: 'var(--text-dim)' }">
                        {{ p.updated_at }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link
                        :href="'/page?uuid=' + p.uuid"
                        class="rounded-lg border px-3 py-1.5 text-xs font-medium transition"
                        :style="{
                            borderColor: 'var(--border)',
                            color: 'var(--text-muted)',
                        }"
                    >
                        View
                    </Link>
                        <Link
                            :href="'/canvas?uuid=' + p.uuid"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium text-white transition hover:opacity-90"
                            :style="{ backgroundColor: 'var(--primary)' }"
                        >
                            Edit
                        </Link>
                </div>
            </div>
        </div>
    </div>
</template>
