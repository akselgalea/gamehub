<script setup>
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

defineProps({
    categories: {
        type: Array
    }
})

const form = useForm({
    name: '',
    description: '',
    file: null,
    category_id: null,
    user_id: user.id,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Subir un juego</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Crea un juego nuevo, para hacer esto debes subir una carpeta comprimida .zip que contenga dentro un juego HTML5,
                dentro de esta carpeta debe haber una carpeta llamada html5game y dentro de ella sus archivos.
            </p>
        </header>

        <form @submit.prevent="form.post(route('games.create'))" class="mt-7">
            <div>
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
                <InputLabel for="category" value="Categoria"/>

                <select id="category" v-model="form.category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="!form.category_id ? form.category_id : ''" hidden :selected="!form.category_id">Elige una opcion</option>
                    <option v-for="(cat, index) in categories" :key="index" :value="cat.id">{{ cat.name }}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.category_id" />
            </div>

            <div class="mt-5">
                <InputLabel for="description" value="Descripcion"/>
                
                <TextArea
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-5">
                <InputLabel for="file" value="Archivo"/>
                
                <input type="file" class="text-white" @input="form.file = $event.target.files[0]" required />

                <InputError class="mt-2" :message="form.errors.file" />
            </div>

            <div class="flex items-center gap-4 mt-10">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>