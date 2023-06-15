<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateEntryPointForm from './Partials/CreateEntryPointForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    experiment_id: {
        type: String,
        required: true
    },
});

</script>

<template>
    <Head title="Crear entrypoint" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento / Entrypoint</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <CreateEntryPointForm :experiment_id="experiment_id"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>