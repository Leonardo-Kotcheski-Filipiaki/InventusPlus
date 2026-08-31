<script setup lang="ts">
import costumers from '@/routes/costumers';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <nav class="bg-zinc-900 border-b border-zinc-800 text-zinc-100 px-6 py-3.5 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-8">
            <Link href="/" class="flex items-center gap-2 text-lg font-bold tracking-tight text-white hover:text-indigo-400 transition-colors">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-indigo-600 text-white text-xs font-black">
                    I+
                </span>
                <span>Inventus<span class="text-indigo-400">+</span></span>
            </Link>

            <ul class="flex items-center gap-1 text-sm font-medium">
                <li>
                    <Link
                        href="/"
                        class="px-3 py-1.5 rounded-md transition-colors hover:bg-zinc-800 hover:text-white"
                        :class="page.url === '/' ? 'bg-zinc-800 text-white font-semibold' : 'text-zinc-400'"
                    >
                        Início
                    </Link>
                </li>
                <li>
                    <Link
                        :href="costumers.index()"
                        class="px-3 py-1.5 rounded-md transition-colors hover:bg-zinc-800 hover:text-white"
                        :class="page.url.startsWith('/costumers') ? 'bg-zinc-800 text-white font-semibold' : 'text-zinc-400'"
                    >
                        Clientes
                    </Link>
                </li>
                <li>
                    <Link
                        href="/stock"
                        class="px-3 py-1.5 rounded-md transition-colors hover:bg-zinc-800 hover:text-white"
                        :class="page.url.startsWith('/stock') ? 'bg-zinc-800 text-white font-semibold' : 'text-zinc-400'"
                    >
                        Estoque
                    </Link>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-4 text-sm">
            <div v-if="page.props.auth?.person" class="flex items-center gap-2.5 px-3 py-1 bg-zinc-800/80 border border-zinc-700/60 rounded-md">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-zinc-200 font-medium">{{ page.props.auth.person.name }}</span>
            </div>

            <Link
                href="/logout"
                class="px-3 py-1.5 text-zinc-400 hover:text-rose-400 hover:bg-zinc-800 rounded-md transition-colors text-xs font-semibold uppercase tracking-wider"
            >
                Sair
            </Link>
        </div>
    </nav>
</template>