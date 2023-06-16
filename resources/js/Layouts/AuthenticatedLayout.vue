<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Notification from '@/Components/Notification.vue';
import AdminNavigationLinks from './Partials/AdminNavigationLinks.vue';
import NavigationLinks from './Partials/NavigationLinks.vue';

const isAdmin = usePage().props.auth.user.type === 'admin';
const notification = computed(() => usePage().props.flash.notification ? usePage().props.flash.notification : null);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <component :is="isAdmin ? AdminNavigationLinks : NavigationLinks" />

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <Notification v-if="notification" :status="notification.status" :message="notification.message" />
                <slot />
            </main>
        </div>
    </div>
</template>