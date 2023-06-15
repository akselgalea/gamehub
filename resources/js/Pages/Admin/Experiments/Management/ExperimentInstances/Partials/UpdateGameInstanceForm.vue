<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    experiment_id: {
        type: String,
        required: true
    },
    games: {
        type: Array,
        required: true
    },
    game_instance: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name: props.game_instance.name,
    description: props.game_instance.description,
    game_id: props.game_instance.game_id,
});

// Funciones //
const sendForm = () => {
    form.patch(route('game_instances.update', {id: props.experiment_id, slug: props.game_instance.slug}));
}

</script>


<template>
    <AuthenticatedLayout>
        <Head title="Editar informacion de instancia" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento / Instancia</h2>
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
                            <div>
                                <InputLabel for="game" value="Juego"/>

                                <select id="game" v-model="form.game_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option :value="!form.game_id ? form.game_id : ''" hidden :selected="!form.game_id">Elige una opción</option>
                                    <option v-for="(game, index) in games" :key="index" :value="game.id">{{ game.name }}</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.game_id" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="name" value="Nombre"/>
                                
                                <TextInput 
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="description" value="Descripcion"/>
                                
                                <TextArea
                                    id="description"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    rows="4"
                                />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="flex items-center gap-4 mt-10">

                                <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>

                                <SecondaryButton type="button" onclick="history.back()">Cancelar</SecondaryButton>
                                
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