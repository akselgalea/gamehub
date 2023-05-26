<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import UpdateSchoolForm from './Partials/UpdateSchoolForm.vue';
import DeleteSchoolForm from './Partials/DeleteSchoolForm.vue';
import GradesIndex from './Grades/Index.vue';

const props = defineProps({
    school: {
        type: Object,
        required: true
    },
    grades: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Editar un colegio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Colegios</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateSchoolForm :school="school" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <GradesIndex :grades="grades" :schoolId="school.id" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteSchoolForm :schoolId="school.id" :schoolName="school.name" class="max-w-xl" />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>