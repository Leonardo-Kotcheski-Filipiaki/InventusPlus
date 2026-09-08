<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import address from '@/routes/address';
import customers from '@/routes/customers';
import type AddressType from '@/types/AddressType';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';

const props = defineProps<{
    addressArray: AddressType[] | [];
}>();

</script>

<template>
    <Head title="Endereços" />
    <div class="bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />
        <SubMenu :options="[
            {
                label: 'Voltar',
                url: customers.index()
            }
        ]"/>
        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Endereços</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">Endereços cadastrados</p>
                </div>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 shadow-sm">
                <div v-if="addressArray.length < 1">
                    <span>Nenhum endereço cadastrado</span>
                </div>
                <table class="w-full" v-else>
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Cliente</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">CEP</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Rua</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Número</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Bairro</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Complemento</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Cidade</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Estado</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="addr in props.addressArray" :key="addr.id">
                            <td class="py-2 text-sm">{{ addr.customer?.name ?? addr.costumer?.name ?? 'Não vinculado' }}</td>
                            <td class="py-2 text-sm">{{ addr.zip_code }}</td>
                            <td class="py-2 text-sm">{{ addr.street }}</td>
                            <td class="py-2 text-sm">{{ addr.number }}</td>
                            <td class="py-2 text-sm">{{ addr.neighborhood }}</td>
                            <td class="py-2 text-sm">{{ addr.complement }}</td>
                            <td class="py-2 text-sm">{{ addr.city }}</td>
                            <td class="py-2 text-sm">{{ addr.state }}</td>
                            <td class="py-2 text-sm">
                                <Link :href="address.edit.url(addr.id)" class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-600 border border-zinc-700 text-zinc-200 hover:text-white text-xs font-medium rounded transition-colors">
                                    Editar
                                </Link>
                                <Link :href="address.destroy.url(addr.id)" class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900/60 active:bg-rose-800/70 border border-rose-800/60 text-rose-300 hover:text-rose-100 text-xs font-medium rounded transition-colors">
                                    Excluir
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</template>