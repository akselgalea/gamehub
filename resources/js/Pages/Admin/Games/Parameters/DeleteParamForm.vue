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
    paramId: {
        type: Number,
        required: true
    },
    paramName: {
        type: String,
        required: true
    },
});

const confirmingParamDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.paramId,
    name: '',
});

// Funciones
const confirmParamDeletion = () => {
    confirmingParamDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteParam = () => {
    form.delete(route('games.params.destroy', props.paramId), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingParamDeletion.value = false;

    form.reset();
};
</script>

<template>
    <DangerButton @click="confirmParamDeletion"><i class="fas fa-trash-alt"></i></DangerButton>

    <Modal :show="confirmingParamDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este parámetro?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el parámetro sea eliminado todos los recursos e información sobre este serán eliminados permanentemente.
                Ingresa <b>{{ paramName }}</b> para confirmar que quieres eliminar permanentemente este parámetro.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre del parámetro" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre del parámetro"
                    @keyup.enter="deleteParam"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteParam"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
