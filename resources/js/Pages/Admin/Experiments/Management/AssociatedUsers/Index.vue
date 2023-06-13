<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ShowProfileInformation from '@/Pages/Profile/Partials/ShowProfileInformation.vue';

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
        
        <div class="w-full mt-5 flex justify-center align-middle">
            <p v-if="users.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron usuarios asociados.</p>
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
                            <td class="px-3 flex gap-1 justify-center">
                                <ShowProfileInformation :user="user"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
        
        <div  class="mt-4 flex items-center justify-center">
            <Link :href="route('users_experiment.edit', {id: experimentId})">
                <PrimaryButton>Gestionar</PrimaryButton>
            </Link>
        </div>
    </section>
</template>