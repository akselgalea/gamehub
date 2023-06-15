<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DeleteGameInstanceForm from './Partials/DeleteGameInstanceForm.vue';

const props = defineProps({
    games_instances: {
        type: Array,
        required: true
    },
    experiment_id: {
        type: Number,
        required: true
    }
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Instancias de juego</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver las instancias de juegos asociadas al experimento.</p>
        </header>

        <div class="w-full mt-5 flex justify-center align-middle">
            <p v-if="games_instances.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron instancias de juegos asociados.</p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                    <thead class="border">
                        <th>Nombre</th>
                        <th>Juego</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr v-for="(game_instance, index) in games_instances" :key="index" class="border">
                            <td class="px-3 text-center">{{ game_instance.name }}</td>
                            <td class="px-3 text-center">{{ game_instance.game?.name ?? '' }}</td>
                            <td class="px-3 flex gap-1 justify-center">
                                <Link :href="route('game_instances.edit', {id: experiment_id, slug: game_instance.slug})">
                                    <PrimaryButton title="Editar"><i class="fas fa-edit"></i></PrimaryButton>
                                </Link>
                                <DeleteGameInstanceForm :game_instance_id="game_instance.id" :game_instance_name="game_instance.name" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
        
        <div  class="mt-4 flex items-center justify-center">
            <Link :href="route('game_instances.show', {id: experiment_id})">
                <PrimaryButton>Gestionar</PrimaryButton>
            </Link>
        </div>

    </section>
</template>