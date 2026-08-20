<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { User } from '@/types';
import { post } from '@/routes/userlogin';
const props = defineProps<{
    user : User
}>()    

const form = useForm({
    login: '',
    password: '',
});

const login = () => {
    form.post(post.url());
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
                <form @submit.prevent="login()" class="flex flex-col gap-5">
                    <input v-model="form.login" type="text" placeholder="Usuário" class="border border-gray-300 text-white rounded p-2 font-medium focus:border-gray-400 focus:outline-none">
                    <input v-model="form.password" type="password" placeholder="Senha" class="border border-gray-300 text-white rounded p-2 font-medium focus:border-gray-400 focus:outline-none">
                    <button type="submit" class="border border-gray-300 font-semibold text-white rounded p-2 cursor-pointer hover:bg-purple-400 hover:scale-105 transition duration-300 ease-in-out">Entrar</button>
                </form>
                
            </div>
        </div>    
    </div>
</template>
