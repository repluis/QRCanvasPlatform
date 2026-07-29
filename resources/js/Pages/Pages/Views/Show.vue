<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { defineOptions } from 'vue'

defineOptions({ layout: null })

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

    <div
        class="flex min-h-screen items-start justify-center"
        :style="{ backgroundColor: 'var(--bg)', paddingTop: '2rem' }"
    >
        <div
            class="relative shadow-lg"
            :style="{ width: '800px', minHeight: '600px', backgroundColor: '#fff' }"
        >
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
                class="absolute inset-0 flex items-center justify-center"
                :style="{ color: 'var(--text-dim)' }"
            >
                <p>This page is empty</p>
            </div>
        </div>
    </div>
</template>
