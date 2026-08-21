<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import costumers from '@/routes/costumers';
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
</template>
