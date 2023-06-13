<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CreateGradeForm from './Partials/CreateGradeForm.vue';
import DeleteGradeForm from './Partials/DeleteGradeForm.vue';
import UpdateGradeForm from './Partials/UpdateGradeForm.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    grades: {
        type: Array,
        required: true
    },
    schoolId: {
        type: Number,
        required: true
    },
    schoolSlug: {
        type: String,
        required: true
    }
});
</script>

<template>
    <main>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Cursos</h2>
            <p class="text-gray-600 dark:text-white">Listado de todos los cursos</p>
        </header>
        
        <p class="text-sm text-gray-600 dark:text-white" v-if="grades.length == 0">
            Este colegio aun no posee cursos.
        </p>

        <table class="mt-10 rounded-sm shadow table-auto w-full border-collapse" v-else>
            <thead class="dark:text-gray-100 border">
                <th>Curso</th>
                <th>Estudiantes</th>
                <th>Gestionar curso</th>
            </thead>
            <tbody class="dark:text-white">
                <tr v-for="(grade, index) in grades" class="border">
                    <td class="text-center">{{grade.name}}</td>
                    <td>
                        <div class="flex justify-center">
                            <Link :href="route('schools.grades.get', {school: schoolSlug, grade: grade.slug})">
                                <PrimaryButton><i class="fas fa-users"></i></PrimaryButton>
                            </Link>
                        </div>
                    </td>
                    <td>
                        <div class="flex justify-center gap-1">
                            <UpdateGradeForm :grade="grade" :key="grade.id" />
                            <DeleteGradeForm :gradeId="grade.id" :gradeName="grade.name" :key="index" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-5">
            <CreateGradeForm :schoolId="schoolId" />
        </div>
    </main>
</template>