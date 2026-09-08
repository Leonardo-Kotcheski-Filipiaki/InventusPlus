<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::with(['stock', 'customer', 'supplier'])
            ->orderByDesc('id')
            ->get();

        $stocks = Stock::select('id', 'name')->get();

        return Inertia::render('sales/index', [
            'sales' => $sales,
            'stocks' => $stocks,
        ]);
    }

    public function create()
    {
        return Inertia::render('sales/create', [
            'stocks' => Stock::all(),
            'customers' => Customer::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'sales_type' => 'required|in:entrada,saida',
            'stock_id' => 'required|exists:stock,id',
            'quantity' => 'required|integer|min:1',
            'unit_value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ];

        if ($request->sales_type === 'entrada') {
            $rules['supplier_id'] = 'required|exists:supplier,id';
        } else {
            $rules['customer_id'] = 'required|exists:customer,id';
        }

        $messages = [
            'sales_type.required' => 'O tipo de movimentação é obrigatório',
            'sales_type.in' => 'Tipo de movimentação inválido',
            'stock_id.required' => 'Selecione um produto',
            'stock_id.exists' => 'Produto selecionado inválido',
            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer' => 'A quantidade deve ser um número inteiro',
            'quantity.min' => 'A quantidade mínima deve ser 1',
            'unit_value.required' => 'O valor unitário é obrigatório',
            'unit_value.numeric' => 'O valor unitário deve ser numérico',
            'unit_value.min' => 'O valor unitário não pode ser negativo',
            'supplier_id.required' => 'Selecione um fornecedor para a entrada',
            'supplier_id.exists' => 'Fornecedor selecionado inválido',
            'customer_id.required' => 'Selecione um cliente para a saída',
            'customer_id.exists' => 'Cliente selecionado inválido',
        ];

        $request->validate($rules, $messages);

        $stock = Stock::findOrFail($request->stock_id);

        if ($request->sales_type === 'saida' && $stock->quantity < $request->quantity) {
            return back()->withErrors([
                'quantity' => "Quantidade indisponível em estoque. Estoque atual: {$stock->quantity} un."
            ])->withInput();
        }

        DB::transaction(function () use ($request, $stock) {
            Sales::create([
                'sales_type' => $request->sales_type,
                'stock_id' => $request->stock_id,
                'quantity' => $request->quantity,
                'unit_value' => $request->unit_value,
                'supplier_id' => $request->sales_type === 'entrada' ? $request->supplier_id : null,
                'customer_id' => $request->sales_type === 'saida' ? $request->customer_id : null,
                'description' => $request->description,
            ]);

            if ($request->sales_type === 'entrada') {
                $stock->increment('quantity', (int) $request->quantity);
            } else {
                $stock->decrement('quantity', (int) $request->quantity);
            }
        });

        session()->flash('success', $request->sales_type === 'entrada'
            ? 'Entrada registrada com sucesso!'
            : 'Venda registrada com sucesso!');

        return redirect()->route('sales.index');
    }

    public function edit(Sales $sale)
    {
        return Inertia::render('sales/edit', [
            'sale' => $sale->load(['stock', 'customer', 'supplier']),
            'stocks' => Stock::all(),
            'customers' => Customer::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function update(Request $request, Sales $sale)
    {
        $rules = [
            'sales_type' => 'required|in:entrada,saida',
            'stock_id' => 'required|exists:stock,id',
            'quantity' => 'required|integer|min:1',
            'unit_value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ];

        if ($request->sales_type === 'entrada') {
            $rules['supplier_id'] = 'required|exists:supplier,id';
        } else {
            $rules['customer_id'] = 'required|exists:customer,id';
        }

        $messages = [
            'sales_type.required' => 'O tipo de movimentação é obrigatório',
            'sales_type.in' => 'Tipo de movimentação inválido',
            'stock_id.required' => 'Selecione um produto',
            'stock_id.exists' => 'Produto selecionado inválido',
            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer' => 'A quantidade deve ser um número inteiro',
            'quantity.min' => 'A quantidade mínima deve ser 1',
            'unit_value.required' => 'O valor unitário é obrigatório',
            'unit_value.numeric' => 'O valor unitário deve ser numérico',
            'unit_value.min' => 'O valor unitário não pode ser negativo',
            'supplier_id.required' => 'Selecione um fornecedor para a entrada',
            'supplier_id.exists' => 'Fornecedor selecionado inválido',
            'customer_id.required' => 'Selecione um cliente para a saída',
            'customer_id.exists' => 'Cliente selecionado inválido',
        ];

        $request->validate($rules, $messages);

        try {
            DB::transaction(function () use ($request, $sale) {
                // Reverter efeito anterior no estoque
                $oldStock = Stock::findOrFail($sale->stock_id);
                if ($sale->sales_type === 'entrada') {
                    $oldStock->decrement('quantity', (int) $sale->quantity);
                } else {
                    $oldStock->increment('quantity', (int) $sale->quantity);
                }

                // Buscar produto alvo atualizado
                $newStock = $sale->stock_id == $request->stock_id
                    ? $oldStock->fresh()
                    : Stock::findOrFail($request->stock_id);

                if ($request->sales_type === 'saida' && $newStock->quantity < $request->quantity) {
                    throw new \RuntimeException("Quantidade indisponível em estoque após ajuste. Estoque disponível: {$newStock->quantity} un.");
                }

                // Aplicar novo efeito no estoque
                if ($request->sales_type === 'entrada') {
                    $newStock->increment('quantity', (int) $request->quantity);
                } else {
                    $newStock->decrement('quantity', (int) $request->quantity);
                }

                $sale->update([
                    'sales_type' => $request->sales_type,
                    'stock_id' => $request->stock_id,
                    'quantity' => $request->quantity,
                    'unit_value' => $request->unit_value,
                    'supplier_id' => $request->sales_type === 'entrada' ? $request->supplier_id : null,
                    'customer_id' => $request->sales_type === 'saida' ? $request->customer_id : null,
                    'description' => $request->description,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }

        session()->flash('success', 'Registro atualizado com sucesso!');
        return redirect()->route('sales.index');
    }

    public function destroy(Sales $sale)
    {
        DB::transaction(function () use ($sale) {
            $stock = Stock::find($sale->stock_id);
            if ($stock) {
                if ($sale->sales_type === 'entrada') {
                    $stock->decrement('quantity', (int) $sale->quantity);
                } else {
                    $stock->increment('quantity', (int) $sale->quantity);
                }
            }
            $sale->delete();
        });

        session()->flash('success', 'Registro excluído com sucesso!');
        return redirect()->route('sales.index');
    }
}
