<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import GeneralInformation from './Partials/Index.vue';
import ExperimentsUser from './Partials/Experiment.vue';
import StatisticsUser from './Partials/Statistics.vue';
import GamesUser from './Partials/Games.vue';

const props = defineProps({
    user: {
        type: Object
    },
    experiments: {
        type: Array
    },
    games: {
        type: Array
    },
    categoryes: {
        type: Array
    }
})
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Perfil del usuario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="grid grid-cols-6 grid-rows-1 gap-4">
                    
                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <GeneralInformation :user="user"/>
                    </div>

                    <div class="col-span-4 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <GamesUser :user_id="user.id" :games="games" :experiments = "experiments" :categoryes = "categoryes"/>
                    </div>

                    <div class="col-span-3 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <ExperimentsUser :user_id="user.id" :experiments="experiments"/>
                    </div>

                    <div class="col-span-3 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <StatisticsUser :user_id="user.id"/>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-center">
                    <PrimaryButton type="button" onclick="history.back()">Volver</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>