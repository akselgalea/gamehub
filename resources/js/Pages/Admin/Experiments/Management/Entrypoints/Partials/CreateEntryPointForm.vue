<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

const user = usePage().props.auth.user;

const props = defineProps({
    experiment_id: {
        type: Number,
        required: true
    }
});

const form = useForm({
    token: '',
    name: '',
    description: '',
    obfuscated: null,
    experiment_id: experiment_id,
});

const sendForm = () => {
    form.post(
        route('entrypoints.create'), {
            onSuccess: () => {
                form.reset();
                form.file = null;
            }
        }
    )
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crear entrypoint</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Para hacer esto debes completar el siguiente formulario.
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
                <InputLabel for="obfuscated" value="Obfuscado"/>

                <select id="obfuscated" v-model="form.obfuscated" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="!form.obfuscated ? form.obfuscated : ''" hidden :selected="!form.obfuscated">Elige una opción</option>
                    <option :key="true" :value="true"> Si </option>
                    <option :key="false" :value="false"> No </option>
                </select>

                <InputError class="mt-2" :message="form.errors.obfuscated" />
            </div>

            <div class="flex items-center gap-4 mt-10">
                <PrimaryButton :disabled="form.processing">Crear</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Creado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>