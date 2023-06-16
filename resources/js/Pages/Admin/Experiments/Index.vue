<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

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
                    /> <span class="mt-1 font-semibold text-sm text-gray-600 dark:text-white">Ver todos los experimentos</span>
                    </div>
                    
                    <div class="w-full mt-8 flex justify-center align-middle">
                        <p v-if="experiments.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron entrypoints asociados.</p>
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
                                                <PrimaryButton @click="showModal" title="Ver detalles"><i class="far fa-eye"></i></PrimaryButton>
                                                <Link :href="route('experiment.management', {id: experiment.id})">
                                                    <PrimaryButton title="Gestionar"><i class="fas fa-cog"></i></PrimaryButton>
                                                </Link>
                                                
                                                <Modal :show="showingModal" @close="closeModal">
                                                    <div class="py-6">
                                                        
                                                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                                                            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                                                                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Informacion General</h2>
                                                                <div class="mt-5">
                                                                        <div class="first-letter:uppercase text-grey-900 dark:text-white">Experimento: {{ experiment.name }}</div>
                                                                        <div class="first-letter:uppercase text-grey-900 dark:text-white">Estado: {{ experiment.status }}</div>
                                                                        <div class="first-letter:uppercase text-grey-900 dark:text-white">Descripcion: {{ experiment.description }}</div>
                                                                        <div class="first-letter:uppercase text-grey-900 dark:text-white">Tiempo limite: {{ experiment.time_limit }} minutos</div>
                                                                        
                                                                    </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-5 flex justify-center">
                                                            <SecondaryButton @click="closeModal"> Cerrar </SecondaryButton>
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

                    <!-- <section class="mt-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="experiments.length == 0">No se encontraron experimentos.</p>

                        <div class="flex flex-wrap gap-10 w-full">
                            <div v-for="(experiment, index) in experiments" :key="index">
                                <div class="first-letter:uppercase text-gray-900  dark:text-white">Experimento: {{ experiment.name }}</div>
                                <div class="first-letter:uppercase text-gray-900 dark:text-white">Estado: {{ experiment.status }}</div>

                                <div class="flex gap-2">
                                    
                                    <PrimaryButton @click="showModal">Ver detalles</PrimaryButton>
                                    <Link :href="route('experiment.management', {id: experiment.id})">
                                        <PrimaryButton>Gestionar</PrimaryButton>
                                    </Link>
                                    <Modal :show="showingModal" @close="closeModal">
                                        <div class="py-12">
                                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                                                <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                                                    <section class="mt-5">
                                                            <div class="first-letter:uppercase text-grey-900 dark:text-white">Experimento: {{ experiment.name }}</div>
                                                            <div class="first-letter:uppercase text-grey-900 dark:text-white">Estado: {{ experiment.status }}</div>
                                                            <div class="first-letter:uppercase text-grey-900 dark:text-white">Descripcion: {{ experiment.description }}</div>
                                                            <div class="first-letter:uppercase text-grey-900 dark:text-white">Tiempo limite: {{ experiment.time_limit }} minutos</div>
                                                            <div class="flex gap-2">
                                                                <SecondaryButton @click="closeModal"> Cerrar </SecondaryButton>
                                                            </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </Modal>
                                </div>

                                
                            </div>
                        </div>
                    </section> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>