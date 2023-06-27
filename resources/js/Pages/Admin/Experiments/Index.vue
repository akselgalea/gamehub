<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import DeleteExperimentForm from './Partials/DeleteExperimentForm.vue';

const props = defineProps({
    experiments: {
        type: Array,
        required: true
    }
})

const showingModal = ref(false);

const showModal = () => {
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
};

const showAll = ref(false);

</script>

<template>
    <Head title="Todos los experimentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimentos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Experimentos registrados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden ver los experimentos registrados en la plataforma.</p>
                    </header>
                    
                    <div class="mt-8">
                    <Checkbox
                        v-model="showAll"
                        :checked="false"
                    /> <span class="mt-1 font-semibold text-sm text-gray-600 dark:text-white">Ver todos los experimentos</span>
                    </div>
                    
                    <div class="w-full mt-8 flex justify-center align-middle">
                        <p v-if="experiments.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron experimentos.</p>
                        <template v-else>
                            <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                <thead class="border">
                                    <th>Experimentos</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(experiment, index) in experiments" :key="index" class="border">
                                        <template v-if ="experiment.status === 'activo' || showAll ">
                                            <td class="px-3 text-center">{{ experiment.name }}</td>
                                            <td class="px-3 text-center">{{ experiment.description }}</td>
                                            <td class="px-3 text-center">{{ experiment.status }}</td>
                                            <td class="px-3 flex gap-1 justify-center">
                                                <PrimaryButton @click="showModal" title="Ver detalles"><i class="fas fa-eye"></i></PrimaryButton>
                                                <Link :href="route('experiment_information.edit', {id: experiment.id})">
                                                    <PrimaryButton title="Editar"><i class="fas fa-edit"></i></PrimaryButton>
                                                </Link>
                                                <Link :href="route('experiment.management', {id: experiment.id})">
                                                    <PrimaryButton title="Gestionar"><i class="fas fa-cog"></i></PrimaryButton>
                                                </Link>

                                                <DeleteExperimentForm :experiment_id="experiment.id" :experiment_name="experiment.name" />

                                                <Modal :show="showingModal" @close="closeModal">
                                                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                                    <header>
                                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del experimento</h2>
                                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                            Esta es la informacion perteneciente al experimento.
                                                        </p>
                                                    </header>

                                                    <div class="mt-6 space-y-6">
                                                        <div>
                                                            <InputLabel for="name" value="Nombre" />
                                                            <InputLabel
                                                                id="name"
                                                                type="text"
                                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                                :value="experiment.name"
                                                                disabled
                                                            />
                                                        </div>
                                                        <div>
                                                            <InputLabel for="status" value="Estado" />
                                                            <InputLabel
                                                                id="status"
                                                                type="text"
                                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                                :value="experiment.status"
                                                                disabled
                                                            />
                                                        </div>
                                                        <div>
                                                            <InputLabel for="description" value="Descripcion" />
                                                            <InputLabel
                                                                id="description"
                                                                type="text"
                                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                                :value="experiment.description"
                                                                disabled
                                                            />
                                                        </div>
                                                        <div>
                                                            <InputLabel for="time_limit" value="Tiempo limite (min)" />
                                                            <InputLabel
                                                                id="time_limit"
                                                                type="text"
                                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                                :value="experiment.time_limit"
                                                                disabled
                                                            />
                                                        </div>
                                                        <div class="flex justify-center items-center gap-4">
                                                            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                                                        </div>
                                                    </div>
                                                </div>
                                                </Modal>
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>