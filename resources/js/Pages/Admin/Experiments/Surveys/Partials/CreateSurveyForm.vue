<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import CreateQuestionForm from './CreateQuestionForm.vue';
import { useForm } from '@inertiajs/vue3';
import { formDate } from '@/helpers/date';
import { ref } from 'vue';

const props = defineProps({
    experimentId: {
        type: String
    }
})

const typesWithOptions = [
    'checkbox',
    'radio',
    'select'
];

const surveyBody = ref([]);

const form = useForm({
    name: '',
    description: '',
    type: '',
    body: surveyBody.value,
    init_date: formDate(new Date()),
    end_date: formDate(new Date()),
    experiment_id: props.experimentId,
});

const sendForm = () => {
    form.post(
        route('surveys.create'), {
            onSuccess: () => {
                form.reset();
                surveyBody.value = [];
            }
        }
    )
}

/*
    question: {
        enunciado: String, //Texto enriquecido
        type: String,
        options?: Array, //Texto enriquecido, preguntas radio
        answer?: [String, Array]
    }
*/
const addQuestion = (type) => {
    const isTest = form.type == 'test';

    surveyBody.value.push({
        title: '',
        type: '',
        ...typesWithOptions.includes(type) && {options: []},
        ...isTest && {answer: null}
    });
}

const changeType = (type) => {
    if (type == 'test')
        survey.forEach(q => {
            if(q.hasOwnProperty('answer'))
                delete q.answer;
        });

    else if(type == 'survey')
        survey.forEach(q => q.answer = null);
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crear una encuesta</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Puedes realizar encuestas a los estudiantes que participen del experimento.
                A continuación puedes ingresar las preguntas y el formato que va a tener esta encuesta,
                al momento de crear una encuesta de tipo prueba se le añadirá también el campo de respuesta correcta
                a cada pregunta.
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
                    <InputLabel for="type" value="Tipo" />
    
                    <select
                        id="type"
                        v-model="form.type"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option :value="!form.type ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                        <option value="test">Prueba</option>
                        <option value="survey">Encuesta</option>
                    </select>
    
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>
    
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
                
            </div>

            <div class="flex items-center gap-4 mt-10">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>