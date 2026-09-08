<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Supplier;
use App\Rules\DocumentRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        return Inertia::render('supplier/index', [
            'supplier' => Supplier::with('address')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('supplier/create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'cpf' => $request->cpf ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cpf) : null,
            'cnpj' => $request->cnpj ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cnpj) : null,
            'phone' => $request->phone ? preg_replace('/[^a-zA-Z0-9]/', '', $request->phone) : null,
        ]);

        $request->validate(
            [
                'name' => 'required|min:3',
                'cpf' => ['nullable', 'max:11', 'unique:supplier,cpf', new DocumentRule('cpf')],
                'cnpj' => ['nullable', 'max:14', 'unique:supplier,cnpj', new DocumentRule('cnpj')],
                'email' => 'required|email',
                'phone' => 'nullable|min:10'
            ],
            [
                'name.required' => 'Nome é obrigatório',
                'name.min' => 'Nome deve ter pelo menos 3 caracteres',
                'cpf.max' => 'CPF inválido',
                'cnpj.max' => 'CNPJ inválido',
                'cpf.unique' => 'CPF já cadastrado',
                'cnpj.unique' => 'CNPJ já cadastrado',
                'email.required' => 'Email é obrigatório',
                'email.email' => 'Email inválido',
                'phone.min' => 'Telefone deve ter pelo menos 10 caracteres'
            ]
        );

        Supplier::create($request->all());

        session()->flash('success', 'Fornecedor cadastrado com sucesso!');
        return redirect()->route('suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return Inertia::render('supplier/edit', [
            'supplier' => $supplier->load('address')
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->merge([
            'cpf' => $request->cpf ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cpf) : null,
            'cnpj' => $request->cnpj ? preg_replace('/[^a-zA-Z0-9]/', '', $request->cnpj) : null,
            'phone' => $request->phone ? preg_replace('/[^a-zA-Z0-9]/', '', $request->phone) : null,
        ]);

        $request->validate(
            [
                'name' => 'required|min:3',
                'cpf' => ['nullable', 'max:11', 'unique:supplier,cpf,' . $supplier->id, new DocumentRule('cpf')],
                'cnpj' => ['nullable', 'max:14', 'unique:supplier,cnpj,' . $supplier->id, new DocumentRule('cnpj')],
                'email' => 'required|email',
                'phone' => 'nullable|min:10'
            ],
            [
                'name.required' => 'Nome é obrigatório',
                'name.min' => 'Nome deve ter pelo menos 3 caracteres',
                'cpf.max' => 'CPF inválido',
                'cnpj.max' => 'CNPJ inválido',
                'cpf.unique' => 'CPF já cadastrado',
                'cnpj.unique' => 'CNPJ já cadastrado',
                'email.required' => 'Email é obrigatório',
                'email.email' => 'Email inválido',
                'phone.min' => 'Telefone deve ter pelo menos 10 caracteres'
            ]
        );

        $supplier->update($request->all());

        session()->flash('success', 'Fornecedor atualizado com sucesso!');
        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        DB::transaction(function () use ($supplier) {
            $address = Address::find($supplier->address_id);
            if ($address) {
                $address->delete();
            }
            $supplier->delete();
        });

        session()->flash('success', 'Fornecedor excluído com sucesso!');
        return redirect()->route('suppliers.index');
    }
}
