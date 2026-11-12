@extends('layouts.app')
@section('title', 'Inventario')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('inventory.index') }}" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar producto, categoría, proveedor..."
            class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <select name="category"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todas las categorías</option>
            <option value="medicamento" {{ request('category') === 'medicamento' ? 'selected' : '' }}>Medicamento</option>
            <option value="vacuna"      {{ request('category') === 'vacuna'      ? 'selected' : '' }}>Vacuna</option>
            <option value="insumo"      {{ request('category') === 'insumo'      ? 'selected' : '' }}>Insumo</option>
            <option value="alimento"    {{ request('category') === 'alimento'    ? 'selected' : '' }}>Alimento</option>
            <option value="otro"        {{ request('category') === 'otro'        ? 'selected' : '' }}>Otro</option>
        </select>
        <select name="stock"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todo el stock</option>
            <option value="bajo" {{ request('stock') === 'bajo' ? 'selected' : '' }}>Stock bajo</option>
        </select>
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('category') || request('stock'))
        <a href="{{ route('inventory.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('inventory.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Nuevo producto
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Producto</th>
                <th class="px-6 py-4 text-left">Categoría</th>
                <th class="px-6 py-4 text-left">Stock</th>
                <th class="px-6 py-4 text-left">Unidad</th>
                <th class="px-6 py-4 text-left">Vencimiento</th>
                <th class="px-6 py-4 text-left">Precio</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-800">{{ $item->name }}</div>
                    @if($item->supplier)
                    <div class="text-xs text-gray-400">{{ $item->supplier }}</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs">{{ $item->category }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold {{ $item->stock <= $item->min_stock ? 'text-red-500' : 'text-gray-800' }}">
                            {{ $item->stock }}
                        </span>
                        @if($item->stock <= $item->min_stock)
                        <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs">Stock bajo</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $item->unit }}</td>
                <td class="px-6 py-4 text-gray-500">
                    @if($item->expiry_date)
                        @php $expired = \Carbon\Carbon::parse($item->expiry_date)->isPast(); @endphp
                        <span class="{{ $expired ? 'text-red-500 font-medium' : '' }}">
                            {{ \Carbon\Carbon::parse($item->expiry_date)->format('d/m/Y') }}
                            @if($expired) <span class="text-xs">(Vencido)</span> @endif
                        </span>
                    @else
                        —
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500">
                    {{ $item->price ? '$' . number_format($item->price, 0, ',', '.') : '—' }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('inventory.show', $item) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('inventory.edit', $item) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('inventory.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-boxes-stacked text-4xl mb-2 block"></i>
                    No hay productos en el inventario
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $items->links() }}
    </div>
</div>

@endsection