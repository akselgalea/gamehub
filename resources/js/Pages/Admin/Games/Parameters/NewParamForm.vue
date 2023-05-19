<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const props = defineProps({
    game: {
        type: Number,
        required: true
    }
});

const types = {
    'int': 'Numero',
    'float': 'Numero flotante',
    'string': 'Cadena de caracteres',
    'boolean': 'Verdadero o falso'
}

const showingModal = ref(false);
const nameInput = ref(null);

const form = useForm({
    name: '',
    type: '',
    description: '',
    game_id: props.game
});

const showModal = () => {
    showingModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const createParam = () => {
    form.post(route('games.params.store'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    showingModal.value = false;

    form.reset();
};
</script>

<template>
    <PrimaryButton @click="showModal">Agregar parámetro</PrimaryButton>

    <Modal :show="showingModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg mb-3 font-medium text-gray-900 dark:text-gray-100">
                Agregar parámetro
            </h2>

            <div>
                <InputLabel for="name" value="Nombre"/>
                
                <TextInput 
                    id="name"
                    ref="nameInput"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-5">
                <InputLabel for="type" value="Tipo"/>

                <select id="type" v-model="form.type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="!form.type ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                    <option v-for="[type, text] in Object.entries(types)" :value="type">{{ text }}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.type" />
            </div>

            <div class="mt-5">
                <InputLabel for="description" value="Descripcion"/>
                
                <TextArea
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    rows="4"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createParam"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
