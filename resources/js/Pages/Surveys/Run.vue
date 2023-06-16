<script setup>
import {Head} from '@inertiajs/vue3';
import Likert from './Partials/Likert.vue';
import OpenQuestion from './Partials/OpenQuestion.vue';
import { useForm, usePage } from '@inertiajs/vue3';

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
const answers = props.survey.body.map((question) => {
    question.answer = '';
    return question;
});

const user = usePage().props.auth.user;

const form = useForm({
    body: answers,
    survey_id: props.survey.id,
    user_id: user.id,
});

const sendForm = () => {
    console.log('activated');
}

/* 
  tipos {
    likert: 'likert',
    open: 'Pregunta abierta'
  }
*/
</script>

<template>
    <Head title="Encuestas" />
    <div v-for="(question, index) in survey.body" :key="index">
        <Likert v-if="question.type == 'likert'" v-model="form.body[index].answer" :question="question.question" :min-text="question.minText" :max-text="question.maxText" />
        <OpenQuestion v-if="question.type == 'open'" v-model="form.body[index].answer" :index="index" />
    </div>
</template>