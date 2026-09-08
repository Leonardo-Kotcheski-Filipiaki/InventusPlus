<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import address from '@/routes/address';
import customers from '@/routes/customers';
import type Customer from '@/types/Customer';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const page = usePage();

const props = defineProps<{
    customer: Customer[];
}>();

const show = ref(true);

if (page.props.flash?.success || page.props.flash?.error) {
    setTimeout(() => {
        show.value = false;
    }, 4000);
}

// Filtros
const search = ref('');
const documentType = ref<'all' | 'cpf' | 'cnpj'>('all');

const hasActiveFilters = computed(() => {
    return search.value.trim() !== '' || documentType.value !== 'all';
});

const clearFilters = () => {
    search.value = '';
    documentType.value = 'all';
};

const cleanDigits = (val?: string | null) => {
    return val ? val.replace(/\D/g, '') : '';
};

const filteredCustomers = computed(() => {
    const list = props.customer || [];
    const query = search.value.trim().toLowerCase();
    const queryDigits = cleanDigits(query);

    return list.filter(cust => {
        // Filtro por tipo de documento
        if (documentType.value === 'cpf' && !cust.cpf) {
            return false;
        }
        
        if (documentType.value === 'cnpj' && !cust.cnpj) {
            return false;
        }

        // Filtro textual
        if (!query) {
            return true;
        }

        const nameMatch = cust.name?.toLowerCase().includes(query);
        const emailMatch = cust.email?.toLowerCase().includes(query);
        const cpfMatch = cust.cpf?.toLowerCase().includes(query) || (queryDigits && cleanDigits(cust.cpf).includes(queryDigits));
        const cnpjMatch = cust.cnpj?.toLowerCase().includes(query) || (queryDigits && cleanDigits(cust.cnpj).includes(queryDigits));
        const phoneMatch = cust.phone?.toLowerCase().includes(query) || (queryDigits && cleanDigits(cust.phone).includes(queryDigits));

        return Boolean(nameMatch || emailMatch || cpfMatch || cnpjMatch || phoneMatch);
    });
});
</script>

<template>
    <Head title="Clientes - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: '+ Novo Cliente',
                url: customers.create()
            },
            {
                label: 'Listar Endereços',
                url: address.index()
            }
        ]"/>

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Clientes</h1>
                    <p class="text-sm text-zinc-200 mt-0.5">Gerenciamento de clientes cadastrados</p>
                </div>
                <Link
                    :href="customers.create()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors"
                >
                    <span>+</span>
                    <span>Novo Cliente</span>
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
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 mb-5 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-sm">
                <div class="flex flex-1 flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar por nome, documento, e-mail ou telefone..."
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

                    <!-- Document Type Filter -->
                    <div class="w-full sm:w-52">
                        <select
                            v-model="documentType"
                            class="w-full bg-zinc-950 border border-zinc-700/80 rounded-lg px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="all">Todos os documentos</option>
                            <option value="cpf">Pessoa Física (CPF)</option>
                            <option value="cnpj">Pessoa Jurídica (CNPJ)</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <button
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="px-3 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium rounded-lg border border-zinc-700 transition-colors whitespace-nowrap"
                    >
                        Limpar Filtros
                    </button>
                </div>

                <!-- Results Counter Badge -->
                <div class="text-xs text-zinc-400 self-end sm:self-center font-medium">
                    Exibindo <span class="text-zinc-200 font-semibold">{{ filteredCustomers.length }}</span> de <span class="text-zinc-200 font-semibold">{{ props.customer?.length || 0 }}</span> clientes
                </div>
            </div>

            <!-- Solid Dark Data Table Card -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-300">
                        <thead class="bg-zinc-950/70 border-b border-zinc-800 text-xs uppercase tracking-wider text-zinc-200 font-semibold">
                            <tr>
                                <th class="py-3 px-4 ">Nome</th>
                                <th class="py-3 px-4 text-center">Documento</th>
                                <th class="py-3 px-4">Telefone</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80 font-normal">
                            <tr
                                v-for="cust in filteredCustomers"
                                :key="cust.id"
                                class="hover:bg-zinc-800/50 transition-colors"
                            >
                                <td class="py-3.5 px-4 font-medium text-white">
                                    {{ cust.name }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span v-if="cust.cpf && cust.cnpj" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                        CPF: {{ cust.cpf }} | CNPJ: {{ cust.cnpj }}
                                    </span>
                                    <span v-else-if="cust.cpf" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                        CPF: {{ cust.cpf }}
                                    </span>
                                    <span v-else-if="cust.cnpj" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                        CNPJ: {{ cust.cnpj }}
                                    </span>
                                    <span v-else class="text-zinc-600">—</span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-300">
                                    {{ cust.phone || '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-zinc-300">
                                    {{ cust.email || '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="customers.edit(cust.id)"
                                            class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-600 border border-zinc-700 text-zinc-200 hover:text-white text-xs font-medium rounded transition-colors"
                                        >
                                            Editar
                                        </Link>
                                        <Link
                                            :href="customers.destroy(cust.id)"
                                            class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/60 active:bg-rose-800/70 border border-rose-800/60 text-rose-300 hover:text-rose-100 text-xs font-medium rounded transition-colors"
                                        >
                                            Excluir
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredCustomers.length === 0">
                                <td colspan="5" class="py-12 text-center text-zinc-500">
                                    <div v-if="hasActiveFilters" class="flex flex-col items-center gap-2">
                                        <span>Nenhum cliente encontrado com os filtros aplicados.</span>
                                        <button
                                            @click="clearFilters"
                                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium underline"
                                        >
                                            Limpar filtros
                                        </button>
                                    </div>
                                    <div v-else>
                                        Nenhum cliente cadastrado ainda.
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
