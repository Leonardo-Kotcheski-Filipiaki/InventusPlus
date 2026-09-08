<?php

namespace App\Http\Controllers;

use App\Models\StockCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockCategoryController extends Controller
{
    public function index()
    {
        $categories = StockCategory::withCount('stocks')->get();
        return Inertia::render('stock-category/index', [
            'categories' => $categories ?? []
        ]);
    }

    public function create()
    {
        return Inertia::render('stock-category/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:250|unique:stock_category,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'O nome da categoria é obrigatório',
            'name.min' => 'O nome deve ter no mínimo 2 caracteres',
            'name.unique' => 'Esta categoria já está cadastrada',
        ]);

        StockCategory::create($request->all());

        session()->flash('success', 'Categoria cadastrada com sucesso!');
        return redirect()->route('stock-categories.index');
    }

    public function edit(StockCategory $stockCategory)
    {
        return Inertia::render('stock-category/edit', [
            'category' => $stockCategory
        ]);
    }

    public function update(Request $request, StockCategory $stockCategory)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:250|unique:stock_category,name,' . $stockCategory->id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'O nome da categoria é obrigatório',
            'name.min' => 'O nome deve ter no mínimo 2 caracteres',
            'name.unique' => 'Esta categoria já está cadastrada',
        ]);

        $stockCategory->update($request->all());

        session()->flash('success', 'Categoria atualizada com sucesso!');
        return redirect()->route('stock-categories.index');
    }

    public function destroy(StockCategory $stockCategory)
    {
        if ($stockCategory->stocks()->count() > 0) {
            session()->flash('error', 'Não é possível excluir uma categoria que possui produtos vinculados no estoque.');
            return redirect()->route('stock-categories.index');
        }

        $stockCategory->delete();

        session()->flash('success', 'Categoria excluída com sucesso!');
        return redirect()->route('stock-categories.index');
    }
}
