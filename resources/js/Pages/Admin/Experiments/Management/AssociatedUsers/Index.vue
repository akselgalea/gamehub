<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    users: {
        type: Array,
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

            <div class="flex flex-wrap gap-10 w-full">
                <div v-for="(user, index) in users" :key="index">
                <div class="first-letter:uppercase">Usuario: {{ user.name }}</div>
                <div class="first-letter:uppercase">Correo: {{ user.email }}</div>
                    <div class="flex gap-2">
                        <Link :href="route('experiment_information.edit', {id: user.id})">
                            <PrimaryButton>Editar</PrimaryButton>
                        </Link>
                    </div>
            </div>
        </div>

    </section>
</template>