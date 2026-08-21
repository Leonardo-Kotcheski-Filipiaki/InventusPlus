<script setup lang="ts">


defineProps <{
    items: any[];
}>()
</script>

<template>
    <table>
        <thead>
            <tr>
                <th v-for="(item, index) in items" :key="index">
                    {{ item.name }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in items" :key="index">
                <td v-for="(item, index) in items" :key="index">
                    {{ item.value }}
                </td>
            </tr>
        </tbody>
    </table>
</template>
