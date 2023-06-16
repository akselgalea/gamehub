<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { onMounted } from 'vue';

const axios = window.axios;

const props = defineProps({
    user: {
        type: Object
    },
});

const types = {
    student: 'Estudiante',
    admin: 'Administrador'
}

const studentSchool = ref('');
const studentGrade = ref('');
const tipo = types[props.user.type];

onMounted(() => {
    if(props.user.grade_id)
        axios.get(route('api.students.school_info', {id: props.user.id})).then(({data}) => {
            const {grade} = data;

            if(grade) {
                studentSchool.value = grade.school;
                studentGrade.value = grade;
            }

        }, error => {
            console.log(error);
        })
});

const showingModal = ref(null);

const showModal = () => {
    showingModal.value = true;
}

const closeModal = () => {
    showingModal.value = false;
};

</script>

<template>
    <section>
        <PrimaryButton @click="showModal" title="Ver datos"><i class="fas fa-address-card"></i></PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del usuario</h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Esta es la informacion perteneciente al usuario.
                </p>
            </header>

            <div class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Nombre" />

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
                        v-model="tipo"
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

                <template v-if="user.grade_id">
                    <div>
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
    
                    <div>
                        <InputLabel for="grade" value="Curso" />
    
                        <TextInput 
                            id="grade"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="studentGrade.name"
                            required
                            disabled
                        />
                        
                    </div>
                </template>

                <div class="flex items-center gap-4">
                    <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </div>
        </Modal>
    </section>
</template>
