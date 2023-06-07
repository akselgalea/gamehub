<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteGameInstanceForm from './Partials/DeleteGameInstanceForm.vue';

defineProps({
    games_instances: {
        type: Array,
        required: true
    },
    games: {
        type: Array,
        required: true
    },
    experiment_id: {
        type: Number,
        required: true
    }
});

</script>

<template>
    <Head title="Editar instancias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Instancias asociadas al experimento</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar las instancias de juego asociados al experimento.</p>
                    </header>

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div class="flex flex-wrap gap-10 w-full">

                                    <section class="mt-5 w-full">
                                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="games_instances.length == 0">No se encontraron instancias de juegos asociados.</p>

                                            <template v-else>
                                                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                                    <thead class="border">
                                                        <th>Nombre</th>
                                                        <th>Descripcion</th>
                                                        <th>Juego</th>
                                                        <th>Acciones</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(game_instance, index) in games_instances" :key="index" class="border">

                                                            <td class="px-3 text-justify">{{ game_instance.name }}</td>
                                                            <td class="px-3 text-justify">{{ game_instance.description }}</td>

                                                            <template v-for="(game) in games">
                                                                <td v-if="game_instance.game_id === game.id" class="px-3 text-justify">{{ game.name }}</td>
                                                            </template>

                                                            <td class="px-3 flex gap-1 justify-center">
                                                                <Link :href="route('game_instances.edit', {id: game_instance.id})">
                                                                    <PrimaryButton>Editar</PrimaryButton>
                                                                </Link>
                                                                    <DeleteGameInstanceForm :game_instance_id="game_instance.id" :game_instance_name="game_instance.name" class="max-w-xl" />
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </template>
                                            
                                    </section>

                                    <!-- <section class="mt-5 w-full"> 

                                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="games_instances.length == 0">No se encontraron instancias de juegos asociados.</p>
                                            <div class="flex flex-wrap gap-10 w-full md:w-1/2">
                                                <div v-for="(game_instance, index) in games_instances" :key="index">
                                                <div class="first-letter:uppercase">Nombre: {{ game_instance.name }}</div>

                                                <template  v-for="(game) in games" >
                                                    <div v-if="game_instance.game_id === game.id" class="first-letter:uppercase"> Juego: {{ game.name }} </div>
                                                </template>
                                                
                                                <div class="first-letter:uppercase">Descripcion: {{ game_instance.description }}</div>
                                                    <div class="flex gap-2">
                                                        <Link :href="route('game_instances.edit', {id: game_instance.id})">
                                                            <PrimaryButton>Editar</PrimaryButton>
                                                        </Link>
                                                            <DeleteGameInstanceForm :game_instance_id="game_instance.id" :game_instance_name="game_instance.name" class="max-w-xl" />
                                                    </div>
                                                </div>
                                            </div>

                                    </section> -->
                                    
                                    <section class="mt-5 flex items-center justify-center w-full">
                                        
                                        <Link class="mr-2" :href="route('game_instances.create', {id: experiment_id})">
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