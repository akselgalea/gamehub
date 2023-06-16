<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    experiment_id: {
        type: Number,
        required: true
    },
    experiment_name: {
        type: String,
        required: true
    }
});

const confirmingExperimentDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.experiment_id,
    name: '',
});

// Funciones //
const confirmExperimentDeletion = () => {
    confirmingExperimentDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteExperiment = () => {
    form.delete(route('experiment.destroy', props.experiment_id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingExperimentDeletion.value = false;

    form.reset();
};
</script>

<template>

    <DangerButton @click="confirmExperimentDeletion" title="Eliminar"><i class="fas fa-trash"></i></DangerButton>

    <Modal :show="confirmingExperimentDeletion" @close="closeModal">
        <Head title="Eliminar experimento" />

        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este experimento?
            </h2>


            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el experimento sea eliminado todos los recursos e información sobre este será eliminada permanentemente.
                Ingresa <b>{{ experiment_name }}</b> para confirmar que quieres eliminar permanentemente esta instancia.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre de la instancia de juego" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre de la instancia de juego"
                    @keyup.enter="deleteExperiment"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteExperiment"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
