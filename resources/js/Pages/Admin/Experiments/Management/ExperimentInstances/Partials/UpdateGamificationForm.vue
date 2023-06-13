<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({

    experiment_id: {
        type: Number,
        required: true
    },
    game_instance: {
        type: Object,
        required: true
    }
});

const form = useForm({
    enable_rewards: props.game_instance.enable_rewards ? true : false,
    enable_badges: props.game_instance.enable_badges ? true : false,
    enable_performance_chart: props.game_instance.enable_performance_chart ? true : false,
    enable_leaderboard: props.game_instance.enable_leaderboard ? true : false
});

const sendForm = () => {
    form.patch(route('game_instances.gamification.update', {id: props.game_instance.id, slug: props.game_instance.slug}));
}
</script>


<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar informacion de la instancia de juego</h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                En este apartado puedes modificar los datos asociados a la instancia de juego.
                            </p>
                        </header>

                        <form @submit.prevent="sendForm()" class="mt-7">

                            <div class="mt-5">
                                <InputLabel for="enable_rewards">
                                    <div class="flex gap-2 items-center"> 
                                        <Checkbox 
                                            id="enable_rewards"
                                            v-model="form.enable_rewards"
                                            :checked="form.enable_rewards"
                                        /> Recompensas
                                    </div> 
                                </InputLabel>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">Las recompensas asociadas al experimento.</div>
                                <InputError class="mt-2" :message="form.errors.enable_rewards" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="enable_badges">
                                    <div class="flex gap-2 items-center"> 
                                        <Checkbox 
                                            id="enable_badges"
                                            v-model="form.enable_badges"
                                            :checked="form.enable_badges"
                                        /> Panel de medallas
                                    </div> 
                                </InputLabel>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">El panel de medallas de usuario se despliega en el dashboard del experimento.</div>

                                <InputError class="mt-2" :message="form.errors.enable_badges" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="enable_performance_chart">
                                    <div class="flex gap-2 items-center"> 
                                        <Checkbox 
                                            id="enable_performance_chart"
                                            v-model="form.enable_performance_chart"
                                            :checked="form.enable_performance_chart"
                                        /> Gráfico de rendimiento de usuario
                                    </div> 
                                </InputLabel>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">El gráfico de rendimiento de usuario presenta un gráfico con los últimos 10 puntajes en el juego.</div>
                                <InputError class="mt-2" :message="form.errors.enable_performance_chart" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="enable_leaderboard">
                                    <div class="flex gap-2 items-center"> 
                                        <Checkbox 
                                            id="enable_leaderboard"
                                            v-model="form.enable_leaderboard"
                                            :checked="form.enable_leaderboard"
                                        /> Ranking de juego
                                    </div> 
                                </InputLabel>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">El ranking de puntajes mayores se despliega bajo el juego y registra los record de puntaje.</div>
                                <InputError class="mt-2" :message="form.errors.enable_leaderboard" />
                            </div>

                            <div class="flex items-center gap-4 mt-10">

                                <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>

                                <Link :href="route('game_instances.show', {id: experiment_id})">
                                    <SecondaryButton type="button">Cancelar</SecondaryButton>
                                </Link>

                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>