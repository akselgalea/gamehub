<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import UpdateEntryPointForm from './Partials/UpdateEntryPointForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteEntryPointForm from './Partials/DeleteEntryPointForm.vue';

defineProps({
    entrypoints: {
        type: Array,
        required: true
    },
    experiment_id: {
        type: String,
        required: true
    }
});
</script>

<template>
    <Head title="Subir un juego" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Entrypoints vinculados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar los entrypoints asociados al experimento.</p>
                    </header>

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div class="flex flex-wrap gap-10 w-full">

                                    <section class="mt-5 w-full"> Entrypoints :
                                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="entrypoints.length == 0">No se encontraron entrypoints en este experimento.</p>
                                            <div class="flex flex-wrap gap-10 w-full md:w-1/2">
                                                <div v-for="(entrypoint, index) in entrypoints" :key="index">
                                                <div class="first-letter:uppercase">Nombre: {{ entrypoint.name }}</div>
                                                <div class="first-letter:uppercase">Descripcion: {{ entrypoint.description }}</div>
                                                    <div class="flex gap-2">
                                                        <Link :href="route('entrypoints.edit', {id: entrypoint.id})">
                                                            <PrimaryButton>Editar</PrimaryButton>
                                                        </Link>
                                                            <DeleteEntryPointForm :entrypoint_id="entrypoint.id" :entrypoint_name="entrypoint.name" class="max-w-xl" />
                                                    </div>
                                                </div>
                                            </div>

                                    </section>
                                    
                                    <section class="mt-5 flex items-center justify-center w-full">
                                        
                                        <Link class="mr-2" :href="route('entrypoints.create', {id: experiment_id})">
                                            <PrimaryButton>Agregar</PrimaryButton>
                                        </Link>
                                        
                                        <Link class="ml-2" :href="route('experiment.management', {id: experiment_id})">
                                            <PrimaryButton>Volver</PrimaryButton>
                                        </Link>

                                    </section>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>