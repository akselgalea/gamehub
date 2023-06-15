<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import {noti} from '@/helpers/notifications';
import ShowProfileInformation from '@/Pages/Profile/Partials/ShowProfileInformation.vue';

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

const form = useForm({
    user_id: null,
    experiment_id: props.experiment_id
});

const asocciateForm = (user_id) => {
    form.user_id = user_id,
    form.post(
        route('user_experiment.link'),{
            onSuccess: () => {
                noti('success', 'Usuario vinculado con éxito!', 'top-center');
                form.reset();
            }
        }
    )
}

const disasocciateForm = (user_id) => {
    form.user_id = user_id,
    form.post(
        route('user_experiment.unlink'),{
            onSuccess: () => {
                noti('success', 'Usuario desvinculado con éxito!', 'top-center');
                form.reset();
            }
        }
    )
}
</script>

<template>
    <Head title="Usuarios del experimento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimentos / Usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Vincular usuarios</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden modificar los usuarios asociados al experimento.</p>
                    </header>

                    <div class="grid grid-cols-2 gap-10 w-full">
                        <div class="mt-5 w-full max-md:col-span-2">
                            <header class="mb-2">
                                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 leading-tight">Usuarios asociados:</h3>
                            </header>

                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="associatedUsers.length == 0">No se encontraron usuarios asociados.</p>

                            <template v-else>
                                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                    <thead class="border dark:text-white">
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody class="dark:text-white">
                                        <tr v-for="(user, index) in associatedUsers" :key="index" class="border">
                                            <td class="px-3 text-center">{{ user.name }}</td>
                                            <td class="px-3 text-center">{{ user.email }}</td>
                                            <td class="px-3 flex gap-1 justify-center">
                                                
                                                <DangerButton @click="disasocciateForm(user.id)" title="Desvincular"><i class="fas fa-user-minus"></i></DangerButton>
                                                <ShowProfileInformation :user="user"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>

                        <div class="mt-5 w-full max-md:col-span-2">
                            <header class="mb-2">
                                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 leading-tight">Usuarios no asociados:</h3>
                            </header>

                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="noAssociatedUsers.length == 0">No se encontraron usuarios asociados.</p>

                            <template v-else>
                                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                    <thead class="border dark:text-white">
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody class="dark:text-white">
                                        <tr v-for="(user, index) in noAssociatedUsers" :key="index" class="border">
                                            <td class="px-3 text-center">{{ user.name }}</td>
                                            <td class="px-3 text-center">{{ user.email }}</td>
                                            <td class="px-3 flex gap-1 justify-center">
                                                
                                                <PrimaryButton @click="asocciateForm(user.id)" title="Vincular"><i class="fas fa-user-plus"></i></PrimaryButton>
                                                <ShowProfileInformation :user="user"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-center w-full">
                    <Link class="ml-2" :href="route('experiment.management', {id: experiment_id})">
                        <PrimaryButton>Volver</PrimaryButton>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>        
</template>