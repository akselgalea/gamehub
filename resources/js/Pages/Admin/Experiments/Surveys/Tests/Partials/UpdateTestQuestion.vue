<script setup>
import { useForm } from '@inertiajs/vue3';
import { noti } from '@/Helpers/notifications';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';

const emit = defineEmits(['updateQuestion']);
const props = defineProps({
    question: {
        type: Object,
        required: true,
    }
});

const showingModal = ref(false);

const form = useForm({
    question: props.question.question,
    type: props.question.type,
    options: props.question.options,
    answer: props.question.answer
});

const updateQuestion = () => {
    form.post(
        route('api.surveys.test_question.create'), {
            onSuccess: () => {
                const question = onlyNecessaryFields(form.data());
                emit('updateQuestion', question);
                showingModal.value = false;
            },
            onError: () => {
                noti('error', 'Datos invalidos', 'top-center');
            }
        }
    );
}

const onlyNecessaryFields = (data) => {
    if(data.type == 'open')
        return { question: data.question, type: data.type, answer: data.answer }

    return data;
}

const showModal = () => {
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const initOptions = () => {
    form.options = ['', '', '', ''];
}

const typeChange = () => {
    if (form.type == 'multi')
        initOptions();
    else
        form.options = null;
        form.answer = '';
}
</script>

<template>
    <PrimaryButton type="button" @click="showModal"><i class="fas fa-edit"></i></PrimaryButton>
    
    <Modal :show="showingModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg mb-3 font-medium text-gray-900 dark:text-gray-100">
                Editar pregunta
            </h2>

            <form @submit.prevent="updateQuestion">
                <div>
                    <InputLabel for="type" value="Tipo"/>

                    <select id="type" v-model="form.type" @change="typeChange" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option :value="!form.type ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                        <option value="multi">Selección múltiple</option>
                        <option value="open">Pregunta abierta</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div class="mt-5">
                    <InputLabel for="question" value="Enunciado de la pregunta" />
                    <TextInput class="w-full" type="text" name="question" v-model="form.question" />
                    <InputError class="mt-2" :message="form.errors.question" />
                </div>

                <template v-if="form.type == 'multi'">
                    <div class="mt-5" v-for="(option, index) in form.options">
                        <InputLabel for="option[]" :value="`Opción ${index + 1}`"/>
                        <TextInput class="w-full" type="text" name="option[]" v-model="form.options[index]" :placeholder="`Opción ${index + 1}`" />
                        <InputError class="mt-2" :message="form.errors?.['options.' + index]" />
                    </div>

                    <div class="mt-5">
                        <InputLabel for="answer" value="Respuesta"/>

                        <select id="answer" v-model="form.answer" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option :value="!form.answer ? form.answer : ''" hidden :selected="!form.answer">Elige una opción</option>
                            <option value="0">Opción 1</option>z
                            <option value="1">Opción 2</option>
                            <option value="2">Opción 3</option>
                            <option value="3">Opción 4</option>
                        </select>

                        <InputError class="mt-2" :message="form.errors.answer" />
                    </div>
                </template>

                <div v-if="form.type == 'open'" class="mt-5">
                    <InputLabel for="answer" value="Respuesta" />
                    <TextInput class="w-full" type="text" name="answer" v-model="form.answer" placeholder="Respuesta correcta" />
                </div>

                <div class="mt-10">
                    <SecondaryButton type="button" @click="closeModal"> Cancelar </SecondaryButton>
                    <PrimaryButton
                        class="ml-3"
                        type="submit"
                    >
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>