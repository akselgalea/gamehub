<script setup>
const props = defineProps({
    question: {
        type: Object,
        required: true
    },
});

const type = props.question.type;

</script>
<template>
    <div>
        <p>{{ question.question }}</p>

        <template v-if="type == 'likert'">
            <p>{{ question.minText }}</p>
            <p>{{ question.maxText }}</p>
        </template>
    </div>
</template>