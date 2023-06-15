<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DeleteEntryPointForm from './Partials/DeleteEntryPointForm.vue';
import ShowInformation from './Partials/ShowInformation.vue';

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
    <Head title="Gestionar entrypoints" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento / Entrypoints</h2>
        </template>
        
        <section class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Entrypoints vinculados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar los entrypoints asociados al experimento.</p>
                    </header>

                    <div class="flex flex-wrap gap-10 w-full">
                        <div class="mt-5 w-full"> 
                                <p class="text-sm text-gray-600 dark:text-gray-400" v-if="entrypoints.length == 0">No se encontraron entrypoints en este experimento.</p>
                                <table v-else class="rounded-sm shadow table-fixed w-full border-collapse">
                                    <thead class="border dark:text-white">
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody class="border first-letter:uppercase text-gray-900 dark:text-gray-200">
                                        <tr v-for="(entrypoint, index) in entrypoints" :key="index" class="border">
                                            <td class="px-3 text-center">{{ entrypoint.name }}</td>
                                            <td class="px-3 text-center">{{ entrypoint.description }}</td>
                                            <td class="px-3">
                                                <div class="flex justify-center gap-2">
                                                    <ShowInformation :entrypoint="entrypoint"/>
                                                    <Link :href="route('entrypoints.edit', {id: entrypoint.id})">
                                                        <PrimaryButton><i class="fas fa-edit"></i></PrimaryButton>
                                                    </Link>
                                                    <DeleteEntryPointForm :entrypoint_id="entrypoint.id" :entrypoint_name="entrypoint.name" class="max-w-xl" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                        
                        <div class="mt-5 flex items-center justify-center w-full">
                            <Link class="mr-2" :href="route('entrypoints.create', {id: experiment_id})">
                                <PrimaryButton>Agregar</PrimaryButton>
                            </Link>
                            
                            <Link class="ml-2" :href="route('experiment.management', {id: experiment_id})">
                                <PrimaryButton>Volver</PrimaryButton>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>