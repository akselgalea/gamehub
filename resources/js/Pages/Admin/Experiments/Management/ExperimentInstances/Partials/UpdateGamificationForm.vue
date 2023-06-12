<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

const user = usePage().props.auth.user;

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
    form.patch(route('instances_gamification.update', {id: props.game_instance.id}));
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

                                <InputError class="mt-2" :message="form.errors.enable_rewards" />
                            </div>

                            <InputLabel for="enable_badges">
                                <div class="flex gap-2 items-center"> 
                                    <Checkbox 
                                        id="enable_badges"
                                        v-model="form.enable_badges"
                                        :checked="form.enable_badges"
                                    /> Medallas
                                </div> 
                            </InputLabel>

                            <InputLabel for="enable_performance_chart">
                                <div class="flex gap-2 items-center"> 
                                    <Checkbox 
                                        id="enable_performance_chart"
                                        v-model="form.enable_performance_chart"
                                        :checked="form.enable_performance_chart"
                                    /> Rendimiento
                                </div> 
                            </InputLabel>

                            <InputLabel for="enable_leaderboard">
                                <div class="flex gap-2 items-center"> 
                                    <Checkbox 
                                        id="enable_leaderboard"
                                        v-model="form.enable_leaderboard"
                                        :checked="form.enable_leaderboard"
                                    /> Ranking
                                </div> 
                            </InputLabel>

                            <div class="flex items-center gap-4 mt-10">

                                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                                </Transition>

                                <Link :href="route('game_instances.show', {id: experiment_id})">
                                    <PrimaryButton>Volver</PrimaryButton>
                                </Link>

                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>