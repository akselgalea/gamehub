<script setup>
import NewParamForm from '../Parameters/NewParamForm.vue';
import EditParamForm from '../Parameters/EditParamForm.vue';
import DeleteParamForm from '../Parameters/DeleteParamForm.vue';

const props = defineProps({
    game: {
        type: Object,
        required: true
    },
    parameters: {
        type: Array,
        required: true
    },
})

const types = {
    'int': 'Número',
    'float': 'Número flotante',
    'string': 'Cadena de caracteres',
    'boolean': 'Verdadero o falso'
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Parametros del juego</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Modificar los parametros de lanzamiento del juego cambia la conducta de este. A continuacion puedes modificar los parametros de este juego.
            </p>
        </header>

        <div class="mt-5 flex flex-wrap gap-10 w-full">
            <p class="text-sm text-gray-600 dark:text-white" v-if="parameters.length == 0">
                Este juego aun no posee parametros de lanzamiento.
            </p>
            <template v-else>
                <table class="rounded-sm shadow table-fixed w-full border-collapse">
                    <thead class="border dark:text-gray-100">
                        <th>Nombre</th>
                        <th>Tipo de dato</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr v-for="(param, index) in parameters" :key="index" class="border dark:text-gray-200">
                            <td class="px-3 text-center">{{ param.name }}</td>
                            <td class="px-3 text-center">{{ types[param.type] }}</td>
                            <td class="px-3 text-center">{{ param.description }}</td>
                            <td class="px-3">
                                <div class="flex gap-1 h-full justify-center items-center">
                                    <EditParamForm :param="param" :game-slug="game.slug" :key="param.name" />
                                    <DeleteParamForm :param-id="param.id" :param-name="param.name" :game-slug="game.slug" :key="param.id" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>

        <div class="flex items-center gap-4 mt-10">
            <NewParamForm :game-slug="game.slug" :game-id="game.id" />
        </div>
    </section>
</template>