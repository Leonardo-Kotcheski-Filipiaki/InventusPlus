<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import salesRoutes from '@/routes/sales';
import type Customer from '@/types/Customer';
import type Sale from '@/types/Sale';
import type Stock from '@/types/Stock';
import type Supplier from '@/types/Supplier';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const props = defineProps<{
    sale: Sale;
    stocks: Stock[];
    customers: Customer[];
    suppliers: Supplier[];
}>();

const form = useForm({
    sales_type: props.sale.sales_type || 'saida',
    supplier_id: props.sale.supplier_id || '',
    customer_id: props.sale.customer_id || '',
    stock_id: props.sale.stock_id || '',
    quantity: props.sale.quantity || 1,
    unit_value: String(props.sale.unit_value || ''),
    description: props.sale.description || '',
});

const showElement = ref(true);
const page = usePage();

// Produto selecionado no momento
const selectedProduct = computed(() => {
    if (!form.stock_id) return null;
    return props.stocks.find((s) => Number(s.id) === Number(form.stock_id)) || null;
});

// Ao selecionar o produto, preenche o valor unitário automaticamente com o preço cadastrado caso mude
const handleProductChange = () => {
    if (selectedProduct.value) {
        form.unit_value = String(selectedProduct.value.unit_value);
    }
};

// Ao alternar o tipo, limpa a seleção do parceiro que não se aplica
watch(
    () => form.sales_type,
    (newType) => {
        if (newType === 'entrada') {
            form.customer_id = '';
        } else {
            form.supplier_id = '';
        }
    }
);

// Cálculo do Total
const totalValue = computed(() => {
    const qty = Number(form.quantity) || 0;
    const unit = Number(form.unit_value) || 0;
    return qty * unit;
});

const formatCurrency = (value: number | string) => {
    const num = Number(value);
    if (isNaN(num)) return 'R$ 0,00';
    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

const submit = () => {
    form.put(salesRoutes.update.url(props.sale.id), {
        onFinish: () => {
            if (page.props.flash?.warning || page.props.errors?.error) {
                showElement.value = true;
                setTimeout(() => {
                    showElement.value = false;
                }, 4000);
            }
        },
    });
};
</script>

<template>
    <Head :title="`Editar Movimentação #${sale.id} - Inventus +`" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Histórico de Vendas',
                url: salesRoutes.index()
            },
            {
                label: 'Estoque de Produtos',
                url: '/stocks'
            }
        ]"/>

        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-8">
            <!-- Breadcrumbs / Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Editar Movimentação / Venda #{{ sale.id }}</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Atualize os dados da movimentação conforme necessário</p>
                </div>
                <Link
                    :href="salesRoutes.index()"
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
                    <!-- Seletor de Tipo (Entrada vs Saída) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-2">
                            Tipo de Operação <span class="text-indigo-400">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Opção: Saída (Venda) -->
                            <label
                                :class="[
                                    'flex items-center justify-center gap-2.5 p-3.5 rounded-lg border cursor-pointer transition-all',
                                    form.sales_type === 'saida'
                                        ? 'bg-indigo-950/50 border-indigo-500 text-white shadow-sm shadow-indigo-950'
                                        : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:border-zinc-700'
                                ]"
                            >
                                <input
                                    type="radio"
                                    name="sales_type"
                                    value="saida"
                                    v-model="form.sales_type"
                                    class="sr-only"
                                />
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                                <div class="text-left">
                                    <div class="text-xs font-bold">Saída (Venda)</div>
                                    <div class="text-[11px] text-zinc-400">Venda direta para cliente</div>
                                </div>
                            </label>

                            <!-- Opção: Entrada (Compra / Reposição) -->
                            <label
                                :class="[
                                    'flex items-center justify-center gap-2.5 p-3.5 rounded-lg border cursor-pointer transition-all',
                                    form.sales_type === 'entrada'
                                        ? 'bg-emerald-950/50 border-emerald-500 text-white shadow-sm shadow-emerald-950'
                                        : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:border-zinc-700'
                                ]"
                            >
                                <input
                                    type="radio"
                                    name="sales_type"
                                    value="entrada"
                                    v-model="form.sales_type"
                                    class="sr-only"
                                />
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                <div class="text-left">
                                    <div class="text-xs font-bold">Entrada (Reposição)</div>
                                    <div class="text-[11px] text-zinc-400">Compra de fornecedor</div>
                                </div>
                            </label>
                        </div>
                        <span v-if="form.errors.sales_type" class="block mt-1 text-xs text-rose-400">
                            {{ form.errors.sales_type }}
                        </span>
                    </div>

                    <!-- Campos em Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Parceiro Dinâmico: Cliente (se Saída) ou Fornecedor (se Entrada) -->
                        <div v-if="form.sales_type === 'saida'" class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="customer" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Cliente <span class="text-indigo-400">*</span>
                                </label>
                                <Link
                                    href="/customers/create"
                                    target="_blank"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 font-medium"
                                >
                                    + Novo Cliente
                                </Link>
                            </div>
                            <select
                                id="customer"
                                v-model="form.customer_id"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            >
                                <option value="" disabled>Selecione o cliente comprador</option>
                                <option
                                    v-for="c in customers"
                                    :key="c.id"
                                    :value="c.id"
                                >
                                    {{ c.name }} {{ c.cpf ? `(CPF: ${c.cpf})` : '' }} {{ c.cnpj ? `(CNPJ: ${c.cnpj})` : '' }}
                                </option>
                            </select>
                            <span v-if="form.errors.customer_id" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.customer_id }}
                            </span>
                        </div>

                        <div v-else class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="supplier" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Fornecedor <span class="text-emerald-400">*</span>
                                </label>
                                <Link
                                    href="/suppliers/create"
                                    target="_blank"
                                    class="text-xs text-emerald-400 hover:text-emerald-300 font-medium"
                                >
                                    + Novo Fornecedor
                                </Link>
                            </div>
                            <select
                                id="supplier"
                                v-model="form.supplier_id"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                            >
                                <option value="" disabled>Selecione o fornecedor</option>
                                <option
                                    v-for="s in suppliers"
                                    :key="s.id"
                                    :value="s.id"
                                >
                                    {{ s.name }} {{ s.cnpj ? `(CNPJ: ${s.cnpj})` : '' }}
                                </option>
                            </select>
                            <span v-if="form.errors.supplier_id" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.supplier_id }}
                            </span>
                        </div>

                        <!-- Produto -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="stock" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Produto <span class="text-indigo-400">*</span>
                                </label>
                                <Link
                                    href="/stocks/create"
                                    target="_blank"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 font-medium"
                                >
                                    + Cadastrar Novo Produto
                                </Link>
                            </div>
                            <select
                                id="stock"
                                v-model="form.stock_id"
                                @change="handleProductChange"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            >
                                <option value="" disabled>Selecione o produto</option>
                                <option
                                    v-for="item in stocks"
                                    :key="item.id"
                                    :value="item.id"
                                >
                                    {{ item.name }} (Estoque atual: {{ item.quantity }} un | Preço padrão: {{ formatCurrency(item.unit_value) }})
                                </option>
                            </select>
                            <span v-if="form.errors.stock_id" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.stock_id }}
                            </span>

                            <!-- Dica de Estoque -->
                            <div v-if="selectedProduct" class="mt-2 text-xs flex items-center gap-2">
                                <span class="text-zinc-400">Estoque disponível:</span>
                                <span
                                    :class="[
                                        'font-mono font-semibold px-1.5 py-0.5 rounded text-[11px]',
                                        Number(selectedProduct.quantity) <= 0
                                            ? 'bg-rose-950/60 text-rose-400 border border-rose-800'
                                            : 'bg-zinc-800 text-zinc-200 border border-zinc-700'
                                    ]"
                                >
                                    {{ selectedProduct.quantity }} unidades
                                </span>
                            </div>
                        </div>

                        <!-- Quantidade -->
                        <div>
                            <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Quantidade <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="quantity"
                                v-model="form.quantity"
                                type="number"
                                min="1"
                                step="1"
                                required
                                placeholder="1"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors font-mono"
                            />
                            <span v-if="form.errors.quantity" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.quantity }}
                            </span>
                        </div>

                        <!-- Valor Unitário (Editável) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="unit_value" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                    Valor Unitário (R$) <span class="text-indigo-400">*</span>
                                </label>
                                <span class="text-[11px] text-zinc-500">Pode ser alterado</span>
                            </div>
                            <input
                                id="unit_value"
                                v-model="form.unit_value"
                                type="number"
                                min="0"
                                step="0.01"
                                required
                                placeholder="0.00"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors font-mono"
                            />
                            <span v-if="form.errors.unit_value" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.unit_value }}
                            </span>
                        </div>

                        <!-- Descrição / Observações -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Descrição / Observações da Venda
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                placeholder="Ex: Venda à vista com desconto aplicado, NF #1234..."
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none"
                            ></textarea>
                            <span v-if="form.errors.description" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.description }}
                            </span>
                        </div>
                    </div>

                    <!-- Resumo Financeiro da Transação -->
                    <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Total da Operação</span>
                            <p class="text-xs text-zinc-500 mt-0.5">
                                {{ form.quantity || 0 }} un × {{ formatCurrency(form.unit_value || 0) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div
                                :class="[
                                    'text-xl font-bold font-mono',
                                    form.sales_type === 'entrada' ? 'text-emerald-400' : 'text-indigo-400'
                                ]"
                            >
                                {{ formatCurrency(totalValue) }}
                            </div>
                            <span class="text-[11px] text-zinc-500">
                                {{ form.sales_type === 'entrada' ? 'Valor a pagar (Reposição)' : 'Valor a receber (Venda)' }}
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                        <Link
                            :href="salesRoutes.index()"
                            class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-semibold rounded-lg border border-zinc-700 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                        >
                            <span v-if="form.processing">Salvando alterações...</span>
                            <span v-else>Salvar Alterações</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
