<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
})

function submit() {
    form.post('/login', {
        onError: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-purple-100">
        <div class="w-full max-w-md rounded-2xl bg-white/80 p-8 shadow-xl backdrop-blur">
            <h1 class="mb-6 text-center text-2xl font-bold text-gray-800">
                QRCanvasPlatform
            </h1>
            <p class="mb-8 text-center text-sm text-gray-500">
                Inicia sesión para continuar
            </p>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                        Correo electrónico
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        placeholder="correo@ejemplo.com"
                        autocomplete="email"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        placeholder="••••••••"
                        autocomplete="current-password"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 active:scale-[0.98] disabled:opacity-50"
                >
                    {{ form.processing ? 'Ingresando...' : 'Iniciar Sesión' }}
                </button>
            </form>
        </div>
    </div>
</template>
