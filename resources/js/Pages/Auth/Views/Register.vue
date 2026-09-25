<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { defineOptions } from 'vue'
import BaseButton from '../../../Components/ui/BaseButton.vue'

defineOptions({ layout: null })

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

function submit() {
    form.post('/register', {
        onError: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head title="Sign Up" />

    <div
        class="flex min-h-screen items-center justify-center"
        :style="{ backgroundColor: 'var(--bg)' }"
    >
        <div
            class="w-full max-w-md rounded-2xl p-8 shadow-xl"
            :style="{
                backgroundColor: 'var(--surface)',
                border: '1px solid var(--border)',
            }"
        >
            <h1
                class="mb-6 text-center text-2xl font-bold"
                :style="{ color: 'var(--text)' }"
            >
                QRCanvasPlatform
            </h1>
            <p
                class="mb-8 text-center text-sm"
                :style="{ color: 'var(--text-muted)' }"
            >
                Create your account
            </p>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label
                        for="name"
                        class="mb-1 block text-sm font-medium"
                        :style="{ color: 'var(--text-muted)' }"
                    >
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm outline-none transition"
                        :style="{
                            backgroundColor: 'var(--bg)',
                            borderColor: 'var(--border)',
                            color: 'var(--text)',
                        }"
                        placeholder="John Doe"
                        autocomplete="name"
                    />
                    <p
                        v-if="form.errors.name"
                        class="mt-1 text-xs"
                        :style="{ color: 'var(--danger)' }"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label
                        for="email"
                        class="mb-1 block text-sm font-medium"
                        :style="{ color: 'var(--text-muted)' }"
                    >
                        Email
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm outline-none transition"
                        :style="{
                            backgroundColor: 'var(--bg)',
                            borderColor: 'var(--border)',
                            color: 'var(--text)',
                        }"
                        placeholder="email@example.com"
                        autocomplete="email"
                    />
                    <p
                        v-if="form.errors.email"
                        class="mt-1 text-xs"
                        :style="{ color: 'var(--danger)' }"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-1 block text-sm font-medium"
                        :style="{ color: 'var(--text-muted)' }"
                    >
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm outline-none transition"
                        :style="{
                            backgroundColor: 'var(--bg)',
                            borderColor: 'var(--border)',
                            color: 'var(--text)',
                        }"
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <p
                        v-if="form.errors.password"
                        class="mt-1 text-xs"
                        :style="{ color: 'var(--danger)' }"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="mb-1 block text-sm font-medium"
                        :style="{ color: 'var(--text-muted)' }"
                    >
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm outline-none transition"
                        :style="{
                            backgroundColor: 'var(--bg)',
                            borderColor: 'var(--border)',
                            color: 'var(--text)',
                        }"
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <p
                        v-if="form.errors.password_confirmation"
                        class="mt-1 text-xs"
                        :style="{ color: 'var(--danger)' }"
                    >
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>

                <BaseButton
                    variant="primary"
                    size="lg"
                    :disabled="form.processing"
                    class="w-full"
                    @click="submit"
                >
                    {{ form.processing ? 'Creating account...' : 'Sign Up' }}
                </BaseButton>
            </form>

            <p class="mt-6 text-center text-sm" :style="{ color: 'var(--text-muted)' }">
                Already have an account?
                <a
                    href="/login"
                    class="ml-1 font-medium underline underline-offset-2 hover:no-underline"
                    :style="{ color: 'var(--primary)' }"
                >
                    Sign In
                </a>
            </p>
        </div>
    </div>
</template>