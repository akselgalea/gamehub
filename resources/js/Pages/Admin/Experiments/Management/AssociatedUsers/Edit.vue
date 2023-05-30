<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    experiment_id: {
        type: Number,
        required: true
    },
    associatedUsers: {
        type: Array,
        required: true
    },
    noAssociatedUsers:{
        type: Array,
        required: true
    }
})

</script>

<template>
    <Head title="Usuarios del experiment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Vincular usuarios</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar los usuarios asociados al experimento.</p>
                    </header>

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div class="flex flex-wrap gap-10 w-full">

                                    <section class="mt-5"> Usuarios no asociados :
                                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="noAssociatedUsers.length == 0">No se encontraron mas usuarios.</p>
                                            <div class="flex flex-wrap gap-10 w-full md:w-1/2">
                                                <div v-for="(user, index) in noAssociatedUsers" :key="index">
                                                <div class="first-letter:uppercase">Nombre: {{ user.name }}</div>
                                                <div class="first-letter:uppercase">Correo: {{ user.email }}</div>
                                                    <!-- <div class="flex gap-2">
                                                        <Link :href="route('experiment_information.edit', {id: user.id})">
                                                            <PrimaryButton>Editar</PrimaryButton>
                                                        </Link>
                                                    </div> -->
                                                </div>
                                            </div>
                                    </section>

                                    <section class="mt-5"> Usuarios asociados :
                                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="associatedUsers.length == 0">No se encontraron usuarios asociados.</p>

                                            <div class="flex flex-wrap gap-10 w-full md:w-1/2">
                                                <div v-for="(user, index) in associatedUsers" :key="index">
                                                    <div class="w-full md:w-1/2">
                                                    <div class="first-letter:uppercase">Nombre: {{ user.name }}</div>
                                                    <div class="first-letter:uppercase">Correo: {{ user.email }}</div>
                                                        <!-- <div class="flex gap-2">
                                                            <Link :href="route('experiment_information.edit', {id: user.id})">
                                                                <PrimaryButton>Editar</PrimaryButton>
                                                            </Link>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                
                                            </div>
                                    </section>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>        
</template>