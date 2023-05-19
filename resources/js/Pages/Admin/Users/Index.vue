<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    users: {
        type: Array,
        required: true
    }
})

</script>

<template>
    <Head title="Todos los usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Usuarios registrados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden ver usuarios registrados en la plataforma.</p>
                    </header>

                    <section class="mt-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="users.length == 0">No se encontraron usuarios.</p>
                        
                        <div class="flex flex-wrap gap-10 w-full">
                            <div v-for="(user, index) in users" :key="index">
                                <div class="first-letter:uppercase">{{ user.name }}</div>
                                <div class="first-letter:uppercase">{{ user.email }}</div>

                                <div class="flex gap-2">
                                    <Link :href="route('users_panel.index', {id: user.id})">
                                        <PrimaryButton>Editar</PrimaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>