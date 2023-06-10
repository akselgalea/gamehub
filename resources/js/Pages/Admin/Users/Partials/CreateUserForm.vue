<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    grades: {
        type: Array
    },
    schools: {
        type: Array
    }
});

const form = useForm({
    name: '',
    email: '',
    type: null,
    grade_id: null,
    password: '',
    password_confirmation: '',
    terms: false,
});

const sendForm = () => {
    form.post(
        route('user.store'), {
            onSuccess: () => {
                form.reset();
            }
        }
    )
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crear Usuario</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                En este apartado se pueden crear usuarios que puedan acceder a la plataforma.
            </p>
        </header>

        <form @submit.prevent="sendForm()" class="mt-7">
            <div>
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-5">
                <InputLabel for="type" value="Tipo de usuario"/>

                <select id="type" v-model="form.type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="form.type ==null ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                    <option value="student"> Estudiante </option>
                    <option value="admin"> Administrador </option>
                </select>

                <!-- <InputError class="mt-2" :message="form.errors.obfuscated" /> -->
            </div>
            
            <div v-if="form.type == 'student'" class="mt-5">
                <InputLabel for="grade" value="Grado"/>

                    <select v-if="grades.length == 0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" disabled>
                    <option selected> No hay grados registrados, dirijase a colegios si desea crear uno. </option>
                    </select>

                    <select v-else id="grade" v-model="form.grade_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option :value="!form.grade_id ? form.grade_id : ''" hidden :selected="!form.grade_id">Elige una opción</option>
                        <option v-for="grade in grades" :key="grade.id" :value="grade.id">{{ grade.name }}</option>
                    </select>

                <InputError class="mt-2" :message="form.errors.category_id" />
            </div>

            <div class="mt-5">
                <InputLabel for="email" value="Correo" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-5">
                <InputLabel for="password" value="Contraseña" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-5">
                <InputLabel for="password_confirmation" value="Confirmar Contraseña" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4 mt-10">

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Crear usuario
                </PrimaryButton>

                <Link :href="route('users_panel.index')">
                    <PrimaryButton> Volver </PrimaryButton>
                 </link>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Creado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
