<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    game_instance_id: {
        type: Number,
        required: true
    },
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Estadisticas</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado usted puede descargar las estadisticas de los usuarios de esta instancia.</p>
        </header>
        
        <div class="mt-4 flex items-center justify-center">
            <Link :href="route('game_instances.stats', {id: instance.id})">
                <PrimaryButton>Descargar estadisticas</PrimaryButton>
            </Link>
        </div>

    </section>
</template>