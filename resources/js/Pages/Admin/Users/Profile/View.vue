<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import GeneralInformation from './Partials/Index.vue';
import ExperimentsUser from './Partials/Experiment.vue';

const props = defineProps({
    user: {
        type: Object
    },
    experiments: {
        type: Array
    },
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
                    
                    <div class="col-span-1 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <GeneralInformation :user="user"/>
                    </div>

                    <div class="col-span-2 row-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <ExperimentsUser :user_id="user.id" :experiments="experiments"/>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>