<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface MenuOption {
    label: string;
    url: string;
}

defineProps <{
    options: MenuOption[];
}>()
</script>

<template>
    <div class="bg-neutral-500 h-10 w-screen p-5 flex items-center text-white font-semibold text-lg flex-row-reverse">
        <ul class="flex flex-row gap-10">
            <li v-for="option in options" :key="option.label">
                <Link :href="option.url" class="hover:text-blue-200 transition-colors duration-300">
                    {{ option.label }}
                </Link>
            </li>
        </ul>
    </div>
</template>