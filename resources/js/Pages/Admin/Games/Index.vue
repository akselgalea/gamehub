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
})
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
                    <div>{{ game.name }}</div>

                    <Link :href="route('games.edit', {id: game.id})">
                        <PrimaryButton>Editar</PrimaryButton>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>