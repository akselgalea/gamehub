<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm} from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
});

const showingModal = ref(null);

const showModal = () => {
    showingModal.value = true;
}

const closeModal = () => {
    showingModal.value = false;
};

const form = useForm({
    user_file: null,
});

const sendForm = () => {
    form.post(
        route('upload-users.create'), {
            onSuccess: () => {
                closeModal();
            }
        }
    )
}

</script>

<template>
    <section>
        <PrimaryButton @click="showModal" title="Ver datos">Carga varios usuarios</PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Subir archivo excel con usuarios</h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Recuerde subir un archivo con el formato indicado en el manual.
                    </p>
                </header>

                <form @submit.prevent="sendForm()" class="mt-7">
                    <div class="mt-5">
                        <InputLabel for="file" value="Archivo"/>
                        
                        <input type="file" class="dark:text-white" @input="form.user_file = $event.target.files[0]" required />

                        <InputError class="mt-2" :message="form.errors.file" />
                    </div>
                    
                    <div class="mt-5 flex items-center gap-4">
                        <PrimaryButton class="submit" :disabled="form.processing">Subir</PrimaryButton>

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                        </Transition>

                        <SecondaryButton class="button" @click="closeModal">Cerrar</SecondaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </section>
</template>
