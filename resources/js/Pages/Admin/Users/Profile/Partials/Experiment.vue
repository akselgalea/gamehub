<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    experiments: {
        type: Array,
        required: true
    },
    user_id: {
        type: Number,
        required: true
    }
})

</script>

<template>
    <Head title="Entrypoint" />
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Experimentos</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los experimentos a los que pertenece el usuario.</p>
        </header>

        <div class="w-full mt-5 flex justify-center align-middle">
            <p v-if="experiments.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron experimentos asociados.</p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                    <thead class="border">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        <tr v-for="(experiment, index) in experiments" :key="index" class="border">
                            <td class="px-3 text-center">{{ experiment.name }}</td>
                            <td class="px-3 text-center">{{ experiment.description }}</td>
                            <td class="px-3 text-center">{{ experiment.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
    </section>
</template>