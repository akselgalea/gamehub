<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EditUser from '@/Pages/Admin/Users/Edit.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';

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
                    
                    <section class="mt-5">
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
                                            <div>
                                                <EditUser :user="user"/>
                                                <!-- <DeleteUserForm :userId="user.id" class="max-w-xl" /> -->
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>

                        <!-- <div class="flex flex-wrap gap-10 w-full text-gray-600 dark:text-white">
                            <div v-for="(user, index) in users" :key="index">
                                <div class="first-letter:uppercase">{{ user.name }}</div>
                                <div class="first-letter:uppercase">{{ user.email }}</div>

                                <EditUser :user="user"/>
                            </div>
                            
                        </div> -->

                    </section>
 
                    <section class="mt-5 flex items-center justify-center">
                        <Link :href="route('user.create')">
                                <PrimaryButton>Crear nuevo usuario</PrimaryButton>
                            </Link>
                    </section>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>