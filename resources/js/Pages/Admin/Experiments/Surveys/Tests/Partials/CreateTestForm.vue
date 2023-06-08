<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import CreateTestQuestion from './CreateTestQuestion.vue';
import { useForm } from '@inertiajs/vue3';
import { formDate } from '@/Helpers/date';
import { ref } from 'vue';
import { noti } from '@/Helpers/notifications';

const props = defineProps({
    experimentId: {
        type: String
    }
});

const showingModal = ref(false);
const surveyBody = ref([]);

const form = useForm({
    name: '',
    description: '',
    type: 'survey',
    body: null,
    init_date: formDate(new Date()),
    end_date: formDate(new Date()),
    experiment_id: props.experimentId,
});

const sendForm = () => {
    form.body = JSON.stringify(surveyBody.value);

    form.post(
        route('surveys.store', {id: props.experimentId}), {
            onSuccess: () => {
                form.reset();
                surveyBody.value = [];
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

const showModal = () => {
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
};

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
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crear una prueba</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Puedes realizar encuestas de tipo prueba a los estudiantes que participen del experimento.
                A continuación puedes ingresar las preguntas y el formato que va a tener esta encuesta.
                Al ser una prueba, todas las preguntas tendrán también una respuesta correcta.
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
    
                        <CreateTestQuestion @cancel="closeModal" @addQuestion="addQuestion($event)" />
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