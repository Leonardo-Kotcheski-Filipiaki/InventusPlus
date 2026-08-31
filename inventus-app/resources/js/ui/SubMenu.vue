<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface MenuOption {
    label: string;
    url: string | any;
}

defineProps<{
    options: MenuOption[];
}>();
</script>


<template>
    <div class="bg-zinc-900/60 border-b border-zinc-800/80 px-6 py-2.5 flex items-center justify-between">
        <div class="text-xs font-semibold uppercase tracking-wider text-zinc-500">
            Ações Rápidas
        </div>
        <ul class="flex items-center gap-2">
            <li v-for="option in options" :key="option.label">
                <Link
                    :href="option.url"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-md transition-colors"
                >
                    <span>+</span>
                    <span>{{ option.label }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>