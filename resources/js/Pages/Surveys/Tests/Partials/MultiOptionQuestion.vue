<script setup>
    import { ref, watch } from 'vue';

    const emits = defineEmits(['update:modelValue']);

    const props = defineProps({
        modelValue: {
            type: String,
            required: true
        },
        question: {
            type: String,
            required: true
        },
        options: {
            type: Array,
            required: true
        },
        index: {
            type: Number
        }
    });

    const opcion = ref('');
    watch(opcion, (newVal, lastVal) => {
        emits('update:modelValue', newVal);
    });
</script>
<template>
    <div class="mt-5">
        <div class="text-center font-semibold mb-2 break-words">{{ question }}</div>
        
        <fieldset class="mt-2 grid grid-cols-1 gap-2">
            <div v-for="(option, idx) in options">
                <label class="flex items-center gap-2" :for="`multi-${index}-${idx}`">
                    <input type="radio" :id="`multi-${index}-${idx}`" :name="`multi-${index}`" v-model="opcion" :value="option" />
                    <p class="m-0">{{ option }}</p>
                </label>
            </div>
        </fieldset>
    </div>
</template>