<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const form = useForm({
    name: '',
    description: ''
});

const showElement = ref(true);
const page = usePage();

const submit = () => {
    form.post('/stock-categories', {
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
    <Head title="Adicionar Categoria - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Voltar ao Estoque',
                url: '/stocks'
            },
            {
                label: 'Listar Categorias',
                url: '/stock-categories'
            }
        ]"/>

        <main class="flex-1 max-w-2xl w-full mx-auto px-6 py-8">
            <!-- Breadcrumbs / Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Cadastrar Nova Categoria</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Defina uma nova categoria para agrupar produtos</p>
                </div>
                <Link
                    href="/stock-categories"
                    class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-lg border border-zinc-700 transition-colors"
                >
                    Voltar
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 shadow-sm">
                <!-- Warnings / Errors -->
                <div
                    v-if="page.props.flash?.warning && showElement"
                    class="mb-6 p-3 rounded-lg bg-amber-950/50 border border-amber-800/80 text-amber-200 text-sm"
                >
                    {{ page.props.flash.warning }}
                </div>

                <form @submit.prevent="submit()" class="space-y-6">
                    <div class="space-y-5">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Nome da Categoria <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Ex: Eletrônicos, Ferramentas, Alimentos..."
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.name" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Descrição
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                placeholder="Descrição opcional sobre os itens pertencentes a esta categoria..."
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none"
                            ></textarea>
                            <span v-if="form.errors.description" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.description }}
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                        <Link
                            href="/stock-categories"
                            class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-semibold rounded-lg border border-zinc-700 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                        >
                            <span v-if="form.processing">Salvando...</span>
                            <span v-else>Salvar Categoria</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
