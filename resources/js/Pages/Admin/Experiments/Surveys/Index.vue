<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    experimentId: {
        type: Number,
        required: true
    },
    surveys: {
        type: Array,
        required: true
    },
});

const types = {
    'test': 'prueba',
    'survey': 'encuesta'
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Encuestas del experimento</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Puedes realizar encuestas a los estudiantes que participen del experimento.
                A continuación las encuestas que ya existen para este experimento.
            </p>
        </header>

        <div class="mt-5 w-full">
            <div class="mb-5 flex gap-2">
                <Link :href="route('surveys.create', {id: experimentId})">
                    <PrimaryButton>Nueva encuesta</PrimaryButton>
                </Link>
    
                <Link :href="route('surveys.tests.create', {id: experimentId})">
                    <PrimaryButton>Nueva prueba</PrimaryButton>
                </Link>
            </div>

            <p class="text-sm text-gray-600 dark:text-white" v-if="surveys.length == 0">
                Este experimento aun no posee encuestas.
            </p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse text-gray-900 dark:text-white">
                    <thead class="border">
                        <th>Nombre</th>
                        <th>Tipo de encuesta</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr class="border" v-for="(survey, index) in surveys" :key="index">
                            <td class="px-3 text-justify">{{ survey.name }}</td>
                            <td class="px-3 text-justify">{{ types[survey.type] }}</td>
                            <td class="px-3 text-justify">{{ survey.description }}</td>
                            <td class="px-3 flex gap-1 justify-center">
                                <!-- <EditSurveyForm :survey="survey" :key="survey.name" />
                                <DeleteSurveyForm :surveyId="survey.id" :surveyName="survey.name" :key="survey.id" /> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>

        <div class="flex items-center gap-4 mt-10">
            <!-- <NewSurveyForm :game="game" /> -->
        </div>
    </section>
</template>