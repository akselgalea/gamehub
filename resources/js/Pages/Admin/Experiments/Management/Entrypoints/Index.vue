<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    entrypoints: {
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
    <Head title="Entrypoint" />
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Entrypoint</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los entrypoints del experimento.</p>
        </header>

        <div class="mt-5">
            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="entrypoints.length == 0">No se encontraron entrypoints asociados.</p>

            <div class="flex flex-wrap gap-10 w-full">
                <div v-for="(entrypoint, index) in entrypoints" :key="index">
                    <div class="first-letter:uppercase">Entrypoint: {{ entrypoint.name }}</div>
                    <div class="first-letter:uppercase">Descripcion: {{ entrypoint.description }}</div>
                    <!-- <div class="flex gap-2">
                        <Link :href="route('entrypoints.edit', {id: entrypoint.id})">
                            <PrimaryButton>Editar</PrimaryButton>
                        </Link>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-center">
            <Link :href="route('entrypoints.show', {id: experiment_id})">
                <PrimaryButton>Editar</PrimaryButton>
            </Link>
        </div>
    </section>
</template>