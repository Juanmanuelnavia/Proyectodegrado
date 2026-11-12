<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
{
    $query = Inventory::query();

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('supplier', 'like', '%' . $request->search . '%')
              ->orWhere('category', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('stock') && $request->stock === 'bajo') {
        $query->whereColumn('stock', '<=', 'min_stock');
    }

    $items = $query->latest()->paginate(10)->withQueryString();
    return view('inventory.index', compact('items'));
}

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'price'       => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item = Inventory::create($request->all());

        if ($request->stock > 0) {
            InventoryMovement::create([
                'inventory_id' => $item->id,
                'user_id'      => auth()->id(),
                'type'         => 'entrada',
                'quantity'     => $request->stock,
                'reason'       => 'Stock inicial',
            ]);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Producto registrado exitosamente.');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load('movements.user');
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'unit'        => 'required|string|max:50',
            'min_stock'   => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'price'       => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function addMovement(Request $request, Inventory $inventory)
    {
        $request->validate([
            'type'     => 'required|in:entrada,salida',
            'quantity' => 'required|integer|min:1',
            'reason'   => 'nullable|string|max:255',
        ]);

        if ($request->type === 'salida' && $inventory->stock < $request->quantity) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'quantity'     => $request->quantity,
            'reason'       => $request->reason,
        ]);

        if ($request->type === 'entrada') {
            $inventory->increment('stock', $request->quantity);
        } else {
            $inventory->decrement('stock', $request->quantity);
        }

        return back()->with('success', 'Movimiento registrado exitosamente.');
    }
}
