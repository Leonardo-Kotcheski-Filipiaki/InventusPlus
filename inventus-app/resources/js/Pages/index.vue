<script setup lang="ts">
import costumers from '@/routes/costumers';
import { Head, Link, router } from '@inertiajs/vue3';

</script>

<template>
    <!-- 
     This is a stock management, costumers, suppliers, products and sales 
     The main page must have the menu with the links to all the other pages 
     The page must have the user name in the header
     the page must have the logout button in the header
     the page must have the current date in the header
     the page must have the current time in the header
     the page must have the company name and logo in the header
     the page must have the company logo in the header
     the page must have the company name in the header
     the page must have the company address in the header
    -->

    <Head title="Inventus +" />
    <div class="bg-blue-700 flex items-center justify-between w-100% h-20 text-slate-100 text-2xl font-bold">
        <ul class="flex flex-row gap-10 ml-5">
            <li>
                <Link v-bind:href="costumers.index()" class="hover:text-blue-400 transition-colors duration-300">
                Costumers
                </Link>
            </li>
            <li>
                <Link v-bind:href="costumers.index()" class="hover:text-blue-400 transition-colors duration-300">
                    Stock
                </Link>
            </li>
        </ul>
        
    </div>
</template>