<script setup>
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
    if(props.user.type == 'student' && props.user.grade_id) {
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
    <Head title="Informacion general del usuario" />

    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informacion General</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">En este apartado se puede ver la informacion general del usuario.</p>
    </header>

    <section class="mt-5">
        <p class="text-sm text-gray-600 dark:text-gray-400" v-if="user == null">No se encontro el usuario.</p>

        <div v-else class="flex flex-col gap-10 w-full text-gray-900 dark:text-gray-200">
            <div class="font-semibold text-sm first-letter:uppercase">Nombre: {{ user.name }}</div>
            <div class="font-semibold text-sm first-letter:uppercase">Correo: {{ user.email }}</div>
            <template v-if="user.type == 'student'">
                <div class="font-semibold text-sm first-letter:uppercase">Tipo de usuario: Estudiante</div>
                <template v-if="studentGrade">
                    <div class="font-semibold text-sm first-letter:uppercase">Colegio: {{ studentSchool.name }}</div>
                    <div class="font-semibold text-sm first-letter:uppercase">Grado: {{ studentGrade.name }}</div>
                </template>
            </template>
        </div>
    </section>
</template>

