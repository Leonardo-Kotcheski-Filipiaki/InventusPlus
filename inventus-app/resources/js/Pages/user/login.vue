<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { User } from '@/types';
import { post } from '@/routes/login';
import { ref } from 'vue';
const props = defineProps<{
    user : User;
}>()

const page = usePage();
const form = useForm({
    login: '',
    password: '',
});

const showElement = ref(true);

const login = () => {
    form.post(post.url());
    showElement.value = true;
    setTimeout(() => {
        showElement.value = false;
    }, 3000)
}
</script>

<template>
    <Head title="Inventus +" />
    <!-- Login Page with dark Purple background and white details and a centered white Login Card -->
    <div class="h-screen w-screen flex flex-col bg-linear-to-br from-indigo-600 to-purple-800">
        <div class="h-screen w-screen flex flex-col items-center justify-center">
            <div class="w-full flex items-center justify-center">
                <h1 class="text-4xl font-black text-center text-white">Inventus +</h1>
            </div>
            <div class="w-full flex flex-col items-center justify-center p-6 gap-5">
                <h1 class="text-center text-2xl font-bold text-white">Login</h1>

                <div v-if="page.props.flash.warning || showElement" id="warnings" class="text-yellow-300 text-lg font-semibold">
                    {{ page.props.flash.warning }}
                </div>

                <div v-if="page.props.errors.error || showElement" id="errors">
                    <span v-for="(error, index) in page.props.errors.error" :key="index" class="text-red-500 text-lg font-semibold">
                        {{ error }}
                    </span>
                </div>
                <form @submit.prevent="login()" class="flex flex-col gap-5">
                    <div class="relative flex flex-col">
                        <input v-model="form.login" type="text" placeholder="Usuário" class="border border-gray-300 text-white rounded p-2 font-medium focus:border-gray-400 focus:outline-none w-full">
                        <span v-if="form.errors.login" class="absolute -bottom-5 left-10 text-red-300 text-md font-semibold">
                            {{ form.errors.login }}
                        </span>
                    </div>
                        
                    <div class="relative flex flex-col">
                        <input v-model="form.password" type="password" placeholder="Senha" class="border border-gray-300 text-white rounded p-2 mt-5 font-medium focus:border-gray-400 focus:outline-none w-full">
                        <span v-if="form.errors.password" class="absolute -bottom-5 left-10 text-red-300 text-md font-semibold">
                            {{ form.errors.password }}
                        </span>
                    </div>
                    <button type="submit" class="border border-gray-300 font-semibold text-white rounded mt-5 p-2 cursor-pointer hover:bg-purple-400 hover:scale-105 transition duration-300 ease-in-out">Entrar</button>
                </form>
            </div>
        </div>    
    </div>
</template>
