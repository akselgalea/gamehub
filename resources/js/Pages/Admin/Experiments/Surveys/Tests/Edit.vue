<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateTestForm from './Partials/UpdateTestForm.vue';
import DeleteSurveyForm from '../Partials/DeleteSurveyForm.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    survey: {
        type: Object
    },
})
</script>

<template>
    <Head title="Editar una prueba" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Encuestas</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateTestForm :survey="survey" />
                </div>
                
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteSurveyForm :surveyId="survey.id" :experimentId="survey.experiment_id" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>