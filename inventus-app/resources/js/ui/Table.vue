<script setup lang="ts">
defineProps<{
    columns?: { key: string; label: string }[];
    items: any[];
}>();
</script>

<template>
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left text-sm text-zinc-300">
            <thead class="bg-zinc-950/70 border-b border-zinc-800 text-xs uppercase tracking-wider text-zinc-400 font-semibold">
                <tr>
                    <slot name="header">
                        <th v-for="(col, index) in columns" :key="index" class="py-3 px-4">
                            {{ col.label }}
                        </th>
                    </slot>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/80">
                <slot name="rows">
                    <tr v-for="(item, index) in items" :key="index" class="hover:bg-zinc-800/50 transition-colors">
                        <td v-for="(col, cIndex) in columns" :key="cIndex" class="py-3.5 px-4">
                            {{ item[col.key] }}
                        </td>
                    </tr>
                </slot>
            </tbody>
        </table>
    </div>
</template>

