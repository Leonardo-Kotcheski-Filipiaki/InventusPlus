<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import type Costumer from '@/types/Costumer';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import address from '@/routes/address';

const form = useForm({
    zip_code: '',
    street: '',
    number: '',
    complement: '',
    neighborhood: '',
    city: '',
    state: ''
});

const showElement = ref(true);
const page = usePage();

const props = defineProps<{
    costumer: Costumer;
}>();


const submit = () => {
    // Submit address logic
    form.transform((data) => ({
        ...data
    })).post(address.store.url(props.costumer.id), {
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
    <Head title="Cadastrar Endereço - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Voltar',
                url: '/'
            }
        ]"/>

        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Cadastrar Endereço para cliente {{ props.costumer.person.name }}</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Preencha os dados de localização</p>
                </div>
                <Link
                    href="/"
                    class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-lg border border-zinc-700 transition-colors"
                >
                    Voltar
                </Link>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 shadow-sm">
                <form @submit.prevent="submit()" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="zip_code" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                CEP
                            </label>
                            <input
                                id="zip_code"
                                v-model="form.zip_code"
                                type="text"
                                placeholder="00000-000"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.zip_code" class="text-red-500 text-xs mt-1">
                                {{ form.errors.zip_code }}
                            </div>
                        </div>

                        <div>
                            <label for="street" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Rua / Logradouro
                            </label>
                            <input
                                id="street"
                                v-model="form.street"
                                type="text"
                                placeholder="Ex: Av. Principal"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.street" class="text-red-500 text-xs mt-1">
                                {{ form.errors.street }}
                            </div>
                        </div>

                        <div>
                            <label for="number" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Número
                            </label>
                            <!-- Only numbers permit, not E or any letter -->
                            <input
                                id="number"
                                v-model="form.number"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                placeholder="123"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.number" class="text-red-500 text-xs mt-1">
                                {{ form.errors.number }}
                            </div>
                        </div>

                        <div>
                            <label for="neighborhood" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Bairro
                            </label>
                            <input
                                id="neighborhood"
                                v-model="form.neighborhood"
                                type="text"
                                placeholder="Ex: Centro"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.neighborhood" class="text-red-500 text-xs mt-1">
                                {{ form.errors.neighborhood }}
                            </div>
                        </div>

                        <div>
                            <label for="complement" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Complemento
                            </label>
                            <input
                                id="complement"
                                v-model="form.complement"
                                type="text"
                                placeholder="Apto, Sala, Bloco"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.complement" class="text-red-500 text-xs mt-1">
                                {{ form.errors.complement }}
                            </div>
                        </div>

                        <div>
                            <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Cidade
                            </label>
                            <input
                                id="city"
                                v-model="form.city"
                                type="text"
                                placeholder="Ex: Curitiba"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <div v-if="form.errors.city" class="text-red-500 text-xs mt-1">
                                {{ form.errors.city }}
                            </div>
                        </div>

                        <div>
                            <label for="state" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Estado (UF)
                            </label>
                            <input
                                id="state"
                                v-model="form.state"
                                type="text"
                                placeholder="PR"
                                maxlength="2"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors uppercase"
                            />
                            <div v-if="form.errors.state" class="text-red-500 text-xs mt-1">
                                {{ form.errors.state }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                        <Link
                            href="/"
                            class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-semibold rounded-lg border border-zinc-700 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                        >
                            Salvar Endereço
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
