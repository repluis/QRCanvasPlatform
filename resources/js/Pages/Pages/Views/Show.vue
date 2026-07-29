<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'

const { page } = usePage().props

function elementStyle(el) {
    return {
        position: 'absolute',
        left: `${el.x}px`,
        top: `${el.y}px`,
        width: `${el.width}px`,
        height: `${el.height}px`,
        fontSize: el.fontSize ? `${el.fontSize}px` : undefined,
        fontWeight: el.fontWeight,
        color: el.color,
    }
}
</script>

<template>
    <Head :title="page.title" />

    <div class="min-h-screen bg-gray-100">
        <div class="mx-auto flex min-h-screen w-[800px] flex-col bg-white shadow-lg">
            <div class="flex items-center justify-between border-b px-6 py-3">
                <h1 class="text-lg font-semibold text-gray-800">{{ page.title }}</h1>
                <Link
                    href="/editor"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                >
                    Editar
                </Link>
            </div>

            <div class="relative flex-1" style="min-height: 600px">
                <div v-for="el in page.elements" :key="el.id" :style="elementStyle(el)" class="select-none">
                    <img
                        v-if="el.type === 'image'"
                        :src="el.content"
                        class="h-full w-full object-cover"
                        draggable="false"
                    />
                    <div v-else>
                        {{ el.content }}
                    </div>
                </div>

                <div
                    v-if="!page.elements || page.elements.length === 0"
                    class="absolute inset-0 flex items-center justify-center text-gray-400"
                >
                    <p>Esta página está vacía</p>
                </div>
            </div>
        </div>
    </div>
</template>
