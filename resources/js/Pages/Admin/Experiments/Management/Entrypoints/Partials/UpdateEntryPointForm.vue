<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({

    experiment_id: {
        type: Number,
        required: true
    },
    entrypoint: {
        type: Object,
        required: true
    }
});

const form = useForm({
    token: props.entrypoint.token,
    name: props.entrypoint.name,
    description: props.entrypoint.description,
    obfuscated: props.entrypoint.obfuscated,
});

// Funciones //
const sendForm = () => {
    form.patch(route('entrypoints.update', {id: props.entrypoint.id}));
}
</script>


<template>
    <AuthenticatedLayout>
        <Head title="Editar entrypoint" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Experimento</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar informacion del entrypoint</h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                En este apartado puedes modificar la informacion general del entrypoint.
                            </p>
                        </header>

                        <form @submit.prevent="sendForm()" class="mt-7">
                        
                            <div class="mt-5">
                                <InputLabel for="token" value="Token"/>
                                
                                <TextInput 
                                    id="token"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.token"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.token" />
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

                            <div class="mt-5">
                                <InputLabel for="obfuscated">
                                    <div class="flex gap-2 items-center"> 
                                        <Checkbox 
                                            id="obfuscated"
                                            v-model="form.obfuscated"
                                            :checked="form.obfuscated"
                                        /> Obfuscado
                                    </div> 
                                </InputLabel>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">Encripta el token de la URL</div>
                                <InputError class="mt-2" :message="form.errors.obfuscated" />
                            </div>

                            <div class="mt-5">
                                <InputLabel for="entrylink" value="URL:"/>
                                <input 
                                    id="url"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    :value="route('entrypoints.register', {token: entrypoint.link})"
                                    disabled
                                />
                            </div>

                            <div class="flex items-center gap-4 mt-10">
                                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                                <Link :href="route('entrypoints.show', {id: experiment_id})">
                                    <PrimaryButton>Volver</PrimaryButton>
                                </Link>
                            </div>

                            <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="mt-2 transition ease-in-out">
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                            </Transition>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>