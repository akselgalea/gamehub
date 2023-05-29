<script setup>
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const user = usePage().props.auth.user;
</script>

<template>
    <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
            Tu dirección de correo electrónico no ha sido verificada
            <Link
                :href="route('verification.send')"
                method="post"
                as="button"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            >
                Haz click aquí para reenviar el correo de verificación.
            </Link>
        </p>

        <div
            v-show="status === 'verification-link-sent'"
            class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
        >
            Un nuevo link de verificacón ha sido enviado a tu correo.
        </div>
    </div>
</template>