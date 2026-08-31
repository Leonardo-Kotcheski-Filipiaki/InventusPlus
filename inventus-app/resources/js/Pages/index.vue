<script setup lang="ts">
import Nav from '@/ui/MainNav.vue';
import { Head, Link } from '@inertiajs/vue3';
import costumers from '@/routes/costumers';
</script>

<template>
    <Head title="Painel - Inventus +" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">
        <Nav />

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-10">
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white tracking-tight">Painel Principal</h1>
                <p class="text-zinc-400 text-sm mt-1">Bem-vindo ao Inventus Plus. Selecione um módulo para começar.</p>
            </div>

            <!-- Quick Access Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Clientes Card -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center font-bold text-sm mb-4">
                            CLI
                        </div>
                        <h2 class="text-lg font-semibold text-white">Clientes</h2>
                        <p class="text-xs text-zinc-400 mt-1 leading-relaxed">
                            Cadastre, edite e consulte a listagem completa de clientes e seus documentos.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <Link
                            :href="costumers.index()"
                            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors"
                        >
                            Ver Clientes
                        </Link>
                        <Link
                            :href="costumers.create()"
                            class="px-3.5 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-lg border border-zinc-700 transition-colors"
                        >
                            + Novo Cliente
                        </Link>
                    </div>
                </div>

                <!-- Estoque Card -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-bold text-sm mb-4">
                            EST
                        </div>
                        <h2 class="text-lg font-semibold text-white">Estoque</h2>
                        <p class="text-xs text-zinc-400 mt-1 leading-relaxed">
                            Controle a entrada e saída de produtos, fornecedores e níveis de estoque.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <Link
                            href="/stock"
                            class="px-3.5 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-lg border border-zinc-700 transition-colors"
                        >
                            Acessar Estoque
                        </Link>
                    </div>
                </div>

                <!-- Sistema / Perfil Card -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 text-zinc-300 border border-zinc-700 flex items-center justify-center font-bold text-sm mb-4">
                            SYS
                        </div>
                        <h2 class="text-lg font-semibold text-white">Configurações</h2>
                        <p class="text-xs text-zinc-400 mt-1 leading-relaxed">
                            Gerenciamento de usuários, permissões e preferências do sistema.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <span class="text-xs text-zinc-500 font-medium">Sistema v1.0.0</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>