<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    experiment_id: {
        type: String,
        required: true
    },
    parameters: {
        type: Array,
        required: true
    },
    instance_id:{
        type: Number,
        required: true
    }
});

const form = useForm({
   parameters: props.parameters,
});

const sendForm = () => {
    form.patch(
        route('game_instances.params.update', {id: props.experiment_id, slug: instance_id}), {
            onSuccess: () => {
            }    
        }
    );
}

const types = {
    'int': 'Numero entero',
    'float': 'Numero decimal',
    'string': 'Cadena de texto',
    'boolean': 'Valor de verdad'
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
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar valores de los parametros</h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                En este apartado puedes modificar los valores de los parametros de la instancia.
                            </p>
                        </header>

                        <section class="mt-5 w-full">
                            <p class="text-sm text-gray-600 dark:text-gray-400" v-if="parameters.length == 0">No se encontraron parametros del juegos asociados.</p>
                            
                            <template v-else>
                                <form @submit.prevent="sendForm()" class="mt-7">
                                    <table class="rounded-sm shadow table-fixed w-full border-collapse text-white">
                                        <thead class="border">
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Tipo de dato</th>
                                            <th>Valor</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(param, index) in form.parameters" :key="param.id" class="border">
                                                <td><InputLabel class="px-3 text-center">{{ param.name }}</InputLabel></td>
                                                <td><InputLabel class="px-3 text-center">{{ param.description }}</InputLabel></td>
                                                <td><InputLabel class="px-3 text-center" :value="types[param.type]" /></td>
                                                <td>
                                                    <TextInput 
                                                        v-if="param.type == 'int'"
                                                        id="value"
                                                        type="number"
                                                        class="mt-1 block w-full text-center"
                                                        v-model="param.value"
                                                        rows="4"
                                                    />

                                                    <TextInput 
                                                        v-if="param.type == 'float'"
                                                        id="value"
                                                        type="number"
                                                        step="0.01"
                                                        class="mt-1 block w-full text-center"
                                                        v-model="param.value"
                                                        rows="4"
                                                    />

                                                    <TextInput 
                                                        v-if="param.type == 'string'"
                                                        id="value"
                                                        type="string"
                                                        class="mt-1 block w-full text-center"
                                                        v-model="param.value"
                                                        rows="4"
                                                    />

                                                    <select v-if="param.type == 'boolean'" id="value" v-model="param.value" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                                        <option v-if="param.value === null" :value="null" hidden :selected="!param.value === null" class="flex text-center">Elige una opción</option>
                                                        <option :value="1" class="text-center"> Si </option>
                                                        <option :value="0" class="text-center"> No </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-5 flex gap-1 justify-center">

                                        <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>

                                        <Link :href="route('game_instances.show', {id: experiment_id})">
                                            <SecondaryButton type="button">Cancelar</SecondaryButton>
                                        </Link>

                                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                                        </Transition>
                                    </div>
                                </form>
                            </template>

                            <div v-if="parameters.length == 0" class="mt-5 flex gap-1 justify-center">
                                <Link :href="route('game_instances.show', {id: experiment_id})">
                                    <SecondaryButton type="button">Volver</SecondaryButton>
                                </Link>
                            </div>
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>