<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DeleteEntryPointForm from './Partials/DeleteEntryPointForm.vue';
import ShowInformation from './Partials/ShowInformation.vue';

const props = defineProps({
    entrypoints: {
        type: Array,
        required: true
    },
    experiment_id: {
        type: Number,
        required: true
    }
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Entrypoint</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los entrypoints del experimento.</p>
        </header>

        <div class="w-full mt-5 flex justify-center align-middle">
            <p v-if="entrypoints.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron entrypoints asociados.</p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                    <thead class="border">
                        <th>Entrypoint</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr v-for="(entrypoint, index) in entrypoints" :key="index" class="border">
                            <td class="px-3 text-center">{{ entrypoint.name }}</td>
                            <td class="px-3 text-center">{{ entrypoint.description }}</td>
                            <td class="px-3 flex gap-1 justify-center">
                                <ShowInformation :entrypoint="entrypoint"/>
                                <Link :href="route('entrypoints.edit', {id: entrypoint.id})">
                                    <PrimaryButton title="Editar"><i class="fas fa-edit"></i></PrimaryButton>
                                </Link>
                                <DeleteEntryPointForm :entrypoint_id="entrypoint.id" :entrypoint_name="entrypoint.name" class="max-w-xl" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
        
        <div class="mt-4 flex items-center justify-center">
            <Link :href="route('entrypoints.show', {id: experiment_id})">
                <PrimaryButton>Gestionar</PrimaryButton>
            </Link>
        </div>

    </section>
</template>