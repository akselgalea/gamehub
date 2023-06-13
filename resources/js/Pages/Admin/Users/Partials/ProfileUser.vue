<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onBeforeMount, ref } from 'vue';

const axios = window.axios;

const props = defineProps({
    user: {
        type: Object
    }
});

const selectedSchool = ref(null);
const studentSchool = ref(null);
const studentGrade = ref(null);
const schools = ref(null);
const grades = ref(null);

const getStudentSchoolInfo = () => {
    if(props.user.type == student) {
        axios.get(route('api.students.school_info', {id: props.user.id})).then(({data}) => {
            const {school, grade} = data;

            studentSchool.value = school;
            studentGrade.value = grade;
            selectedSchool.value = school.id;
        }, error => {
            console.log(error);
        })
    }
}

onBeforeMount(() => {
    getStudentSchoolInfo();
})

</script>

<template>
    <section>
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Perfil del usuario</h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Esta es el perfil perteneciente al usuario.
                </p>
            </header>

            <div class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Nombre" /> {{ user.name }}

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="user.name"
                        required
                        disabled
                    />

                </div>

                <div>
                    <InputLabel for="type" value="Tipo" />

                    <TextInput
                        id="type"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="user.type"
                        required
                        disabled
                    />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="user.email"
                        required
                        disabled
                    />
                </div>

                <div v-if="studentSchool != null">
                    <InputLabel for="school" value="Colegio" />

                    <TextInput
                        id="school"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="studentSchool.name"
                        required
                        disabled
                    />
                </div>

                <div v-if="studentSchool">
                    <InputLabel for="grade" value="Curso" />

                    <TextInput
                        v-if="studentGrade != null"
                        id="grade"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="studentGrade.name"
                        required
                        disabled
                    />
                    
                    <p v-else>Este colegio aun no posee cursos.</p>
                </div>

                <div class="flex items-center gap-4">
                    <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </div>
    </section>
</template>
