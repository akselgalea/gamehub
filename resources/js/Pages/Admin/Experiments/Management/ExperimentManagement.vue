<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import ExperimentInformation from '@/Pages/Admin/Experiments/Management/GeneralInformation/Index.vue';

const props = defineProps({
    experiment: {
        type: Object,
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

    </AuthenticatedLayout>
</template>