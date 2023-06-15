<script setup>

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    experiment: {
        name: '',
        description: '',
        status: null,
        time_limit: null,
        required: true
    },
})

const form = useForm({
    name: props.experiment.name,
    description: props.experiment.description,
    status: props.experiment.status,
    time_limit: props.experiment.time_limit,
});

const sendForm = () => {
    form.patch(route('experiment_information.update', {id: props.experiment.id}));
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar informacion del experimento</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                En este apartado puedes modificar la informacion general del experimento.
            </p>
        </header>

        <form @submit.prevent="sendForm()" class="mt-7">
        
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
                <InputLabel for="status" value="Estado"/>

                <select id="status" v-model="form.status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="!form.status ? form.status : ''" hidden :selected="!form.status">Elige una opción</option>
                    <option key="activo" value="activo"> Activo </option>
                    <option key="detenido" value="detenido"> Inactivo </option>
                </select>

                <InputError class="mt-2" :message="form.errors.status" />
            </div>

            <div class="mt-5">
                <InputLabel for="time_limit" value="Tiempo limite del experimento (En minutos)"/>
                
                <TextInput
                    id="time_limit"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.time_limit"
                    rows="4"
                />

                <InputError class="mt-2" :message="form.errors.description" />
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

                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>

                <SecondaryButton type="button" onclick="history.back()">Cancelar</SecondaryButton>

            </div>

        </form>
    </section>
</template>