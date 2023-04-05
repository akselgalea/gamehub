<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    games: {
        type: Array,
        required: true
    }
})

console.log(props.games);
</script>

<template>
    <Head title="Todos los juegos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Juegos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <header>
                    <h2>Todos los juegos</h2>
                </header>

                <div v-for="(game, index) in games" :key="index">
                    {{ game.name }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>