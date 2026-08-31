<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { post } from '@/routes/login';
import { ref } from 'vue';

const page = usePage();
const form = useForm({
    login: '',
    password: '',
});

const showElement = ref(true);

const login = () => {
    form.post(post.url(), {
        onFinish: () => {
            if (page.props.flash?.warning || page.props.errors?.error) {
                showElement.value = true;
                setTimeout(() => {
                    showElement.value = false;
                }, 4000);
            }
        }
    });
};
</script>

<template>
    <Head title="Login - Inventus +" />
    
    <div class="min-h-screen w-full flex flex-col items-center justify-center bg-zinc-950 px-4">
        <div class="w-full max-w-md">
            <!-- Brand header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-600 text-white font-black text-xl mb-3 shadow-sm">
                    I+
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Inventus Plus</h1>
                <p class="text-zinc-400 text-sm mt-1">Gerenciamento inteligente de estoque e clientes</p>
            </div>

            <!-- Solid Dark Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 shadow-xl">
                <h2 class="text-lg font-semibold text-zinc-100 mb-6">Acesse sua conta</h2>

                <!-- Flash Warnings -->
                <div
                    v-if="page.props.flash?.warning && showElement"
                    class="mb-5 p-3 rounded-lg bg-amber-950/50 border border-amber-800/80 text-amber-200 text-sm"
                >
                    {{ page.props.flash.warning }}
                </div>

                <!-- Error Messages -->
                <div
                    v-if="page.props.errors?.error && showElement"
                    class="mb-5 p-3 rounded-lg bg-rose-950/50 border border-rose-800/80 text-rose-200 text-sm"
                >
                    <p v-for="(error, index) in page.props.errors.error" :key="index">
                        {{ error }}
                    </p>
                </div>

                <form @submit.prevent="login()" class="space-y-4">
                    <div>
                        <label for="login" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                            Usuário
                        </label>
                        <input
                            id="login"
                            v-model="form.login"
                            type="text"
                            autocomplete="username"
                            required
                            placeholder="Digite seu usuário ou email"
                            class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        />
                        <span v-if="form.errors.login" class="block mt-1 text-xs text-rose-400">
                            {{ form.errors.login }}
                        </span>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                            Senha
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        />
                        <span v-if="form.errors.password" class="block mt-1 text-xs text-rose-400">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white font-semibold text-sm rounded-lg transition-colors cursor-pointer"
                    >
                        <span v-if="form.processing">Entrando...</span>
                        <span v-else>Entrar no Sistema</span>
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-zinc-600 mt-6">
                Inventus Plus &copy; {{ new Date().getFullYear() }} — Todos os direitos reservados.
            </p>
        </div>
    </div>
</template>

