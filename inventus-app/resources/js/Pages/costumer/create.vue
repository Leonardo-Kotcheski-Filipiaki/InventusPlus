<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Nav from '@/ui/MainNav.vue';
import SubMenu from '@/ui/SubMenu.vue';
import costumers from '@/routes/costumers';
import { ref } from 'vue';

const form = useForm({
    name: '',
    cpf: '',
    cnpj: '',
    birthdate: '',
    email: '',
    phone: ''
});

const showElement = ref(true);

const page = usePage();

const submit = () => {
    form.post(costumers.store.url());
    if (page.props.flash.warning || page.props.errors.error) {
        showElement.value = true;
    } else {
        showElement.value = false;
    }
    setTimeout(() => {
        showElement.value = false;
    }, 3000)
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
    <div class="flex justify-center max-w-full mt-10 ">
        <form class="border border-gray-500 rounded-md p-10" @submit.prevent="submit()" method="post">
            <h1 class="text-center font-semibold text-2xl">Adicionar Cliente</h1>
            <div class="flex flex-col gap-5 w-80">
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="text" v-model="form.name" name="name" id="name" placeholder="Nome*" required />
                <span v-if="form.errors.name" class="text-red-400 text-md font-semibold">
                    {{ form.errors.name }}
                </span>
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="text" v-model="form.cpf" name="cpf" id="cpf" placeholder="CPF" />
                <span v-if="form.errors.cpf" class="text-red-400 text-md font-semibold">
                    {{ form.errors.cpf }}
                </span>
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="text" v-model="form.cnpj" name="cnpj" id="cnpj" placeholder="CNPJ" />
                <span v-if="form.errors.cnpj" class="text-red-400 text-md font-semibold">
                    {{ form.errors.cnpj }}
                </span>
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="date" v-model="form.birthdate" name="birthdate" id="birthdate" placeholder="Data de Nascimento*" required />
                <span v-if="form.errors.birthdate" class="text-red-400 text-md font-semibold">
                    {{ form.errors.birthdate }}
                </span>
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="email" v-model="form.email" name="email" id="email" placeholder="Email*" required />
                <span v-if="form.errors.email" class="text-red-400 text-md font-semibold">
                    {{ form.errors.email }}
                </span>
                <input class="border border-gray-500 rounded-md p-2 focus:border-blue-500" type="tel" v-model="form.phone" name="phone" id="phone" placeholder="Telefone*" required />
                <span v-if="form.errors.phone" class="text-red-400 text-md font-semibold">
                    {{ form.errors.phone }}
                </span>
            </div>
            <div class="flex justify-center items-center w-80 mt-5">
                <button class="w-[50%] border border-gray-500 rounded-md p-2 hover:bg-gray-600 hover:text-white transition-colors cursor-pointer" type="submit">Salvar</button>
            </div>
        </form>
    </div>
</template>
