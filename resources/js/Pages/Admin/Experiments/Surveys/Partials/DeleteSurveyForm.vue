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
    surveyId: {
        type: Number,
        required: true
    },
    experimentId: {
        type: Number,
        required: true
    }
});

const confirmingSurveyDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    surveyId: props.surveyId,
    password: ''
});

const confirmSurveyDeletion = () => {
    confirmingSurveyDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteSurvey = () => {
    form.delete(route('surveys.destroy', { id: props.experimentId, survey: props.surveyId }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingSurveyDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eliminar encuesta</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Una vez eliminada la encuesta, todos los recursos e información de esta van a ser eliminados permanentemente.
                Antes de eliminar la encuesta, porfavor respalda cualquier información que desees mantener.
            </p>
        </header>

        <DangerButton @click="confirmSurveyDeletion">Eliminar encuesta</DangerButton>

        <Modal :show="confirmingSurveyDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Estás seguro de que deseas eliminar la encuesta?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminada la encuesta, todos los recursos e información de esta va a ser eliminada permanentemente. Por favor
                    ingresa tu contraseña para confirmar que quieres eliminar la encuesta.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Contraseña"
                        @keyup.enter="deleteSurvey"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteSurvey"
                    >
                        Eliminar encuesta
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
