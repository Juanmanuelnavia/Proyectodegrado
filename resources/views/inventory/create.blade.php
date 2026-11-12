@extends('layouts.app')
@section('title', 'Nuevo Producto')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('inventory.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del producto *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: Amoxicilina 500mg">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="category"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccionar</option>
                        <option value="medicamento" {{ old('category') === 'medicamento' ? 'selected' : '' }}>Medicamento</option>
                        <option value="vacuna" {{ old('category') === 'vacuna' ? 'selected' : '' }}>Vacuna</option>
                        <option value="insumo" {{ old('category') === 'insumo' ? 'selected' : '' }}>Insumo</option>
                        <option value="alimento" {{ old('category') === 'alimento' ? 'selected' : '' }}>Alimento</option>
                        <option value="otro" {{ old('category') === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidad *</label>
                    <select name="unit"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="unidad" {{ old('unit') === 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="caja" {{ old('unit') === 'caja' ? 'selected' : '' }}>Caja</option>
                        <option value="frasco" {{ old('unit') === 'frasco' ? 'selected' : '' }}>Frasco</option>
                        <option value="ampolla" {{ old('unit') === 'ampolla' ? 'selected' : '' }}>Ampolla</option>
                        <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>ml</option>
                        <option value="mg" {{ old('unit') === 'mg' ? 'selected' : '' }}>mg</option>
                    </select>
                    @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock inicial *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('min_stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('expiry_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: 25000">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: Laboratorios MK">
                    @error('supplier')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Descripción opcional...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar
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