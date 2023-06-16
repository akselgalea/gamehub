<script setup>
    const props = defineProps({
        modelValue: {
            type: String,
            required: true
        },
        question: {
            type: String,
            required: true
        }
    });

    defineEmits(['update:modelValue']);
</script>

<template>
    <div class="mt-10">
        <div class="text-center font-semibold mb-2 break-words">{{ question }}</div>

        <input
            type="text" 
            class="w-full border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>