<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import CreateSurveyQuestion from './CreateSurveyQuestion.vue';
import UpdateSurveyQuestion from './UpdateSurveyQuestion.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { noti } from '@/Helpers/notifications';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    survey: {
        type: Object
    }
});

const surveyBody = ref(JSON.parse(props.survey.body));

const form = useForm({
    name: props.survey.name,
    description: props.survey.name,
    stage: props.survey.stage,
    type: 'survey',
    body: props.survey.body,
    init_date: props.survey.init_date,
    end_date: props.survey.end_date,
    experiment_id: props.survey.experiment_id
});

const sendForm = () => {
    form.body = JSON.stringify(surveyBody.value);

    form.post(
        route('surveys.update', {id: props.survey.experiment_id, survey: props.survey.id}), {
            onSuccess: () => {
                noti('success', 'Prueba actualizada con éxito!', 'top-center');
            },
            onError: (e) => {
                console.log(e);
            }
        }
    )
}

const addQuestion = (question) => {
    surveyBody.value.push(question);
}

const updateQuestion = (question, index) => {
    surveyBody.value[index] = question;
    noti('success', 'Pregunta actualizada con éxito!', 'top-center');
}

const deleteQuestion = (index) => {
    if(confirm('¿Estás seguro que deseas eliminar esta pregunta?')) {
        surveyBody.value = surveyBody.value.filter((item, idx) => idx != index);
        noti('success', 'Pregunta eliminada con éxito!', 'top-center');
    }
}

const cancel = () => {
    form.reset();
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Actualizar encuesta</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Puedes realizar encuestas los estudiantes que participen del experimento.
                A continuación puedes actualizar las preguntas que va a tener esta encuesta.
            </p>
        </header>

        <form @submit.prevent="sendForm()" class="mt-7">
            <div class="mt-5">
                <InputLabel for="name" value="Nombre" />
                
                <TextInput 
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-5 grid grid-cols-3 gap-4">
                <div class="w-full">
                    <InputLabel for="init_date" value="Fecha de inicio" />
                    <input 
                        type="date"
                        name="init_date"
                        v-model="form.init_date"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <InputError class="mt-2" :message="form.errors.init_date" />
                </div>
    
                <div class="w-full">
                    <InputLabel for="end_date" value="Fecha de fin" />
                    <input 
                        type="date"
                        name="end_date" 
                        v-model="form.end_date"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <InputError class="mt-2" :message="form.errors.end_date" />
                </div>

                <div class="w-full">
                    <InputLabel for="etapa" value="Etapa" />
                    <select 
                        name="etapa"
                        v-model="form.stage" 
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option :value="!form.stage ? form.stage : ''" hidden :selected="!form.stage">Elige una opción</option>
                        <option value="pre">Pre juego</option>
                        <option value="post">Post juego</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.stage" />
                </div>
            </div>

            <div class="mt-5">
                <InputLabel for="description" value="Descripcion" />
                
                <TextArea
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    rows="4"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-5">
                <CreateSurveyQuestion @addQuestion="addQuestion($event)" />
            </div>

            <div class="mt-5">
                <p class="text-sm text-gray-600 dark:text-white" v-if="surveyBody.length == 0">
                    Esta encuesta aun no posee preguntas.
                </p>
                <template v-else>
                    <table class="rounded-sm shadow table-fixed w-full border-collapse text-gray-900 dark:text-white">
                        <thead class="border">
                            <th>Enunciado</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            <tr class="border" v-for="(question, index) in surveyBody" :key="index">
                                <td class="px-3 text-justify">{{ question.question }}</td>
                                <td class="px-3 text-justify">{{ question.type == 'open' ? 'Pregunta abierta' : 'Opción múltiple' }}</td>
                                <td class="px-3 flex gap-1 justify-center">
                                    <UpdateSurveyQuestion :question="question" @update-question="updateQuestion($event, index)" :key="index"/>
                                    <DangerButton type="button" @click="deleteQuestion(index)"><i class="fas fa-trash-alt"></i></DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </div>

            <div class="flex items-center gap-4 mt-10">
                <SecondaryButton type="button" :disabled="form.processing" @click="cancel">Cancelar</SecondaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>