<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    schools: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Todos los establecimientos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Colegios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Todos los colegios</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver el listado de todos los colegios inscritos en la plataforma.</p>
                    </header>

                    <section class="mt-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="schools.length == 0">No se encontraron colegios.</p>
                        
                        <div class="flex flex-wrap gap-10 w-full">
                            <div v-for="(school, index) in schools" :key="index">
                                <div class="first-letter:uppercase text-xl font-bold text-gray-600 dark:text-gray-400">{{ school.name }}</div>
                                
                                <div class="flex gap-2">
                                    <Link :href="route('schools.edit', {slug: school.slug})">
                                        <PrimaryButton>Gestionar</PrimaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>