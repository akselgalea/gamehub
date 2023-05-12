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
    200: 'success',
    404: 'not-found',
    500: 'error',
    400: 'not-permitted'
};
</script>

<template>
    <div :class="statusClasses[status]">
        <div>{{ message }}</div>
    </div>
</template>