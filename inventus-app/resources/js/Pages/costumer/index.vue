<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import costumers from '@/routes/costumers';
import type Costumer from '@/types/Costumer';
import { ref } from 'vue';

const page = usePage();

const props = defineProps <{
    costumer: Costumer[];
}>()

const show = ref(true);

if (page.props.flash.success || page.props.flash.error) {
    setTimeout(() => {
        show.value = false;
    }, 3000);
}
</script>


<template>
    <Head title="Costumers" />
    <Nav />
    <SubMenu :options="[
        {
            label: 'Listar',
            url: costumers.index()
        },
        {
            label: 'Adicionar',
            url: costumers.create()
        }
    ]"/>
    <!-- Style Table -->
    <div class="flex justify-center items-center w-full top-32 absolute">
        <span class="flex items-center bg-green-500 px-10 py-2 font-bold rounded-md text-white relative z-50" v-if="page.props.flash.success && show" >{{ page.props.flash.success }}</span>
        <span class="flex items-center px-10 py-2 font-bold rounded-md text-white" v-if="!page.props.flash.success && !show"></span>

        <span class="flex items-center bg-red-500 px-10 py-2 font-bold rounded-md text-white relative z-50" v-if="page.props.flash.error && show" >{{ page.props.flash.error }}</span>
        <span class="flex items-center px-10 py-2 font-bold rounded-md text-white" v-if="!page.props.flash.error && !show"></span>
    </div>
    <div class="flex justify-center items-center w-full mt-20">
        <table class="table-auto border-collapse border border-gray-200 w-[80%]">
            <thead class="flex flex-col">
                <tr class="bg-gray-200 flex">
                    <th class="w-full flex justify-center">Nome</th>
                    <th class="w-full flex justify-center">CPF</th>
                    <th class="w-full flex justify-center">CNPJ</th>
                    <th class="w-full flex justify-center">Telefone</th>
                    <th class="w-full flex justify-center">Email</th>
                    <th class="w-full flex justify-center">Ações</th>
                </tr>
            </thead>
            <tbody class="flex flex-col w-full">
                <tr v-for="cost in costumer" :key="cost.id" class="w-full flex h-10 border-b-1 border-gray-200 hover:bg-gray-200 text-center items-center">
                    <td class="w-full flex justify-center">{{ cost.person.name }}</td>
                    <td class="w-full flex justify-center">{{ cost.person.cpf ? cost.person.cpf : '-' }}</td>
                    <td class="w-full flex justify-center">{{ cost.person.cnpj ? cost.person.cnpj : '-' }}</td>
                    <td class="w-full flex justify-center">{{ cost.person.phone }}</td>
                    <td class="w-full flex justify-center">{{ cost.person.email }}</td>
                    <td class="w-full flex justify-center gap-2">
                        <Link :href="costumers.edit(cost.id)" class="bg-blue-500 text-white px-2 py-1 rounded cursor-pointer">Editar</Link>
                        <Link :href="costumers.destroy(cost.id)" class="bg-red-500 text-white px-2 py-1 rounded cursor-pointer">Excluir</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
