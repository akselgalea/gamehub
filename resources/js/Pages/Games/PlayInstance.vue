<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    game: {
        type: Object,
        required: true
    },
    location: {
        type: String,
        required: true
    }
})

const setFullscreen = () => {
    const gameCanvas = document.getElementById('canvas');

    gameCanvas.requestFullscreen();
}
</script>

<template>
    <Head :title="game.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Juegos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
                <header>
                    <h2 class="first-letter:uppercase text-gray-600 dark:text-gray-400">{{game.name}}</h2>
                </header>

                <div class="max-w-7xl h-full mx-auto">
                    <canvas id="canvas" width="960" height="540" >
                        <p>Your browser doesn't support HTML5 canvas.</p>
                    </canvas>
                </div>

                <PrimaryButton @click="setFullscreen">Pantalla completa</PrimaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
    export default {
        mounted() {
            if(this.$props.game.gm2game) {
                const script = document.createElement('script');
                script.src = this.$props.location;
                document.body.appendChild(script);

                script.onload = () => {
                    GameMaker_Init();
                }
            }
        }
    }
</script>

<style scoped>
canvas {
    image-rendering: optimizeSpeed;
    -webkit-interpolation-mode: nearest-neighbor;
    -ms-touch-action: none;
    touch-action: none;
    margin: 0px;
    padding: 0px;
    border: 0px;
}
:-webkit-full-screen #canvas {
    width: 100%;
    height: 100%;
}
:-webkit-full-screen {
    width: 100%;
    height: 100%;
}

/* Custom Runner Styles */
div.gm4html5_div_class {
    margin: 0px;
    padding: 0px;
    border: 0px;
}
div.gm4html5_login {
    padding: 20px;
    position: absolute;
    border: solid 2px #000000;
    background-color: #404040;
    color:#00ff00;
    border-radius: 15px;
    box-shadow: #101010 20px 20px 40px;
}
div.gm4html5_cancel_button {
    float: right;
}
div.gm4html5_login_button {
    float: left;
}
div.gm4html5_login_header {
    text-align: center;
}
</style>