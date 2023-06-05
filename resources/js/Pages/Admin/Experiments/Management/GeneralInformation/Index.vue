<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    experiment: {
        name: '',
        description: '',
        status: null,
        time_limit: null,
        required: true
    }
})

</script>

<template>
    <Head title="Experimento" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informacion general</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver la informacion del experimento.</p>
                </header>

                <section class="mt-5">
                    
                    <div class="flex flex-wrap gap-10 w-full">
                        <div class="first-letter:uppercase">Experimento: {{ experiment.name }}</div>
                        <div class="first-letter:uppercase">Estado: {{ experiment.status }}</div>
                        <div class="first-letter:uppercase">Descripcion: {{ experiment.description }}</div>
                        <div class="first-letter:uppercase">Tiempo limite: {{ experiment.time_limit }}</div>
                    </div>

                </section>

                <section class="mt-4 flex items-center justify-center">

                        <Link :href="route('experiment_information.edit', {id: experiment.id})">
                            <PrimaryButton>Editar</PrimaryButton>
                        </Link>

                </section>

            </div>
        </div>
    </div>
    
</template>