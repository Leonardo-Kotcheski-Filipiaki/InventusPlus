<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import costumers from '@/routes/costumers';
import address from '@/routes/address';
import { ref } from 'vue';

const props = defineProps({
    costumer: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name: props.costumer.person?.name || '',
    cpf: props.costumer.person?.cpf || '',
    cnpj: props.costumer.person?.cnpj || '',
    birthdate: props.costumer.person?.birthdate || '',
    email: props.costumer.person?.email || '',
    phone: props.costumer.person?.phone || ''
});

const showElement = ref(true);
const page = usePage();

const submit = () => {
    form.patch(costumers.update.url(props.costumer.id), {
        preserveState: true,
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
    <Head title="Alterar Cliente - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Listar Clientes',
                url: costumers.index()
            },
            {
                label: 'Novo Cliente',
                url: costumers.create()
            }
        ]"/>

        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-8">
            <!-- Breadcrumbs / Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Editar Cliente</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Atualize os dados de {{ form.name || 'cliente' }}</p>
                </div>
                <Link
                    :href="costumers.index()"
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
                                Nome Completo <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Ex: João da Silva"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.name" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <!-- CPF -->
                        <div>
                            <label for="cpf" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                CPF
                            </label>
                            <input
                                id="cpf"
                                v-model="form.cpf"
                                type="text"
                                placeholder="000.000.000-00"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.cpf" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.cpf }}
                            </span>
                        </div>

                        <!-- CNPJ -->
                        <div>
                            <label for="cnpj" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                CNPJ
                            </label>
                            <input
                                id="cnpj"
                                v-model="form.cnpj"
                                type="text"
                                placeholder="00.000.000/0000-00"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.cnpj" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.cnpj }}
                            </span>
                        </div>

                        <!-- Data de Nascimento -->
                        <div>
                            <label for="birthdate" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Data de Nascimento <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="birthdate"
                                v-model="form.birthdate"
                                type="date"
                                required
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors [color-scheme:dark]"
                            />
                            <span v-if="form.errors.birthdate" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.birthdate }}
                            </span>
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Telefone <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                required
                                placeholder="(00) 00000-0000"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.phone" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.phone }}
                            </span>
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Email <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="cliente@email.com"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.email" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.email }}
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                     
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-800">
                        <div>
                            <Link
                                :href="address.create(props.costumer.id)"
                                class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-semibold rounded-lg border border-zinc-700 transition-colors"
                            >
                                Adicionar Endereço
                            </Link>
                        </div>

                        <div class="flex gap-3">
                            <Link
                                :href="costumers.index()"
                                class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-semibold rounded-lg border border-zinc-700 transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                            >
                                <span v-if="form.processing">Atualizando...</span>
                                <span v-else>Atualizar Cliente</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

