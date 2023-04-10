<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';

const props = defineProps({
    param: {
        type: Object,
        required: true
    },
    errors: {
        type: Object
    }
})

defineEmits(['update:modelValue']);
</script>
<template>
    <div>
        <InputLabel for="name" value="Nombre"/>
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="param.name"
            required
            autofocus
        />

        <InputError class="mt-2" :message="errors.name" />
    </div>

    <div class="mt-5">
        <InputLabel for="type" value="Tipo"/>

        <select id="type" v-model="param.type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <option :value="!param.type ? param.type : ''" hidden :selected="!param.type">Elige una opcion</option>
            <option v-for="[type, text] in Object.entries(types)" :value="type">{{ text }}</option>
        </select>

        <InputError class="mt-2" :message="errors.type" />
    </div>

    <div class="mt-5">
        <InputLabel for="description" value="Descripcion"/>
        
        <TextArea
            id="description"
            class="mt-1 block w-full"
            v-model="form.description"
            rows="4"
        />

        <InputError class="mt-2" :message="errors.description" />
    </div>
</template>