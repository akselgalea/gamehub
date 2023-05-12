<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onBeforeMount } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    game: {
        type: Object,
        required: true
    },
    location: {
        type: String,
        required: true
    }
})

const setFullscreen = () => {
    const gameIFrame = document.getElementById('game');

    gameIFrame.requestFullscreen();
}
</script>

<template>
    <Head :title="game.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Juegos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <header>
                    <h2 class="first-letter:uppercase">{{game.name}}</h2>
                </header>

                <div class="max-w-7xl h-full mx-auto">
                    <iframe id="game" class="w-full" height="600" :src="location"></iframe>
                </div>

                <PrimaryButton @click="setFullscreen">Fullscreen</PrimaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>