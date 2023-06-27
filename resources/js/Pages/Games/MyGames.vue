<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    games: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Mis juegos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Juegos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mis juegos</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado puedes acceder a todos los juegos disponibles para tí.</p>
                    </header>

                    <section class="mt-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="games.length == 0">No se encontraron juegos.</p>
                        
                        <div class="flex flex-wrap gap-10 w-full">
                            <div v-for="(game, index) in games" :key="index" class="grid grid-cols-3 gap-5 bg-gray-900 p-2 rounded-md min-h-[100px] min-w-[200px] max-sm:w-ful">
                                <div class="col-span-1 bg-cover bg-center rounded-sm" style="background-image: url('/assets/gamification/bg-game-default.jpg')">
                                </div>

                                <div class="col-span-2 w-full flex flex-col justify-between items-end">
                                    <div class="first-letter:uppercase text-right text-md font-bold text-gray-100">{{ game.name }}</div>
                                    <Link :href="game.gm2game ? route('game_instances.select_instance', {id: game?.experiment_id}) : route('games.play', {slug: game.slug})">
                                        <PrimaryButton>Jugar</PrimaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>