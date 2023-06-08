<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    users: {
        type: Array,
        required: true
    },
    experimentId: {
        type: Number,
        required: true
    }
    
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Usuarios asociados</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los usuarios asociados experimento.</p>
        </header>
            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="users.length == 0">No se encontraron usuarios asociados.</p>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Usuarios asociados</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los usuarios asociados experimento.</p>
                    </header>

                    <div class="mt-5">
                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="users.length == 0">No se encontraron usuarios asociados.</p>

                            <div class="flex flex-wrap gap-10 w-full">
                                <div v-for="(user, index) in users" :key="index">
                                <div class="first-letter:uppercase">Usuario: {{ user.name }}</div>
                                <div class="first-letter:uppercase">Correo: {{ user.email }}</div>
                            </div>

                        </div>
                    </div>

                    <div  class="mt-4 flex items-center justify-center">

                        <Link :href="route('users_experiment.edit', {id: experimentId})">
                            <PrimaryButton>Editar</PrimaryButton>
                        </Link>
                        
                    </div>
                </div>
            </div>
        </div>

    </section>
</template>