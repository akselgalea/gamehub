<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onBeforeMount, ref } from 'vue';

const props = defineProps({
    // Propiedad que indica qué usuario se está editando
    userId: {
        type: Number
    }
});

const axios = window.axios;

const studentId = props.userId ?? usePage().props.auth.user.id;
const studentSchool = ref(null);
const studentGrade = ref(null);

const selectedSchool = ref(null);
const schools = ref(null);
const grades = ref(null);

const form = useForm({
    gradeId: null,
});

const updateGrade = () => {
    form.patch(route('api.students.school_info.update', {id: studentId}), {
        preserveScroll: true,
    });
};

const getStudentSchoolInfo = () => {
    axios.get(route('api.students.school_info', {id: studentId})).then(({data}) => {
        const {grade} = data;
        
        if(grade) {
            studentSchool.value = grade.school;
            studentGrade.value = grade;
            selectedSchool.value = grade.school.id;
            form.gradeId = grade.id;
        }

    }, error => {
        console.log(error);
    })
}

const setSchools = () => {
    getStudentSchoolInfo();

    axios.get(route('api.schools.index')).then(({data}) => {
        schools.value = data;
        if (studentSchool.value) {
            let s = schools.value.find(item => item.id == studentSchool.value.id);
            grades.value = s ? s.grades : null;
        }
    }, error => {
        console.log(error);
    })
}

const setGrades = () => {
    const school = schools.value.find(item => item.id == selectedSchool.value);
    
    grades.value = school.grades ?? [];
    
    form.gradeId = null;
}

onBeforeMount(() => {
    setSchools();
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del colegio</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualiza la información sobre a que colegio y curso pertenece.
            </p>
        </header>

        <form @submit.prevent="updateGrade()" class="mt-6 space-y-6">
            <div>
                <InputLabel for="school" value="Colegio" />

                <select
                    id="school"
                    v-model="selectedSchool"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    @change="setGrades()"   
                >
                    <option :value="!selectedSchool ? selectedSchool : ''" hidden :selected="!selectedSchool">Elige una opción</option>
                    <option v-for="s in schools" :value="s.id" :key="s.id">{{ s.name }}</option>
                </select>
            </div>

            <div v-if="grades">
                <InputLabel for="grade" value="Curso" />

                <select
                    v-if="grades.length > 0"
                    id="grade"
                    v-model="form.gradeId"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                >
                    <option :value="!form.gradeId ? form.gradeId : ''" hidden :selected="!form.gradeId">Elige una opción</option>
                    <option v-for="g in grades" :value="g.id" :key="g.id">{{ g.name }}</option>
                </select>
                
                <p v-else>Este colegio aun no posee cursos.</p>

                <InputError class="mt-2" :message="form.errors.gradeId" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
