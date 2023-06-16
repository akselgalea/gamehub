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

    const howMuch = ref('');

    watch(howMuch, (newVal, oldVal) => {
        emits('update:modelValue', newVal);
    });
</script>

<template>
    <fieldset class="m-5">
        <div class="text-center font-semibold py-3">{{ question }}</div>

        <div class="grid grid-cols-3 items-center">
            <p class="mr-5 text-left">{{ minText }}</p>
            
            <div class="flex flex-grow gap-5">
                <div>
                    <label class="cursor-pointer" :for="`max-4-${index}`">
                        <div class="flex justify-center items-center flex-col gap-1">
                            <img width="40" height="40" :src="smile1" />
                            <input type="radio" :id="`max-4-${index}`" :name="`howMuch-${index}`" value="1" v-model="howMuch" />
                        </div>
                    </label>
                </div>

                <div>
                    <label class="cursor-pointer" :for="`max-3-${index}`">
                        <div class="flex justify-center items-center flex-col gap-1">
                            <img width="40" height="40" :src="smile2" />
                            <input type="radio" :id="`max-3-${index}`" :name="`howMuch-${index}`" value="2" v-model="howMuch" />
                        </div>
                    </label>
                </div>

                <div>
                    <label class="cursor-pointer" :for="`max-2-${index}`">
                        <div class="flex justify-center items-center flex-col gap-1">
                            <img width="40" height="40" :src="smile3" />
                            <input type="radio" :id="`max-2-${index}`" :name="`howMuch-${index}`" value="3" v-model="howMuch" />
                        </div>
                    </label>
                </div>

                <div>
                    <label class="cursor-pointer" :for="`max-1-${index}`">
                        <div class="flex justify-center items-center flex-col gap-1">
                            <img width="40" height="40" :src="smile4" />
                            <input type="radio" :id="`max-1-${index}`" :name="`howMuch-${index}`" value="4" v-model="howMuch" />
                        </div>
                    </label>
                </div>

                <div>
                    <label class="cursor-pointer" :for="`max-${index}`">
                        <div class="flex justify-center items-center flex-col gap-1">
                            <img width="40" height="40" :src="smile5" />
                            <input type="radio" :id="`max-${index}`" :name="`howMuch-${index}`" value="5" v-model="howMuch" />
                        </div>
                    </label>
                </div>
            </div>

            <p class="ml-5 text-right">{{ maxText }}</p>
        </div>
    </fieldset>
</template>