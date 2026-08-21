<script setup lang="ts">
import costumers from '@/routes/costumers';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div class="bg-neutral-700 flex items-center justify-between w-100% h-0 text-slate-100 text-xl font-bold p-10">
        <ul class="flex flex-row gap-10">
            <li>
                <Link v-bind:href="'/'" class="hover:text-blue-400 transition-colors duration-300">
                Home
                </Link>
            </li>
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
            <li>
                <Link v-bind:href="'/logout'" class="hover:text-blue-400 transition-colors duration-300">
                    Logout
                </Link>
            </li>
        </ul>
        <p>{{ page.props.auth.person.name }}</p>
    </div>
</template>