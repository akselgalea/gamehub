<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    user_id: {
        type: Number,
        required: true
    }
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Estadisticas</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se pueden observar las estadisticas de rendimiento del usuario.</p>
        </header>

        <div class="w-full mt-5 flex justify-center align-middle">
            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">Proximamente.....</p>
            
        </div>
    </section>
</template>