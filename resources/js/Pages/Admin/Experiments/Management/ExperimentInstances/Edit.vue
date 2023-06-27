<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DeleteGameInstanceForm from './Partials/DeleteGameInstanceForm.vue';

defineProps({
    games_instances: {
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
    <Head title="Gestionar instancias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento / Instancias</h2>
        </template>
        
        <section class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Instancias asociadas al experimento</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar las instancias de juego asociados al experimento.</p>
                    </header>
                    <div class="flex flex-wrap gap-10 w-full">

                        <div class="mt-5 w-full">
                            <p v-if="games_instances.length == 0" class="text-sm text-gray-600 dark:text-gray-400">No se encontraron instancias de juegos asociados.</p>
                            <template v-else>
                                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                    <thead class="border dark:text-gray-100">
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Juego</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody class="dark:text-gray-200">
                                        <tr v-for="(instance, index) in games_instances" :key="index" class="border">
                                            <td class="px-3 text-center">{{ instance.name }}</td>
                                            <td class="px-3 text-center">{{ instance.description }}</td>
                                            <td class="px-3 text-center">{{ instance.game?.name ?? '' }}</td>

                                            <td class="px-3 flex gap-1 justify-center">
                                                <Link :href="route('game_instances.play', { game: instance.game.slug, instance: instance.slug, t: instance.crypted_slug })">
                                                    <PrimaryButton title="Jugar"><i class="fas fa-play"></i></PrimaryButton>
                                                </Link>

                                                <Link :href="route('game_instances.edit', {id: experiment_id, slug: instance.slug})">
                                                    <PrimaryButton title="Editar"><i class="fas fa-cog"></i></PrimaryButton>
                                                </Link>

                                                <DeleteGameInstanceForm :game_instance_id="instance.id" :game_instance_name="instance.name" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>

                        <div class="mt-5 flex items-center justify-center w-full">
                            <Link class="mr-2" :href="route('game_instances.create', {id: experiment_id})">
                                <PrimaryButton>Agregar</PrimaryButton>
                            </Link>
                            
                            <Link class="ml-2" :href="route('experiment.management', {id: experiment_id})">
                                <SecondaryButton>Volver</SecondaryButton>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>