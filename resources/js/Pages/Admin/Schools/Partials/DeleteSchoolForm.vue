
<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const props = defineProps({
    schoolId: {
        type: Number,
        required: true
    },
    schoolName: {
        type: String,
        required: true
    }
});

const confirmingSchoolDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.schoolId,
    name: '',
});

// Funciones
const confirmSchoolDeletion = () => {
    confirmingSchoolDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteSchool = () => {
    form.delete(route('schools.destroy', props.schoolId), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingSchoolDeletion.value = false;

    form.reset();
};
</script>

<template>
    <header class="">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eliminar colegio</h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Una vez el colegio sea eliminado, todos los recursos e información sobre este será eliminada permanentemente.
            Antes de eliminar el colegio, por favor asegurate de estar 100% seguro de este procedimiento.
        </p>
    </header>

    <DangerButton class="mt-6" @click="confirmSchoolDeletion">Eliminar colegio</DangerButton>

    <Modal :show="confirmingSchoolDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este colegio?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el colegio sea eliminado todos los recursos e información sobre este será eliminada permanentemente.
                Ingresa <b>{{ schoolName }}</b> para confirmar que quieres eliminar permanentemente este colegio.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre del colegio" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre del colegio"
                    @keyup.enter="deleteSchool"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteSchool"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
