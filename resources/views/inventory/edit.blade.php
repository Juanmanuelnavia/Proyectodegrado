@extends('layouts.app')
@section('title', 'Editar Producto')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('inventory.update', $inventory) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del producto *</label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="category"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="medicamento" {{ old('category', $inventory->category) === 'medicamento' ? 'selected' : '' }}>Medicamento</option>
                        <option value="vacuna" {{ old('category', $inventory->category) === 'vacuna' ? 'selected' : '' }}>Vacuna</option>
                        <option value="insumo" {{ old('category', $inventory->category) === 'insumo' ? 'selected' : '' }}>Insumo</option>
                        <option value="alimento" {{ old('category', $inventory->category) === 'alimento' ? 'selected' : '' }}>Alimento</option>
                        <option value="otro" {{ old('category', $inventory->category) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidad *</label>
                    <select name="unit"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="unidad" {{ old('unit', $inventory->unit) === 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="caja" {{ old('unit', $inventory->unit) === 'caja' ? 'selected' : '' }}>Caja</option>
                        <option value="frasco" {{ old('unit', $inventory->unit) === 'frasco' ? 'selected' : '' }}>Frasco</option>
                        <option value="ampolla" {{ old('unit', $inventory->unit) === 'ampolla' ? 'selected' : '' }}>Ampolla</option>
                        <option value="ml" {{ old('unit', $inventory->unit) === 'ml' ? 'selected' : '' }}>ml</option>
                        <option value="mg" {{ old('unit', $inventory->unit) === 'mg' ? 'selected' : '' }}>mg</option>
                    </select>
                    @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $inventory->min_stock) }}" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('min_stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $inventory->expiry_date) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <input type="number" name="price" value="{{ old('price', $inventory->price) }}" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                    <input type="text" name="supplier" value="{{ old('supplier', $inventory->supplier) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $inventory->description) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar
                </button>
                <a href="{{ route('inventory.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection