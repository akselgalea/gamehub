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
    gameId: {
        type: Number,
        required: true
    },
    gameName: {
        type: String,
        required: true
    }
});

const confirmingGameDeletion = ref(false);
const confirmingInput = ref(null);

const form = useForm({
    id: props.gameId,
    name: '',
});

// Funciones
const confirmGameDeletion = () => {
    confirmingGameDeletion.value = true;

    nextTick(() => confirmingInput.value.focus());
};

const deleteGame = () => {
    form.delete(route('games.destroy', props.gameId), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => confirmingInput.value.focus(),
    });
};

const closeModal = () => {
    confirmingGameDeletion.value = false;

    form.reset();
};
</script>

<template>
    <header class="">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eliminar juego</h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Una vez el juego sea eliminado, todos los recursos e información sobre este será eliminada permanentemente.
            Antes de eliminar el juego, por favor asegurate de estar 100% seguro de este procedimiento.
        </p>
    </header>

    <DangerButton class="mt-6" @click="confirmGameDeletion">Eliminar juego</DangerButton>

    <Modal :show="confirmingGameDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Estás seguro de que quieres eliminar este juego?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez el juego sea eliminado todos los recursos e información sobre este será eliminada permanentemente.
                Ingresa <b>{{ gameName }}</b> para confirmar que quieres eliminar permanentemente este juego.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre del juego" class="sr-only" />

                <TextInput
                    id="name"
                    ref="confirmingInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Nombre del juego"
                    @keyup.enter="deleteGame"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteGame"
                >
                    Eliminar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
