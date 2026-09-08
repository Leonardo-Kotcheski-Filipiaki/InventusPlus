<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import stocks from '@/routes/stocks';
import type Stock from '@/types/Stock';
import type StockCategory from '@/types/StockCategory';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import type Supplier from '@/types/Supplier';

const page = usePage();

const props = defineProps<{
    stock: Stock[];
    categories?: StockCategory[];
    suppliers?: Supplier[];
}>();

const show = ref(true);

if (page.props.flash?.success || page.props.flash?.error) {
    setTimeout(() => {
        show.value = false;
    }, 4000);
}

const formatCurrency = (value: number | string) => {
    const num = Number(value);

    if (isNaN(num)) {
        return 'R$ 0,00';
    }

    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

// Filtros
const search = ref('');
const selectedCategory = ref<string | number>('all');
const selectedSupplier = ref<string | number>('all');
const stockStatus = ref<'all' | 'in_stock' | 'low_stock' | 'out_of_stock'>('all');

const hasActiveFilters = computed(() => {
    return (
        search.value.trim() !== '' ||
        selectedCategory.value !== 'all' ||
        selectedSupplier.value !== 'all' ||
        stockStatus.value !== 'all'
    );
});

const clearFilters = () => {
    search.value = '';
    selectedCategory.value = 'all';
    selectedSupplier.value = 'all';
    stockStatus.value = 'all';
};

// Lista de categorias para o dropdown (das props ou extraídas dos produtos)
const availableCategories = computed(() => {
    if (props.categories && props.categories.length > 0) {
        return props.categories;
    }

    const map = new Map<number, StockCategory>();

    for (const item of props.stock || []) {
        if (item.stock_category && !map.has(item.stock_category.id)) {
            map.set(item.stock_category.id, item.stock_category);
        }
    }

    return Array.from(map.values());
});

// Lista de fornecedores para o dropdown (das props ou extraídos dos produtos)
const availableSuppliers = computed(() => {
    if (props.suppliers && props.suppliers.length > 0) {
        return props.suppliers;
    }

    const map = new Map<number, Supplier>();

    for (const item of props.stock || []) {
        if (item.supplier && !map.has(item.supplier.id)) {
            map.set(item.supplier.id, item.supplier);
        }
    }

    return Array.from(map.values());
});

const filteredStocks = computed(() => {
    const list = props.stock || [];
    const query = search.value.trim().toLowerCase();

    return list.filter(item => {
        // Filtro por Categoria
        if (selectedCategory.value !== 'all') {
            if (Number(item.stock_category_id) !== Number(selectedCategory.value)) {
                return false;
            }
        }

        // Filtro por Fornecedor
        if (selectedSupplier.value !== 'all') {
            if (Number(item.supplier_id) !== Number(selectedSupplier.value)) {
                return false;
            }
        }

        // Filtro por Nível de Estoque
        if (stockStatus.value === 'in_stock' && Number(item.quantity) <= 0) {
            return false;
        }

        if (stockStatus.value === 'low_stock' && (Number(item.quantity) <= 0 || Number(item.quantity) > 5)) {
            return false;
        }

        if (stockStatus.value === 'out_of_stock' && Number(item.quantity) > 0) {
            return false;
        }

        // Filtro Textual
        if (!query) {
            return true;
        }

        const nameMatch = item.name?.toLowerCase().includes(query);
        const descMatch = item.description?.toLowerCase().includes(query);
        const idMatch = String(item.id).includes(query.replace('#', ''));
        const categoryMatch = item.stock_category?.name?.toLowerCase().includes(query);
        const supplierMatch = item.supplier?.name?.toLowerCase().includes(query);

        return Boolean(nameMatch || descMatch || idMatch || categoryMatch || supplierMatch);
    });
});
</script>

<template>
    <Head title="Estoque - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: '+ Novo Produto',
                url: stocks.create()
            },
            {
                label: 'Categorias de Produtos',
                url: '/stock-categories'
            }
        ]"/>

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Estoque</h1>
                    <p class="text-sm text-zinc-200 mt-0.5">Gerenciamento de produtos e inventário</p>
                </div>
                <Link
                    :href="stocks.create()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors"
                >
                    <span>+</span>
                    <span>Novo Produto</span>
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

            <!-- Filter Controls Bar -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 mb-5 shadow-sm space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Search Input -->
                    <div class="relative sm:col-span-2 lg:col-span-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar produto, código..."
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg pl-9 pr-8 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        />
                        <button
                            v-if="search"
                            @click="search = ''"
                            class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-zinc-500 hover:text-zinc-300"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select
                            v-model="selectedCategory"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todas as categorias</option>
                            <option
                                v-for="cat in availableCategories"
                                :key="cat.id"
                                :value="cat.id"
                            >
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Supplier Filter -->
                    <div>
                        <select
                            v-model="selectedSupplier"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todos os fornecedores</option>
                            <option
                                v-for="supp in availableSuppliers"
                                :key="supp.id"
                                :value="supp.id"
                            >
                                {{ supp.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Stock Status Filter -->
                    <div>
                        <select
                            v-model="stockStatus"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todos os status</option>
                            <option value="in_stock">Em Estoque (> 0)</option>
                            <option value="low_stock">Estoque Baixo (≤ 5)</option>
                            <option value="out_of_stock">Sem Estoque (= 0)</option>
                        </select>
                    </div>
                </div>

                <!-- Sub-bar with Results Counter & Clear Button -->
                <div class="flex items-center justify-between pt-2 border-t border-zinc-800/60 text-xs">
                    <div class="text-zinc-400 font-medium">
                        Exibindo <span class="text-zinc-200 font-semibold">{{ filteredStocks.length }}</span> de <span class="text-zinc-200 font-semibold">{{ props.stock?.length || 0 }}</span> produtos
                    </div>

                    <button
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium rounded border border-zinc-700 transition-colors"
                    >
                        Limpar Filtros
                    </button>
                </div>
            </div>

            <!-- Solid Dark Data Table Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-300">
                        <thead class="bg-zinc-950/70 border-b border-zinc-800 text-xs uppercase tracking-wider text-zinc-200 font-semibold">
                            <tr>
                                <th class="py-3 px-4">Código</th>
                                <th class="py-3 px-4">Produto</th>
                                <th class="py-3 px-4">Categoria</th>
                                <th class="py-3 px-4">Fornecedor</th>
                                <th class="py-3 px-4 text-center">Quantidade</th>
                                <th class="py-3 px-4">Valor Unitário</th>
                                <th class="py-3 px-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80 font-normal">
                            <tr v-for="item in filteredStocks" :key="item.id" class="hover:bg-zinc-800/50 transition-colors">
                                <td class="py-3.5 px-4 font-mono text-xs text-zinc-400">
                                    #{{ item.id }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-white">{{ item.name }}</div>
                                    <div v-if="item.description" class="text-xs text-zinc-400 truncate max-w-xs mt-0.5">
                                        {{ item.description }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span v-if="item.stock_category?.name" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-800 text-indigo-300 border border-zinc-700">
                                        {{ item.stock_category.name }}
                                    </span>
                                    <span v-else class="text-zinc-600">—</span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-300">
                                    {{ item.supplier?.name || '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2 py-0.5 rounded text-xs font-mono border',
                                            Number(item.quantity) === 0
                                                ? 'bg-rose-950/50 text-rose-300 border-rose-800/60'
                                                : Number(item.quantity) <= 5
                                                    ? 'bg-amber-950/50 text-amber-300 border-amber-800/60'
                                                    : 'bg-zinc-800 text-zinc-300 border-zinc-700'
                                        ]"
                                    >
                                        {{ item.quantity || 0 }} un
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-200 font-medium">
                                    {{ formatCurrency(item.unit_value) }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            v-if="item.id"
                                            :href="stocks.edit(item.id)"
                                            class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-600 border border-zinc-700 text-zinc-200 hover:text-white text-xs font-medium rounded transition-colors"
                                        >
                                            Editar
                                        </Link>
                                        <Link
                                            v-if="item.id"
                                            :href="stocks.destroy(item.id)"
                                            method="delete"
                                            as="button"
                                            class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/60 active:bg-rose-800/70 border border-rose-800/60 text-rose-300 hover:text-rose-100 text-xs font-medium rounded transition-colors cursor-pointer"
                                        >
                                            Excluir
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredStocks.length === 0">
                                <td colspan="7" class="py-12 text-center text-zinc-500">
                                    <div v-if="hasActiveFilters" class="flex flex-col items-center gap-2">
                                        <span>Nenhum produto encontrado com os filtros aplicados.</span>
                                        <button
                                            @click="clearFilters"
                                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium underline"
                                        >
                                            Limpar filtros
                                        </button>
                                    </div>
                                    <div v-else>
                                        Nenhum produto cadastrado no estoque ainda.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>