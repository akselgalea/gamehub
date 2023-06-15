<script setup>

import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

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

</script>

<template>
    <section>
        <PrimaryButton @click="showModal()" title="Ver datos"><i class="far fa-eye"></i></PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <Head title="Datos entrypoint" />
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

                    <TextInput
                        id="token"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="entrypoint.token"
                        required
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="name" value="Nombre" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="entrypoint.name"
                        required
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="description" value="Descripcion" />

                    <TextInput
                        id="description"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="entrypoint.description"
                        required
                        disabled
                    />

                </div>

                <div v-if="entrypoint.obfuscated == 1">
                    <InputLabel for="obfuscated" value="Obfuscado" />

                    <TextInput
                        id="obfuscated"
                        type="text"
                        class="mt-1 block w-full"
                        value="si"
                        required
                        disabled
                    />

                </div>

                <div v-else>
                    <InputLabel for="obfuscated" value="Obfuscado" />

                    <TextInput
                        id="obfuscated"
                        type="text"
                        class="mt-1 block w-full"
                        value="no"
                        required
                        disabled
                    />

                </div>

                <div class="flex items-center gap-4">
                    <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </div>
        </Modal>
    </section>
</template>
