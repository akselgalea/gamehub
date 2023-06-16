<script setup>
    import { smile1, smile2, smile3, smile4, smile5 } from '@/Helpers/assets';
    import { watch, ref } from 'vue';

    const props = defineProps({
        modelValue: {
            type: String,
            required: true
        },
        question: {
            type: String,
        },
        minText: {
            type: String
        },
        maxText: {
            type: String
        },
        index: {
            type: Number
        }
    });
    
    const emits = defineEmits(['update:modelValue']);

    const howMuch = ref(null);

    watch(howMuch, (oldVal, newVal) => {
        emits('update:modelValue', newVal);
        console.log(newVal);
    });
</script>

<template>
    <fieldset>
        <legend>{{ question }}</legend>

        <div class="flex gap-2">
            <p>{{ minText }}</p>
            
            <div class="flex gap-5">
                <div>
                    <label for="max-4">
                        <img width="40" height="40" :src="smile1" />
                        <input type="radio" id="max-4" :name="`howMuch${index}`" value="1">
                    </label>
                </div>

                <div>
                    <label for="max-3">
                        <img width="40" height="40" :src="smile2" />
                        <input type="radio" id="max-3" :name="`howMuch${index}`" value="2">
                    </label>
                </div>

                <div>
                    <label for="max-2">
                        <img width="40" height="40" :src="smile3" />
                        <input type="radio" id="max-2" :name="`howMuch${index}`" value="3">
                    </label>
                </div>

                <div>
                    <label for="max-1">
                        <img width="40" height="40" :src="smile4" />
                        <input type="radio" id="max-1" :name="`howMuch${index}`" value="4">
                    </label>
                </div>

                <div>
                    <label for="max">
                        <img width="40" height="40" :src="smile5" />
                        <input type="radio" id="max" :name="`howMuch${index}`" value="5">
                    </label>
                </div>
            </div>

            <p>{{ maxText }}</p>
        </div>
    </fieldset>

</template>