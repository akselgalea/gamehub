<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { bgClouds, logo, icon } from '@/Helpers/assets';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Bienvenido" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
        :style="`background-image: url(${bgClouds})`"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-200 hover:text-white dark:text-gray-100 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-200 hover:text-white dark:text-gray-100 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Iniciar sesión</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-200 hover:text-white dark:text-gray-100 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Registrarse</Link
                >
            </template>
        </div>

        <div class="w-10/12 mx-auto my-auto p-6 lg:p-8">
            <img class="logo w-[0px] slide" :src="logo" />
        </div>
    </div>
</template>

<style scoped>
.icon, .logo {
    overflow: hidden;
}

.slide {
	-webkit-animation: slide 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) 0.25s both;
	        animation: slide 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) 0.25s both;
}

@-webkit-keyframes slide {
  0% {
    -webkit-transform: translateX(100);
            transform: translateX(100);
  }
  100% {
    -webkit-transform: translateX(0px);
            transform: translateX(0px);
            width: 100%;
  }
}
@keyframes slide {
  0% {
    -webkit-transform: translateX(100px);
            transform: translateX(100px);
  }
  100% {
    -webkit-transform: translateX(0px);
            transform: translateX(0px);
            width: 100%;
  }
}
</style>
