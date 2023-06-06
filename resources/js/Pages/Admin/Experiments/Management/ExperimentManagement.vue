<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import ExperimentInformation from '@/Pages/Admin/Experiments/Management/GeneralInformation/Index.vue';
import AssociatedUsers from '@/Pages/Admin/Experiments/Management/AssociatedUsers/Index.vue';
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
    <Head title="Experimento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>

        <div class="row">
            
            <ExperimentInformation :experiment = "experiment"/>

            
        </div> 

        <div class="row">
            
            <AssociatedUsers :users = "users" :experiment_id = "experiment.id"/>

        </div> 

        <div class="row">

            <EntryPoints :entrypoints = "entrypoints" :experiment_id = "experiment.id"/>

        </div>

        <div class="row">

            <ExperimentInstances :games_instances = "games_instances" :experiment_id = "experiment.id"/>

        </div>

    </AuthenticatedLayout>
</template>