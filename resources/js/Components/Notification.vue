<script setup>
const props = defineProps({
    status: {
        type: Number,
        required: true
    },
    message: {
        type: String,
        required: true
    }
});

const statusClasses = {
    200: 'bg-green-600',
    404: 'bg-blue-400',
    500: 'bg-red-600',
    400: 'bg-orange-600'
};
</script>

<template>
    <div :class="statusClasses[status] + ' max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-10 rounded-md h-10 flex items-center'">
        <div class="text-md font-medium text-white">{{ message }}</div>
    </div>
</template>