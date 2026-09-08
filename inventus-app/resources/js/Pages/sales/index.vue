<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import salesRoutes from '@/routes/sales';
import type Sale from '@/types/Sale';
import type Stock from '@/types/Stock';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const page = usePage();

const props = defineProps<{
    sales: Sale[];
    stocks?: Stock[];
}>();

const show = ref(true);

if (page.props.flash?.success || page.props.flash?.error) {
    setTimeout(() => {
        show.value = false;
    }, 4000);
}

const formatCurrency = (value: number | string) => {
    const num = Number(value);
    if (isNaN(num)) return 'R$ 0,00';
    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

const formatDate = (dateString?: string) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return date.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Filtros
const search = ref('');
const selectedType = ref<'all' | 'entrada' | 'saida'>('all');
const selectedStock = ref<string | number>('all');

const hasActiveFilters = computed(() => {
    return search.value.trim() !== '' || selectedType.value !== 'all' || selectedStock.value !== 'all';
});

const clearFilters = () => {
    search.value = '';
    selectedType.value = 'all';
    selectedStock.value = 'all';
};

// Produtos únicos para o filtro
const availableStocks = computed(() => {
    if (props.stocks && props.stocks.length > 0) {
        return props.stocks;
    }
    const map = new Map<number, { id: number; name: string }>();
    for (const s of props.sales || []) {
        if (s.stock && !map.has(s.stock.id)) {
            map.set(s.stock.id, { id: s.stock.id, name: s.stock.name });
        }
    }
    return Array.from(map.values());
});

const filteredSales = computed(() => {
    const list = props.sales || [];
    const query = search.value.trim().toLowerCase();

    return list.filter((item) => {
        // Tipo
        if (selectedType.value !== 'all' && item.sales_type !== selectedType.value) {
            return false;
        }

        // Produto
        if (selectedStock.value !== 'all' && Number(item.stock_id) !== Number(selectedStock.value)) {
            return false;
        }

        // Busca
        if (query) {
            const productName = item.stock?.name?.toLowerCase() || '';
            const customerName = item.customer?.name?.toLowerCase() || '';
            const supplierName = item.supplier?.name?.toLowerCase() || '';
            const description = item.description?.toLowerCase() || '';

            const matchesQuery =
                productName.includes(query) ||
                customerName.includes(query) ||
                supplierName.includes(query) ||
                description.includes(query);

            if (!matchesQuery) return false;
        }

        return true;
    });
});

// Métricas / Resumo
const metrics = computed(() => {
    const list = props.sales || [];
    let countEntrada = 0;
    let totalEntrada = 0;
    let countSaida = 0;
    let totalSaida = 0;

    for (const item of list) {
        const total = Number(item.quantity) * Number(item.unit_value);
        if (item.sales_type === 'entrada') {
            countEntrada++;
            totalEntrada += total;
        } else if (item.sales_type === 'saida') {
            countSaida++;
            totalSaida += total;
        }
    }

    return {
        countEntrada,
        totalEntrada,
        countSaida,
        totalSaida,
        balance: totalSaida - totalEntrada,
    };
});
</script>

<template>
    <Head title="Vendas e Movimentações - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: '+ Nova Movimentação / Venda',
                url: salesRoutes.create()
            },
            {
                label: 'Histórico de Vendas',
                url: salesRoutes.index()
            },
            {
                label: 'Estoque de Produtos',
                url: '/stocks'
            }
        ]"/>

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Movimentações de Estoque e Vendas</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Histórico completo de entradas (compras/reposições) e saídas (vendas)</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        :href="salesRoutes.create()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nova Movimentação
                    </Link>
                </div>
            </div>

            <!-- Flash Alert -->
            <div
                v-if="(page.props.flash?.success || page.props.flash?.error) && show"
                class="mb-6 transition-all duration-300"
            >
                <div
                    v-if="page.props.flash?.success"
                    class="p-3.5 rounded-lg bg-emerald-950/50 border border-emerald-800/80 text-emerald-200 text-xs flex items-center justify-between"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ page.props.flash.success }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-100 text-xs font-bold px-1">✕</button>
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="p-3.5 rounded-lg bg-rose-950/50 border border-rose-800/80 text-rose-200 text-xs flex items-center justify-between"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                        <span>{{ page.props.flash.error }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-100 text-xs font-bold px-1">✕</button>
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <!-- Saídas (Vendas) -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Saídas (Vendas)</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-950/60 text-indigo-300 border border-indigo-800/60">
                            {{ metrics.countSaida }} transações
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl font-bold text-indigo-400">{{ formatCurrency(metrics.totalSaida) }}</span>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Total faturado em vendas</p>
                    </div>
                </div>

                <!-- Entradas (Reposições) -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Entradas (Compras)</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-950/60 text-emerald-300 border border-emerald-800/60">
                            {{ metrics.countEntrada }} transações
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl font-bold text-emerald-400">{{ formatCurrency(metrics.totalEntrada) }}</span>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Total em reposição de estoque</p>
                    </div>
                </div>

                <!-- Balanço -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Balanço Líquido</span>
                        <span class="text-[11px] text-zinc-400">Vendas - Entradas</span>
                    </div>
                    <div class="mt-3">
                        <span
                            :class="[
                                'text-xl font-bold',
                                metrics.balance >= 0 ? 'text-emerald-400' : 'text-rose-400'
                            ]"
                        >
                            {{ formatCurrency(metrics.balance) }}
                        </span>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Saldo financeiro movimentado</p>
                    </div>
                </div>
            </div>

            <!-- Filter Controls Bar -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 mb-5 shadow-sm space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Search Input -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar produto, cliente, fornecedor ou descrição..."
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

                    <!-- Tipo Filter -->
                    <div>
                        <select
                            v-model="selectedType"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todos os tipos (Entradas e Saídas)</option>
                            <option value="saida">Apenas Saídas (Vendas)</option>
                            <option value="entrada">Apenas Entradas (Compras/Reposições)</option>
                        </select>
                    </div>

                    <!-- Produto Filter -->
                    <div>
                        <select
                            v-model="selectedStock"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todos os produtos</option>
                            <option
                                v-for="item in availableStocks"
                                :key="item.id"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Active filters banner -->
                <div v-if="hasActiveFilters" class="flex items-center justify-between pt-2 border-t border-zinc-800 text-xs text-zinc-400">
                    <div>
                        Exibindo <span class="font-semibold text-zinc-200">{{ filteredSales.length }}</span> de <span class="font-semibold text-zinc-200">{{ sales?.length || 0 }}</span> registros
                    </div>
                    <button
                        @click="clearFilters"
                        class="text-xs text-indigo-400 hover:text-indigo-300 font-medium cursor-pointer transition-colors"
                    >
                        Limpar todos os filtros
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-zinc-300">
                        <thead class="bg-zinc-950/70 border-b border-zinc-800 text-[11px] uppercase tracking-wider text-zinc-400">
                            <tr>
                                <th scope="col" class="py-3 px-4 font-semibold">Tipo</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Data / Hora</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Produto</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Cliente / Fornecedor</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-center">Qtd</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Valor Unit.</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Total</th>
                                <th scope="col" class="py-3 px-4 font-semibold">Descrição</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/60">
                            <tr
                                v-for="item in filteredSales"
                                :key="item.id"
                                class="hover:bg-zinc-800/40 transition-colors"
                            >
                                <!-- Tipo Badge -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span
                                        v-if="item.sales_type === 'entrada'"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-950/60 text-emerald-300 border border-emerald-800/60"
                                    >
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Entrada
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-950/60 text-indigo-300 border border-indigo-800/60"
                                    >
                                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Saída
                                    </span>
                                </td>

                                <!-- Data -->
                                <td class="py-3.5 px-4 whitespace-nowrap text-zinc-400">
                                    {{ formatDate(item.created_at) }}
                                </td>

                                <!-- Produto -->
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-white">
                                        {{ item.stock?.name || 'Produto não encontrado' }}
                                    </div>
                                </td>

                                <!-- Parceiro (Cliente / Fornecedor) -->
                                <td class="py-3.5 px-4">
                                    <div v-if="item.sales_type === 'entrada'">
                                        <div class="text-zinc-200 font-medium">
                                            {{ item.supplier?.name || '-' }}
                                        </div>
                                        <div class="text-[11px] text-zinc-500">Fornecedor</div>
                                    </div>
                                    <div v-else>
                                        <div class="text-zinc-200 font-medium">
                                            {{ item.customer?.name || '-' }}
                                        </div>
                                        <div class="text-[11px] text-zinc-500">Cliente</div>
                                    </div>
                                </td>

                                <!-- Quantidade -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono border bg-zinc-800 text-zinc-300 border-zinc-700">
                                        {{ item.quantity }} un
                                    </span>
                                </td>

                                <!-- Valor Unitário -->
                                <td class="py-3.5 px-4 whitespace-nowrap text-zinc-300 font-mono">
                                    {{ formatCurrency(item.unit_value) }}
                                </td>

                                <!-- Valor Total -->
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono font-semibold"
                                    :class="item.sales_type === 'entrada' ? 'text-emerald-400' : 'text-indigo-400'"
                                >
                                    {{ formatCurrency(Number(item.quantity) * Number(item.unit_value)) }}
                                </td>

                                <!-- Descrição -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <span v-if="item.description" class="truncate block text-zinc-400" :title="item.description">
                                        {{ item.description }}
                                    </span>
                                    <span v-else class="text-zinc-600">-</span>
                                </td>

                                <!-- Ações -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            v-if="item.id"
                                            :href="salesRoutes.edit(item.id)"
                                            class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-600 border border-zinc-700 text-zinc-200 hover:text-white text-xs font-medium rounded transition-colors"
                                        >
                                            Editar
                                        </Link>
                                        <Link
                                            v-if="item.id"
                                            :href="salesRoutes.destroy(item.id)"
                                            method="delete"
                                            as="button"
                                            class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/60 active:bg-rose-800/70 border border-rose-800/60 text-rose-300 hover:text-rose-100 text-xs font-medium rounded transition-colors cursor-pointer"
                                        >
                                            Excluir
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="filteredSales.length === 0">
                                <td colspan="9" class="py-12 text-center text-zinc-500">
                                    <div v-if="hasActiveFilters" class="flex flex-col items-center gap-2">
                                        <span>Nenhuma movimentação encontrada com os filtros aplicados.</span>
                                        <button
                                            @click="clearFilters"
                                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium underline cursor-pointer"
                                        >
                                            Limpar filtros
                                        </button>
                                    </div>
                                    <div v-else class="flex flex-col items-center gap-2">
                                        <p>Nenhuma venda ou movimentação cadastrada até o momento.</p>
                                        <Link
                                            :href="salesRoutes.create()"
                                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium underline"
                                        >
                                            Registrar primeira movimentação
                                        </Link>
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