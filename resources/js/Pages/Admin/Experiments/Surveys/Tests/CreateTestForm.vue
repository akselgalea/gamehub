<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import CreateSurveyQuestion from './CreateSurveyQuestion.vue';
import { useForm } from '@inertiajs/vue3';
import { formDate } from '@/Helpers/date';
import { ref } from 'vue';

const props = defineProps({
    experimentId: {
        type: String
    }
});

const typesWithOptions = [
    'checkbox',
    'radio',
    'select'
];

const showingModal = ref(false);
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
        route('surveys.store', {id: props.experimentId}), {
            onSuccess: () => {
                form.reset();
                surveyBody.value = [];
            }
        }
    )
}

/*
    question: {
        question: String, //Texto enriquecido
        type: String,
        options?: Array, //Texto enriquecido, preguntas radio
        answer?: [String, Array]
    }
*/
const addQuestion = (question) => {
    surveyBody.value.push(question);
    console.log(surveyBody.value);
}

const showModal = () => {
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
};

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

            <div class="mt-5 grid grid-cols-2 gap-4">
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
                <PrimaryButton type="button" @click="showModal">Agregar pregunta</PrimaryButton>
    
                <Modal :show="showingModal" @close="closeModal">
                    <div class="p-6">
                        <h2 class="text-lg mb-3 font-medium text-gray-900 dark:text-gray-100">
                            Agregar pregunta
                        </h2>
    
                        <CreateSurveyQuestion @cancel="closeModal" @addQuestion="addQuestion($event)" />
                    </div>
                </Modal>
            </div>

            <div v-for="question in surveyBody">
                <pre>{{ question }}</pre>
            </div>

            <div class="flex items-center gap-4 mt-10">
                <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>