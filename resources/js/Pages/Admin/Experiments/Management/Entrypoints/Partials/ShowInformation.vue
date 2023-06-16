<script setup>
import { copyText } from '@/Helpers/clipboard';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
const props = defineProps({
    entrypoint: {
        type: Object
    }
});

const showingModal = ref(null);

//Funciones //
const showModal = () => {
    showingModal.value = true;
}

const closeModal = () => {
    showingModal.value = false;
};

const entryLink = route('entrypoints.register', props.entrypoint.link);
</script>

<template>
    <section>
        <PrimaryButton @click="showModal()" title="Ver datos"><i class="far fa-eye"></i></PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del entrypoint</h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Esta es la informacion perteneciente al entrypoint.
                </p>
            </header>

            <div class="mt-6 space-y-6">
                <div>
                    <InputLabel for="token" value="Token" />

                    <input
                        id="token"
                        type="text"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        :value="entrypoint.token"
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="name" value="Nombre" />

                    <input
                        id="name"
                        type="text"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        :value="entrypoint.name"
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="description" value="Descripción" />

                    <input
                        id="description"
                        type="text"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        :value="entrypoint.description"
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="obfuscated" value="Obfuscado" />

                    <input
                        id="obfuscated"
                        type="text"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        :value="entrypoint.obfuscated ? 'Sí' : 'No'"
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="link" value="URL" />

                    <input
                        id="link"
                        type="text"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        :value="entryLink"
                        disabled
                    />

                </div>

                <div class="flex justify-center items-center gap-4">
                    <PrimaryButton @click="copyText(entryLink)">Copiar URL</PrimaryButton>
                    <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </div>
        </Modal>
    </section>
</template>
