<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { noti } from '@/Helpers/notifications';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['addQuestion', 'cancel']);

const form = useForm({
    question: '',
    type: null,
    options: [],
    answer: null,
});

const initOptions = () => {
    form.options = ['', '', '', ''];
}

const typeChange = () => {
    if (form.type == 'multi')
        initOptions();
    else
        form.options = null;
}

const sendQuestion = () => {
    form.post(
        route('api.surveys.test_question.create'), {
            onSuccess: () => {
                const question = onlyNecessaryFields(form.data());
                form.question = '';
                emit('addQuestion', question);
                noti('success', 'Pregunta agregada correctamente', 'top-center');
            },
            onError: () => {
                console.log(form.errors);
                noti('error', 'Datos inválidos', 'top-center');
            }
        }
    );
}

const onlyNecessaryFields = (data) => {
    if(data.type == 'open')
        return { question: data.question, type: data.type }

    return data;
}
</script>

<template>
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
            <TextInput class="w-full" type="text" name="option[]" v-model="form.options[index]" :placeholder="`Opción ${index + 1}`" required />
            <InputError class="mt-2" :message="form.errors.options" />
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

    <div v-else class="mt-5">
        <InputLabel for="answer" value="Respuesta" />
        <TextInput class="w-full" type="text" name="answer" v-model="form.answer" placeholder="Respuesta correcta" />
    </div>

    <div class="mt-10">
        <SecondaryButton @click="$emit('cancel')"> Cancelar </SecondaryButton>
        <PrimaryButton
            class="ml-3"
            @click="sendQuestion"
        >
            Añadir pregunta
        </PrimaryButton>
    </div>
</template>