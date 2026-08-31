<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import type AddressType from '@/types/AddressType';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import address from '@/routes/address';

const props = defineProps<{
    address: AddressType;
}>();

const form = useForm({
    zip_code: props.address.zip_code || '',
    street: props.address.street || '',
    number: props.address.number || '',
    complement: props.address.complement || '',
    neighborhood: props.address.neighborhood || '',
    city: props.address.city || '',
    state: props.address.state || ''
});

const showElement = ref(true);
const page = usePage();

const submit = () => {
    form.patch(address.update.url(props.address.id), {
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
    <Head title="Editar Endereço - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Listar Endereços',
                url: address.index()
            }
        ]"/>

        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">
                        Editar Endereço
                        <span v-if="props.address.costumer?.person?.name">
                            - {{ props.address.costumer.person.name }}
                        </span>
                    </h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Atualize as informações de localização</p>
                </div>
                <Link
                    :href="address.index()"
                    class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-lg border border-zinc-700 transition-colors"
                >
                    Voltar
                </Link>
            </div>

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
                        <!-- CEP -->
                        <div>
                            <label for="zip_code" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                CEP <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="zip_code"
                                v-model="form.zip_code"
                                type="text"
                                required
                                placeholder="00000-000"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.zip_code" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.zip_code }}
                            </span>
                        </div>

                        <!-- Rua -->
                        <div>
                            <label for="street" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Rua / Logradouro <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="street"
                                v-model="form.street"
                                type="text"
                                required
                                placeholder="Ex: Av. Principal"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.street" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.street }}
                            </span>
                        </div>

                        <!-- Número -->
                        <div>
                            <label for="number" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Número <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="number"
                                v-model="form.number"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                required
                                placeholder="123"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.number" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.number }}
                            </span>
                        </div>

                        <!-- Bairro -->
                        <div>
                            <label for="neighborhood" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Bairro <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="neighborhood"
                                v-model="form.neighborhood"
                                type="text"
                                required
                                placeholder="Ex: Centro"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.neighborhood" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.neighborhood }}
                            </span>
                        </div>

                        <!-- Complemento -->
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
                            <span v-if="form.errors.complement" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.complement }}
                            </span>
                        </div>

                        <!-- Cidade -->
                        <div>
                            <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Cidade <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="city"
                                v-model="form.city"
                                type="text"
                                required
                                placeholder="Ex: Curitiba"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                            <span v-if="form.errors.city" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.city }}
                            </span>
                        </div>

                        <!-- Estado -->
                        <div class="md:col-span-2">
                            <label for="state" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                Estado (UF) <span class="text-indigo-400">*</span>
                            </label>
                            <input
                                id="state"
                                v-model="form.state"
                                type="text"
                                required
                                placeholder="PR"
                                maxlength="2"
                                class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3.5 py-2.5 text-zinc-100 placeholder-zinc-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors uppercase"
                            />
                            <span v-if="form.errors.state" class="block mt-1 text-xs text-rose-400">
                                {{ form.errors.state }}
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                        <Link
                            :href="address.index()"
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
                            <span v-else>Atualizar Endereço</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

