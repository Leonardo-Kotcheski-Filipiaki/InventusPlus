<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import stocks from '@/routes/stocks';
import type StockCategory from '@/types/StockCategory';
import type Supplier from '@/types/Supplier';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

defineProps<{
    suppliers: Supplier[];
    categories: StockCategory[];
}>();

const form = useForm({
    name: '',
    description: '',
    quantity: 0,
    unit_value: '',
    supplier_id: '',
    stock_category_id: ''
});

const showElement = ref(true);
const page = usePage();

const submit = () => {
    form.post(stocks.store.url(), {
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
    <Head title="Adicionar Produto - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Listar Estoque',
                url: stocks.index()
            },
            {
                label: 'Categorias de Produtos',
                url: '/stock-categories'
            }
        ]"/>

        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-8">
            <!-- Breadcrumbs / Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Cadastrar Novo Produto</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Preencha os dados do produto para o estoque</p>
                </div>
                <Link
                    :href="stocks.index()"
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nome -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Nome do Produto <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Ex: Parafuso Sextavado M8"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.name" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <!-- Descrição -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Descrição
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                placeholder="Detalhes, especificações ou observações do produto..."
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none"
                            ></textarea>
                            <span v-if="form.errors.description" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <!-- Categoria -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Categoria <span class="text-indigo-400">*</span>
                                </label>
                                <Link
                                    href="/stock-categories/create"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 font-medium"
                                >
                                    + Nova Categoria
                                </Link>
                            </div>
                            <select
                                id="category"
                                v-model="form.stock_category_id"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            >
                                <option value="" disabled>Selecione uma categoria</option>
                                <option
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id"
                                >
                                    {{ cat.name }}
                                </option>
                            </select>
                            <span v-if="form.errors.stock_category_id" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.stock_category_id }}
                            </span>
                        </div>

                        <!-- Fornecedor -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="supplier" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Fornecedor <span class="text-indigo-400">*</span>
                                </label>
                                <Link
                                    href="/suppliers/create"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 font-medium"
                                >
                                    + Novo Fornecedor
                                </Link>
                            </div>
                            <select
                                id="supplier"
                                v-model="form.supplier_id"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            >
                                <option value="" disabled>Selecione um fornecedor</option>
                                <option
                                    v-for="supp in suppliers"
                                    :key="supp.id"
                                    :value="supp.id"
                                >
                                    {{ supp.name }}
                                </option>
                            </select>
                            <span v-if="form.errors.supplier_id" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.supplier_id }}
                            </span>
                        </div>

                        <!-- Quantidade -->
                        <div>
                            <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Quantidade em Estoque <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="quantity"
                                v-model="form.quantity"
                                type="number"
                                min="0"
                                step="1"
                                required
                                placeholder="0"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.quantity" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.quantity }}
                            </span>
                        </div>

                        <!-- Valor Unitário -->
                        <div>
                            <label for="unit_value" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Valor Unitário (R$) <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="unit_value"
                                v-model="form.unit_value"
                                type="number"
                                min="0"
                                step="0.01"
                                required
                                placeholder="0.00"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.unit_value" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.unit_value }}
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                        <Link
                            :href="stocks.index()"
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
                            <span v-else>Salvar Produto</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
