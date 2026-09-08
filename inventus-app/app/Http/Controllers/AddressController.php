<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with("customer")->get();
        return Inertia::render("address/index", [
            "addressArray" => $addresses ?? []
        ]);
    }

    public function create(int $id)
    {
        $customer = Customer::find($id);
        if ($customer == null) {
            return redirect()->route("customers.edit", $id)->with("error", "Cliente não encontrado");
        }
        return Inertia::render('address/create', [
            'customer' => $customer
        ]);
    }

    public function store(Request $request, int $id) 
    {
        $customer = Customer::find($id);
        if ($customer == null) {
            return redirect()->route("customers.index")->with("error", "Cliente não encontrado");
        }

        $request->merge(['zip_code' => str_replace('-', '', $request->zip_code)]);
        $request->merge(['zip_code' => substr($request->zip_code, 0, 5) . '-' . substr($request->zip_code, 5)]);

        $request->validate([
            "street" => "required", 
            "number" => "required|integer",
            "neighborhood" => "required",
            "city" => "required", 
            "state" => "required", 
            "zip_code" => "required|string|min:7|max:9" 
        ],
        [
            "street.required" => "Rua é obrigatória",
            "number.required" => "Número é obrigatório",
            "neighborhood.required" => "Bairro é obrigatório",
            "city.required" => "Cidade é obrigatória",
            "state.required" => "Estado é obrigatório",
            "zip_code.required" => "CEP é obrigatório",
            "zip_code.min" => "CEP inválido",
            "zip_code.max" => "CEP inválido",
        ]);

        DB::transaction(function () use ($request, $customer) {
            $address = Address::create($request->all());
            $ok = $customer->update(['address_id' => $address->id]);
            if (!$ok) {
                DB::rollBack();
                session()->flash("error", "Address not created");
                return redirect()->route("customers.edit", $customer->id);
            }
            DB::commit();
        });

        return redirect()->route("customers.edit", $customer->id)->with("success", "Endereço cadastrado com sucesso");
    }

    public function edit(int $id) 
    {
        $address = Address::find($id);
        if ($address == null) {
            return redirect()->route("address.index")->with("error", "Endereço não encontrado");
        }
        $address->customer = Customer::where('address_id', $address->id)->first();

        return Inertia::render("address/edit", [
            "address" => $address
        ]);
    }

    public function update(Request $request, int $id)
    {
        $address = Address::find($id);
        if ($address == null) {
            return redirect()->route("address.index")->with("error", "Endereço não encontrado");
        }

        $request->merge(['zip_code' => str_replace('-', '', $request->zip_code)]);
        $request->merge(['zip_code' => substr($request->zip_code, 0, 5) . '-' . substr($request->zip_code, 5)]);

        $request->validate([
            "street" => "required", 
            "number" => "required|integer",
            "neighborhood" => "required",
            "city" => "required", 
            "state" => "required", 
            "zip_code" => "required|string|min:7|max:9" 
        ],
        [
            "street.required" => "Rua é obrigatória",
            "number.required" => "Número é obrigatório",
            "neighborhood.required" => "Bairro é obrigatório",
            "city.required" => "Cidade é obrigatória",
            "state.required" => "Estado é obrigatório",
            "zip_code.required" => "CEP é obrigatório",
            "zip_code.min" => "CEP inválido",
            "zip_code.max" => "CEP inválido",
        ]);

        $address->update($request->all());

        return redirect()->route("address.index")->with("success", "Endereço atualizado com sucesso");
    }

    public function destroy(int $id) 
    {
        $address = Address::find($id);
        if ($address == null) {
            return redirect()->route("address.index")->with("error", "Endereço não encontrado");
        }
        $address->delete();
        return redirect()->route("address.index")->with("success", "Endereço deletado com sucesso");
    }
}
