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
    entrypoint_id: {
        type: Number,
        required: true
    },
    entrypoint_name: {
        type: String,
        required: true
    }
});

const confirmingEntrypointDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.entrypoint_id,
    name: '',
});

// Funciones
const confirmEntrypointDeletion = () => {
    confirmingEntrypointDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteEntrypoint = () => {
    form.delete(route('entrypoints.destroy', props.entrypoint_id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingEntrypointDeletion.value = false;

    form.reset();
};
</script>

<template>

    <DangerButton @click="confirmEntrypointDeletion">Eliminar</DangerButton>

    <Modal :show="confirmingEntrypointDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este entrypoint?
            </h2>


            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el entrypoint sea eliminado todos los recursos e información sobre este será eliminada permanentemente.
                Ingresa <b>{{ entrypoint_name }}</b> para confirmar que quieres eliminar permanentemente este entrypoint.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre del entrypoint" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre del entrypoint"
                    @keyup.enter="deleteEntrypoint"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteEntrypoint"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
