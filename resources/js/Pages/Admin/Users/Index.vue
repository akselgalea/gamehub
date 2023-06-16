<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EditUser from '@/Pages/Admin/Users/Edit.vue';
import DeleteUserModal from './Partials/DeleteUserModal.vue';
import ShowProfileInformation from '@/Pages/Profile/Partials/ShowProfileInformation.vue';
import UsersImport from './Partials/UsersImport.vue';

const props = defineProps({
    users: {
        type: Array,
        required: true
    }
})

</script>

<template>
    <Head title="Todos los usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Usuarios registrados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden ver usuarios registrados en la plataforma.</p>
                    </header>
                    
                    <div class="mt-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="users.length == 0">No se encontraron usuarios.</p>
                        
                        <template v-else>
                            <table class="rounded-sm shadow table-fixed w-full border-collapse">
                                <thead class="border">
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in users" :key="index" class="border">
                                        <td class="px-3 text-center">{{ user.name }}</td>
                                        <td class="px-3 text-center">{{ user.email }}</td>
                                        <td class="px-3 text-center">
                                            <div class="flex gap-1 justify-center">
                                                <ShowProfileInformation :user="user"/>
                                                <EditUser :user="user"/>
                                                <Link :href="route('user-profile.index', {id: user.id})">
                                                    <PrimaryButton title="Ver Perfil"><i class="fas fa-user-alt"></i></PrimaryButton>
                                                </Link>
                                                <DeleteUserModal :userId="user.id"/>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </div>
 
                    <div class="mt-5 flex items-center justify-center gap-2">
                        <Link :href="route('user.create')">
                            <PrimaryButton>Crear nuevo usuario</PrimaryButton>
                        </Link>

                        <UsersImport/>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>