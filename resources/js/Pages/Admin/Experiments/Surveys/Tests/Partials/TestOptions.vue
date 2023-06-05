<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';

    const errors = ref({});

    const options = ref([]);
</script>

<template>
    <div class="mt-5">
        <InputLabel for="name" value="Opción 1" />
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>

    <div class="mt-5">
        <InputLabel for="name" value="Opción 2" />
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>

    <div class="mt-5">
        <InputLabel for="name" value="Opción 3" />
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>

    <div class="mt-5">
        <InputLabel for="name" value="Opción 4" />
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>

    <div class="mt-5">
        <InputLabel for="name" value="Opción 4" />
        
        <TextInput 
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>
</template>