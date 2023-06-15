<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminUserEdit from '@/Pages/Admin/Users/Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import ShowProfileInformation from '@/Pages/Profile/Partials/ShowProfileInformation.vue';

const props = defineProps({
    grade: {
        type: Object,
        required: true
    },
    school: {
        type: Object,
        required: true
    },
    students: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Colegios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cursos</h2>
        </template>

        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <header>
                            <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">{{ grade.name }}</h2>
                            <p class="text-gray-600 dark:text-white">Listado de estudiantes del curso</p>
                        </header>
                        
                        <p class="mt-10 text-sm text-gray-600 dark:text-white" v-if="students.length == 0">
                            Este curso aun no posee estudiantes.
                        </p>
    
                        <table class="mt-10 rounded-sm shadow table-auto w-full border-collapse" v-else>
                            <thead class="text-gray-900 dark:text-gray-100 border">
                                <th>Nombre</th>
                                <th>Perfil</th>
                            </thead>
                            <tbody class="text-gray-600 dark:text-white">
                                <tr v-for="(student, index) in students" class="border">
                                    <td class="text-center">{{student.name}}</td>
                                    <td>
                                        <div class="flex justify-center gap-1">
                                            <ShowProfileInformation :user="student"/>
                                            <Link :href="route('user-profile.index', {id: student.id})">
                                                <PrimaryButton title="Ver Perfil"><i class="fas fa-user-alt"></i></PrimaryButton>
                                            </Link>
                                            <AdminUserEdit :user="student" :key="index" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>