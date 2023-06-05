<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import UpdateExperimentForm from './Partials/UpdateExperimentForm.vue';

defineProps({
    experiment: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <Head title="Editar un experimento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateExperimentForm :experiment="experiment"/>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>