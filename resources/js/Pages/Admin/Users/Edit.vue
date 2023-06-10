<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import UpdateUserForm from './Partials/UpdateUserForm.vue';
import Modal from '@/Components/Modal.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdateSchoolInformationForm from '@/Pages/Profile/Partials/UpdateSchoolInformationForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

defineProps({
    user: {
        type: Object,
        required: true
    },
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
    <PrimaryButton @click="showModal"><i class="fas fa-edit"></i></PrimaryButton>

    <Modal :show="showingModal" @close="closeModal">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <UpdateProfileInformationForm :userData="user" class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <UpdateSchoolInformationForm :userId="user.id" class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <DeleteUserForm :userId="user.id" class="max-w-xl" />
        </div>
    </Modal>
</template>
