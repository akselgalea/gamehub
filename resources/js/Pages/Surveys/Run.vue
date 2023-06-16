<script setup>
import {Head, router} from '@inertiajs/vue3';
import Likert from './Partials/Likert.vue';
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
    question.answer = '';
    return question;
});

const user = usePage().props.auth.user;

const form = useForm({
    stats: 'finished',
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
    likert: 'likert',
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

        <main class="py-12 flex justify-center max-w-7xl mx-auto">
            <div class="p-6 bg-white rounded-md">
                <div v-for="(question, index) in body" :key="index">
                    <Likert 
                        v-if="question.type == 'likert'" 
                        v-model="form.body[index].answer" 
                        :question="question.question" 
                        :min-text="question.minText" 
                        :max-text="question.maxText"
                        :index="index"
                    />

                    <OpenQuestion v-if="question.type == 'open'" v-model="form.body[index].answer" :question="question.question" :index="index" />
                </div>

                <div class="mt-10">
                    <SecondaryButton class="w-full" @click="sendForm()">Guardar respuesta</SecondaryButton>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style>
.jello {
	-webkit-animation: jello-vertical 0.9s both;
	        animation: jello-vertical 0.9s both;
}

 @-webkit-keyframes jello {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
  }
  30% {
    -webkit-transform: scale3d(0.75, 1.25, 1);
            transform: scale3d(0.75, 1.25, 1);
  }
  40% {
    -webkit-transform: scale3d(1.25, 0.75, 1);
            transform: scale3d(1.25, 0.75, 1);
  }
  50% {
    -webkit-transform: scale3d(0.85, 1.15, 1);
            transform: scale3d(0.85, 1.15, 1);
  }
  65% {
    -webkit-transform: scale3d(1.05, 0.95, 1);
            transform: scale3d(1.05, 0.95, 1);
  }
  75% {
    -webkit-transform: scale3d(0.95, 1.05, 1);
            transform: scale3d(0.95, 1.05, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
  }
}
@keyframes jello {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
  }
  30% {
    -webkit-transform: scale3d(0.75, 1.25, 1);
            transform: scale3d(0.75, 1.25, 1);
  }
  40% {
    -webkit-transform: scale3d(1.25, 0.75, 1);
            transform: scale3d(1.25, 0.75, 1);
  }
  50% {
    -webkit-transform: scale3d(0.85, 1.15, 1);
            transform: scale3d(0.85, 1.15, 1);
  }
  65% {
    -webkit-transform: scale3d(1.05, 0.95, 1);
            transform: scale3d(1.05, 0.95, 1);
  }
  75% {
    -webkit-transform: scale3d(0.95, 1.05, 1);
            transform: scale3d(0.95, 1.05, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
  }
}

</style>