<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['stockCategory', 'supplier'])->get();
        return Inertia::render("stock/index", [
            'stock' => $stocks ?? [],
            'categories' => StockCategory::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('stock/create', [
            'suppliers' => Supplier::all(),
            'categories' => StockCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:250',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:0',
            'unit_value' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:supplier,id',
            'stock_category_id' => 'required|exists:stock_category,id',
        ], [
            'name.required' => 'O nome do produto é obrigatório',
            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer' => 'A quantidade deve ser um número inteiro',
            'quantity.min' => 'A quantidade não pode ser negativa',
            'unit_value.required' => 'O valor unitário é obrigatório',
            'unit_value.numeric' => 'O valor unitário deve ser numérico',
            'unit_value.min' => 'O valor unitário não pode ser negativo',
            'supplier_id.required' => 'Selecione um fornecedor',
            'supplier_id.exists' => 'Fornecedor inválido',
            'stock_category_id.required' => 'Selecione uma categoria',
            'stock_category_id.exists' => 'Categoria inválida',
        ]);

        Stock::create($request->all());

        session()->flash('success', 'Produto cadastrado com sucesso!');
        return redirect()->route('stocks.index');
    }

    public function edit(Stock $stock)
    {
        return Inertia::render('stock/edit', [
            'stock' => $stock->load(['stockCategory', 'supplier']),
            'suppliers' => Supplier::all(),
            'categories' => StockCategory::all(),
        ]);
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:250',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:0',
            'unit_value' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:supplier,id',
            'stock_category_id' => 'required|exists:stock_category,id',
        ], [
            'name.required' => 'O nome do produto é obrigatório',
            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer' => 'A quantidade deve ser um número inteiro',
            'quantity.min' => 'A quantidade não pode ser negativa',
            'unit_value.required' => 'O valor unitário é obrigatório',
            'unit_value.numeric' => 'O valor unitário deve ser numérico',
            'unit_value.min' => 'O valor unitário não pode ser negativo',
            'supplier_id.required' => 'Selecione um fornecedor',
            'supplier_id.exists' => 'Fornecedor inválido',
            'stock_category_id.required' => 'Selecione uma categoria',
            'stock_category_id.exists' => 'Categoria inválida',
        ]);

        $stock->update($request->all());

        session()->flash('success', 'Produto atualizado com sucesso!');
        return redirect()->route('stocks.index');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        session()->flash('success', 'Produto excluído com sucesso!');
        return redirect()->route('stocks.index');
    }
}
