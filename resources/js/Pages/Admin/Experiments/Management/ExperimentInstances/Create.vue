<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateGameInstanceForm from './Partials/CreateGameInstanceForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    experiment_id: {
        type: Number,
        required: true
    },
    games: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <Head title="Crear experimento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Instancias de juego</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <CreateGameInstanceForm :games="games" :experiment_id="experiment_id"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>