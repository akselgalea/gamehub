<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { onBeforeMount, ref } from 'vue';

const schools =ref(null);
const grades = ref(null);

const form = useForm({
    name: '',
    email: '',
    type: null,
    grade_id: null,
    password: '',
    password_confirmation: '',
    terms: false,
});

const getSchools = () => {
    axios.get(route('api.schools.index')).then(({data}) => {
        schools.value = data;

    }, error => {
        console.log(error);
    })
}

const setGrades = (e) => {
    const index = e.target.value - 1;
    grades.value = schools.value[index].grades;
    form.grade_id = null;
}

const sendForm = () => {
    form.post(
        route('user.store'), {
            onSuccess: () => {
                form.reset();
            }
        }
    )
}

onBeforeMount(() => {
    getSchools();
})

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

                <select id="type" v-model="form.type" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option :value="form.type ==null ? form.type : ''" hidden :selected="!form.type">Elige una opción</option>
                    <option value="student"> Estudiante </option>
                    <option value="admin"> Administrador </option>
                </select>

                <!-- <InputError class="mt-2" :message="form.errors.obfuscated" /> -->
            </div>
            
            <div v-if="form.type == 'student'" class="mt-5">
                
            <div>
                <InputLabel for="school" value="Colegio" />

                <select
                    id="school"
                    v-model="selectedSchool"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    @change="setGrades($event)"   
                >
                    <option :value="!selectedSchool ? selectedSchool : ''" hidden :selected="!selectedSchool">Elige una opción</option>
                    <option v-for="s in schools" :value="s.id" :key="s.id">{{ s.name }}</option>
                </select>
                <InputError v-if = "form.errors.grade_id && selectedSchool == null" 
                    class="mt-2" message=" El colegio es obligatorio en caso de que el usuario sea estudiante" 
                />
            </div>

            <div v-if="grades">
                <InputLabel for="grade" value="Curso" class="mt-5"/>

                <select
                    v-if="grades.length > 0"
                    id="grade"
                    v-model="form.grade_id"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                >
                    <option :value="!form.grade_id ? form.grade_id : ''" hidden :selected="!form.grade_id">Elige una opción</option>
                    <option v-for="g in grades" :value="g.id" :key="g.id">{{ g.name }}</option>
                </select>
                
                <p v-else class="mt-1 text-sm text-gray-600 dark:text-gray-400">Este colegio aun no posee cursos.</p>

                <InputError class="mt-2" :message="form.errors.grade_id" />
            </div>
            
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
