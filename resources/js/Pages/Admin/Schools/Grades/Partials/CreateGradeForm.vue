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
    schoolId: {
        type: Number,
        required: true
    }
});

const showingModal = ref(false);
const nameInput = ref(null);

const form = useForm({
    name: '',
    school_id: props.schoolId
});

const showModal = () => {
    showingModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const createGrade = () => {
    form.post(route('schools.grades.store'), {
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
    <PrimaryButton @click="showModal">Agregar curso</PrimaryButton>

    <Modal :show="showingModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg mb-3 font-medium text-gray-900 dark:text-gray-100">
                Agregar curso
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
                    @keyup.enter="createGrade"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createGrade"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
