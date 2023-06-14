<script setup>

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    game: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
})

const form = useForm({
    name: props.game.name,
    description: props.game.description,
    category_id: props.game.category_id,
});

const sendForm = () => {
    form.patch(route('games.update', {slug: props.game.slug}));
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar un juego</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Al editar un juego, no puedes sustituir el juego existente. Si quieres subir otro juego deberas crear otro.
            </p>
        </header>

        <form @submit.prevent="sendForm()" class="mt-7">
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
                    <option :value="!form.category_id ? form.category_id : ''" hidden :selected="!form.category_id">Elige una opción</option>
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
                    rows="4"
                />

                <InputError class="mt-2" :message="form.errors.description" />
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