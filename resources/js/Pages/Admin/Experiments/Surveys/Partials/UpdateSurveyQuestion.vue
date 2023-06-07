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
    minText: props.question.minText ?? '',
    maxText: props.question.maxText ?? ''
});

const updateQuestion = () => {
    form.post(
        route('api.surveys.survey_question.create'), {
            onSuccess: () => {
                const question = onlyNecessaryFields(form.data());
                emit('updateQuestion', question);
                closeModal();
            },
            onError: () => {
                noti('error', 'Datos invalidos', 'top-center');
            }
        }
    );
}

const onlyNecessaryFields = (data) => {
    if(data.type == 'open')
        return { question: data.question, type: data.type }

    return data;
}

const showModal = () => {
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
};
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
    
                    <select id="type" v-model="form.type" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option :value="!form.type ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                        <option value="likert">Likert</option>
                        <option value="open">Pregunta abierta</option>
                    </select>
    
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>
    
                <div class="mt-5">
                    <InputLabel for="question" value="Enunciado de la pregunta" />
                    <TextInput class="w-full" type="text" name="question" v-model="form.question" />
                    <InputError class="mt-2" :message="form.errors.question" />
                </div>
    
                <template v-if="form.type == 'likert'">
                    <div class="mt-5">
                        <InputLabel for="minText" value="Valor mínimo"/>
                        <TextInput class="w-full" type="text" name="minText" v-model="form.minText" placeholder="Totalmente en desacuerdo" required />
                        <InputError class="mt-2" :message="form.errors.minText" />
                    </div>
    
                    <div class="mt-5">
                        <InputLabel for="minText" value="Valor máximo"/>
                        <TextInput class="w-full" type="text" name="maxText" v-model="form.maxText" placeholder="Totalmente de acuerdo" required />
                        <InputError class="mt-2" :message="form.errors.maxText" />
                    </div>
                </template>
    
                <div class="mt-10">
                    <SecondaryButton type="button" @click="closeModal"> Cancelar </SecondaryButton>
                    <PrimaryButton
                        type="submit"
                        class="ml-3"
                    >
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>