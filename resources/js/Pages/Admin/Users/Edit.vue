<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import UpdateUserForm from './Partials/UpdateUserForm.vue';

defineProps({
    user: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <Head title="Editar datos de un usuario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuario</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateUserForm :user="user"/>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>