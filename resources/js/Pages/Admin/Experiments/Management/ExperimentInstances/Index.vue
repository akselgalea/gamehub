<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

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
    <Head title="Instancias de juego" />
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Instancias de juego</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver las instancias de juegos asociadas al experimento.</p>
        </header>

        <div class="mt-5">
            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="games_instances.length == 0">No se encontraron instancias de juegos asociados.</p>
            
            <div class="flex flex-wrap gap-10 w-full">
                <div v-for="(game_instance, index) in games_instances" :key="index">
                    <div class="first-letter:uppercase">Instancia: {{ game_instance.name }}</div>
                    <div class="first-letter:uppercase">Descripcion: {{ game_instance.description }}</div>
                    <div class="flex gap-2">
                        <Link :href="route('game_instances.edit', {id: game_instance.id})">
                            <PrimaryButton>Editar</PrimaryButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-center">
            <Link :href="route('game_instances.show', {id: experiment_id})">
                <PrimaryButton>Editar</PrimaryButton>
            </Link>
        </div>
    </section>
</template>