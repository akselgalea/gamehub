<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import AssociatedUsers from '@/Pages/Admin/Experiments/Management/AssociatedUsers/Index.vue';
import AssociatedSurveys from './Surveys/Index.vue';
import EntryPoints from '@/Pages/Admin/Experiments/Management/Entrypoints/Index.vue';
import ExperimentInstances from '@/Pages/Admin/Experiments/Management/ExperimentInstances/Index.vue';

const props = defineProps({
    experiment: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    entrypoints: {
        type: Array,
        required: true
    },
    surveys: {
        type: Array,
        required: true
    },
    games_instances: {
        type: Array,
        required: true
    },
    games: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <Head title="Experimentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimentos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="grid grid-cols-3 grid-rows-2 gap-4">
                    <div class="col-span-1 row-span-4 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informacion general</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver la informacion del experimento.</p>
                        </header>
        
                        <section class="mt-5">
                            <div class="flex flex-wrap gap-10 w-full text-gray-900 dark:text-gray-200">
                                <div class="first-letter:uppercase">Experimento: {{ experiment.name }}</div>
                                <div class="first-letter:uppercase">Estado: {{ experiment.status }}</div>
                                <div class="first-letter:uppercase">Descripcion: {{ experiment.description }}</div>
                                <div class="first-letter:uppercase">Tiempo limite: {{ experiment.time_limit }}</div>
                                    <div class="flex gap-2">
                                        <Link :href="route('experiment_information.edit', {id: experiment.id})">
                                            <PrimaryButton>Editar</PrimaryButton>
                                        </Link>
                                    </div>
                            </div>
                        </section>
                    </div>
                    
                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <AssociatedUsers :users="users" :experimentId="experiment.id" />
                    </div>

                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <AssociatedSurveys :experimentId="experiment.id" :surveys="surveys" />
                    </div>
                    
                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <ExperimentInstances :games_instances = "games_instances" :experiment_id = "experiment.id"/>
                    </div>

                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <EntryPoints :entrypoints = "entrypoints" :experiment_id = "experiment.id"/>
                    </div>

                    

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>