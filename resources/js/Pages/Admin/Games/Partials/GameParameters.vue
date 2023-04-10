<script setup>
import NewParamForm from './Parameters/NewParamForm.vue';

const props = defineProps({
    game: {
        type: Number,
        required: true
    },
    parameters: {
        type: Array,
        required: true
    },
})

const types = {
    'int': 'Numero',
    'float': 'Numero flotante',
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

        <div class="mt-5">
            <p class="text-sm text-gray-600 dark:text-white" v-if="parameters.length == 0">
                Este juego aun no posee parametros de lanzamiento.
            </p>
            <template v-else>
                <div v-for="(param, index) in parameters" :key="index">
                    <div>
                        <div>{{ param.name }}</div>
                        <div>{{ types[param.type] }}</div>
                        <div>{{ param.description }}</div>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center gap-4 mt-10">
            <NewParamForm :game="game" />
        </div>
    </section>
</template>