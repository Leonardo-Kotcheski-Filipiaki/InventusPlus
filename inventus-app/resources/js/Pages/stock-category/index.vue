<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import type StockCategory from '@/types/StockCategory';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const page = usePage();

defineProps<{
    categories: StockCategory[];
}>();

const show = ref(true);

if (page.props.flash?.success || page.props.flash?.error) {
    setTimeout(() => {
        show.value = false;
    }, 4000);
}
</script>

<template>
    <Head title="Categorias de Produtos - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Voltar ao Estoque',
                url: '/stocks'
            },
            {
                label: '+ Nova Categoria',
                url: '/stock-categories/create'
            }
        ]"/>

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Categorias de Produtos</h1>
                    <p class="text-sm text-zinc-200 mt-0.5">Gerenciamento de categorias para organização do estoque</p>
                </div>
                <Link
                    href="/stock-categories/create"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors"
                >
                    <span>+</span>
                    <span>Nova Categoria</span>
                </Link>
            </div>

            <!-- Toast Flash Messages -->
            <div v-if="(page.props.flash?.success || page.props.flash?.error) && show" class="mb-6">
                <div
                    v-if="page.props.flash?.success"
                    class="flex items-center justify-between p-3.5 bg-emerald-950/60 border border-emerald-800/80 rounded-lg text-emerald-200 text-sm font-medium"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ page.props.flash.success }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-100 text-xs font-bold px-1">✕</button>
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="flex items-center justify-between p-3.5 bg-rose-950/60 border border-rose-800/80 rounded-lg text-rose-200 text-sm font-medium"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                        <span>{{ page.props.flash.error }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-100 text-xs font-bold px-1">✕</button>
                </div>
            </div>

            <!-- Solid Dark Data Table Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-300">
                        <thead class="bg-zinc-950/70 border-b border-zinc-800 text-xs uppercase tracking-wider text-zinc-200 font-semibold">
                            <tr>
                                <th class="py-3 px-4">Código</th>
                                <th class="py-3 px-4">Nome da Categoria</th>
                                <th class="py-3 px-4">Descrição</th>
                                <th class="py-3 px-4 text-center">Produtos Vinculados</th>
                                <th class="py-3 px-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80 font-normal">
                            <tr v-for="cat in (categories || [])" :key="cat.id" class="hover:bg-zinc-800/50 transition-colors">
                                <td class="py-3.5 px-4 font-mono text-xs text-zinc-400">
                                    #{{ cat.id }}
                                </td>
                                <td class="py-3.5 px-4 font-medium text-white">
                                    {{ cat.name }}
                                </td>
                                <td class="py-3.5 px-4 text-zinc-400">
                                    {{ cat.description || '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                        {{ cat.stocks_count || 0 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/stock-categories/${cat.id}/edit`"
                                            class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-600 border border-zinc-700 text-zinc-200 hover:text-white text-xs font-medium rounded transition-colors"
                                        >
                                            Editar
                                        </Link>
                                        <Link
                                            :href="`/stock-categories/${cat.id}`"
                                            method="delete"
                                            as="button"
                                            class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/60 active:bg-rose-800/70 border border-rose-800/60 text-rose-300 hover:text-rose-100 text-xs font-medium rounded transition-colors cursor-pointer"
                                        >
                                            Excluir
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!categories || categories.length === 0">
                                <td colspan="5" class="py-12 text-center text-zinc-500">
                                    Nenhuma categoria de produto cadastrada ainda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>
