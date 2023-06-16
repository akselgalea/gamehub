<script setup>
import {Head, router} from '@inertiajs/vue3';
import MultiOptionQuestion from './Partials/MultiOptionQuestion.vue';
import OpenQuestion from './Partials/OpenQuestion.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { noti } from '@/Helpers/notifications';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    experimentId: {
        type: String,
        required: true
    }, 
    survey: {
        type: Object,
        required: true
    }
});

// Añade el campo respuesta a las preguntas del cuestionario
const body = JSON.parse(props.survey.body);
const answers = body.map((question) => {
    question.player_answer = '';
    return question;
});

const user = usePage().props.auth.user;

const form = useForm({
    status: 'finished',
    body: answers,
    survey_id: props.survey.id,
    user_id: user.id,
});

const sendForm = () => {
    form.body = JSON.stringify(form.body);
    form.post(route('surveys.response.store', {id: props.experimentId, survey: props.survey.id}), {
        onSuccess: () => {
            form.reset();
            noti('success', 'Respuesta enviada con éxito!', 'top-center');
            router.get(route('game_instances.select_instance', props.experimentId));
        }
    })
}

/* 
  tipos {
    multi: 'seleccion multiple',
    open: 'Pregunta abierta'
  }
*/
</script>

<template>
    <Head title="Encuestas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Encuestas</h2>
        </template>

        <main class="py-12 flex justify-center max-w-4xl mx-auto">
            <div class="p-6 w-full bg-white rounded-md">
                <div v-for="(question, index) in body" :key="index">
                    <MultiOptionQuestion 
                        v-if="question.type == 'multi'" 
                        v-model="form.body[index].player_answer" 
                        :question="question.question"
                        :options="question.options"
                        :index="index"
                    />

                    <OpenQuestion v-if="question.type == 'open'" v-model="form.body[index].player_answer" :question="question.question" :index="index" />
                </div>

                <div class="mt-10">
                    <SecondaryButton class="w-full" @click="sendForm()">Guardar respuesta</SecondaryButton>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>