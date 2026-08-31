<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AddressController extends Controller
{
    
    public function index()
    {
        //Get costumer name that belongs to Person model
        $addresses = Address::all()->each(function ($address) {
            $address->costumer = Costumer::where('address_id', $address->id)->with('person:id,name')->get()->first();
        });
        return Inertia::render("address/index", [
            "addressArray" => $addresses ?? []
        ]);
    }

    public function create(int $id)
    {
        //need an Costumer object with person->name column and costumer id
        $costumer = Costumer::with('person:id,name')->find($id);
        if ($costumer == null) {
            return redirect()->route("costumers.edit", $id)->with("error", "Cliente não encontrado");
        }
        return Inertia::render('address/create', [
            'costumer' => $costumer
        ]);
    }

    public function store(Request $request, int $id) 
    {
        $costumer = Costumer::find($id);
        if ($costumer == null) {
            return redirect()->route("costumers.index")->with("error", "Cliente não encontrado");
        }
        $request->zip_code = str_replace('-', '', $request->zip_code);
        $request->zip_code = substr($request->zip_code, 0, 5) . '-' . substr($request->zip_code, 5);

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
            "zip_code.min"=>"CEP inválido",
            "zip_code.max"=>"CEP inválido",
        ]);

        DB::transaction(function () use ($request, $costumer) {
            $address = Address::create($request->all());
            $ok = $costumer->update(['address_id' => $address->id]);
            if (!$ok) {
                DB::rollBack();
                session()->flash("error", "Address not created");
                return redirect()->route("costumers.edit", $costumer->id);
            }
            DB::commit();
        });

        return redirect()->route("costumers.edit", $costumer->id)->with("success", "Address created successfully");
    }

    public function edit(int $id) 
    {
        $address = Address::find($id);
        if ($address == null) {
            return redirect()->route("address.index")->with("error", "Endereço não encontrado");
        }
        $address->costumer = Costumer::where('address_id', $address->id)->with('person:id,name')->first();

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

        $request->zip_code = str_replace('-', '', $request->zip_code);
        $request->zip_code = substr($request->zip_code, 0, 5) . '-' . substr($request->zip_code, 5);

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
            "zip_code.min"=>"CEP inválido",
            "zip_code.max"=>"CEP inválido",
        ]);

        $address->update($request->all());

        return redirect()->route("address.index")->with("success", "Endereço atualizado com sucesso");
    }
}

