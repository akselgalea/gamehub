<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    games: {
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
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Juegos</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar los juegos del usuario.</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">(NA-> No esta asociado a un experimento)</p>
        </header>

        <div class="w-full mt-5 flex justify-center align-middle">
            <p v-if="games.length == 0" class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">No se encontraron juegos asociados.</p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                    <thead class="border">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Instancia</th>
                    </thead>
                    <tbody>
                        <tr v-for="(game, index) in games" :key="index" class="border">
                            <td class="px-3 text-center">{{ game.name }}</td>
                            <td class="px-3 text-center">{{ game.description }}</td>
                            <template v-if="game.instance_slug">
                                <td class="px-3 text-center">{{ game.instance_slug }}</td>
                            </template>
                            <template v-else>
                                <td class="px-3 text-center"> NA</td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
    </section>
</template>