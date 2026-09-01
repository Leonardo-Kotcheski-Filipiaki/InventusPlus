<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Person;
use App\Rules\DocumentRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {  
        return Inertia::render('customer/index', [
            'customer' => Customer::with('person')->get()->all()
        ]);
    }

    public function create()
    {
        return Inertia::render('customer/create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:3',
                'cpf' => ['nullable', 'max:14', 'unique:person,cpf', new DocumentRule('cpf')],
                'cnpj' => ['nullable', 'max:18', 'unique:person,cnpj', new DocumentRule('cnpj')],
                'birthdate' => 'required|date',
                'email' => 'required|email',
                'phone' => 'nullable|min:10'
            ],
            [
                'name.required' => 'Nome é obrigatório',
                'name.min' => 'Nome deve ter pelo menos 3 caracteres',
                'cpf.min' => 'CPF inválido',
                'cpf.max' => 'CPF inválido',
                'cnpj.min' => 'CNPJ inválido',
                'cnpj.max' => 'CNPJ inválido',
                'cpf.unique' => 'CPF já cadastrado',
                'cnpj.unique' => 'CNPJ já cadastrado',
                'birthdate.required' => 'Data de nascimento é obrigatória',
                'birthdate.date' => 'Data de nascimento inválida',
                'email.required' => 'Email é obrigatório',
                'email.email' => 'Email inválido',
                'phone.min' => 'Telefone deve ter pelo menos 11 caracteres'
            ]
        );

        DB::transaction(function () use ($request) {
            $person = Person::create($request->all());
            Customer::create(['person_id' => $person->id]);
        });
        session()->flash('success', 'Cliente cadastrado com sucesso!');
        return Inertia::location(route('customer.index'));
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('customer/edit', [
            // [Correção 4]: Usa load() ao invés de buscar novamente
            'customer' => $customer->load('person') 
        ]);
    }
    public function update(Customer $customer, Request $request)
    {
        // [Correção 1]: Limpamos os dados ANTES de validar para que o 'unique' e o 'max/min' funcionem!
        $request->merge([
            'cpf' => $request->cpf ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cpf) : null,
            'cnpj' => $request->cnpj ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cnpj) : null,
            'phone' => $request->phone ? preg_replace('/[^a-zA-Z0-9]/', '', $request->phone) : null,
        ]);
        $request->validate(
            [
                'name' => 'required|min:3',
                // [Correção 3]: Tamanhos ajustados para os dados limpos (11 e 14)
                'cpf' => ['nullable', 'max:11', 'unique:person,cpf,' . $customer->person_id, new DocumentRule('cpf')],
                'cnpj' => ['nullable', 'max:14', 'unique:person,cnpj,' . $customer->person_id, new DocumentRule('cnpj')],
                'birthdate' => 'required|date',
                'email' => 'required|email',
                'phone' => 'nullable|min:10'
            ],
            // ... (suas mensagens customizadas continuam iguais)
        );
        
        DB::transaction(function () use ($customer, $request) {
            $person = Person::find($customer->person_id);
            $person->update($request->all());
        });
        session()->flash('success', 'Cliente atualizado com sucesso!');
        return Inertia::location(route('customer.index'));
    }
    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            $person = Person::find($customer->person_id);
            $address = Address::find($customer->address_id);
            if ($address) {
                $address->delete();
            }
            $customer->delete();
            $person->delete();
        });
        session()->flash('success', 'Cliente excluído com sucesso!');
        return Inertia::location(route('customer.index'));
    }

}
