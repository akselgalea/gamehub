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
    gradeId: {
        type: Number,
        required: true
    },
    gradeName: {
        type: String,
        required: true
    }
});

const confirmingGradeDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.gradeId,
    name: '',
});

// Funciones
const confirmGradeDeletion = () => {
    confirmingGradeDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteGrade = () => {
    form.delete(route('schools.grades.destroy', props.gradeId), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingGradeDeletion.value = false;

    form.reset();
};
</script>

<template>
    <DangerButton @click="confirmGradeDeletion"><i class="fas fa-trash-alt"></i></DangerButton>

    <Modal :show="confirmingGradeDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este curso?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el curso sea eliminado todos los recursos e información sobre este será eliminada permanentemente.
                Ingresa <b>{{ gradeName }}</b> para confirmar que quieres eliminar permanentemente este curso.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre del curso" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre del curso"
                    @keyup.enter="deleteGrade"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteGrade"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
