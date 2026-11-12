@extends('layouts.app')
@section('title', 'Detalle Producto')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Info producto --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-pills text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $inventory->name }}</h2>
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs">{{ $inventory->category }}</span>
                </div>
            </div>
            <a href="{{ route('inventory.edit', $inventory) }}"
               class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class="fa-solid fa-pen mr-1"></i> Editar
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Stock actual</p>
                <p class="text-2xl font-bold {{ $inventory->stock <= $inventory->min_stock ? 'text-red-500' : 'text-purple-600' }}">
                    {{ $inventory->stock }}
                </p>
                <p class="text-xs text-gray-400">{{ $inventory->unit }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Stock mínimo</p>
                <p class="text-2xl font-bold text-gray-800">{{ $inventory->min_stock }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Precio</p>
                <p class="text-sm font-bold text-gray-800">
                    {{ $inventory->price ? '$' . number_format($inventory->price, 0, ',', '.') : '—' }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Vencimiento</p>
                <p class="text-sm font-medium text-gray-800">
                    {{ $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date)->format('d/m/Y') : '—' }}
                </p>
            </div>
        </div>

        {{-- Registrar movimiento --}}
        <div class="border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Registrar movimiento</h3>
            <form action="{{ route('inventory.movement', $inventory) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                        <select name="type"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad</label>
                        <input type="number" name="quantity" min="1"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Motivo</label>
                        <input type="text" name="reason"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: Compra, uso en consulta">
                    </div>
                </div>
                <button type="submit"
                    class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-plus mr-1"></i> Registrar
                </button>
            </form>
        </div>
    </div>

    {{-- Historial de movimientos --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de movimientos</h3>

        @forelse($inventory->movements->sortByDesc('created_at') as $movement)
        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <div class="w-10 h-10 rounded-full flex items-center justify-center
                {{ $movement->type === 'entrada' ? 'bg-green-100' : 'bg-red-100' }}">
                <i class="fa-solid fa-{{ $movement->type === 'entrada' ? 'arrow-down text-green-600' : 'arrow-up text-red-500' }}"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">
                    {{ ucfirst($movement->type) }} — {{ $movement->quantity }} {{ $inventory->unit }}
                </p>
                <p class="text-xs text-gray-500">{{ $movement->reason ?? 'Sin motivo' }} · {{ $movement->user->name }}</p>
            </div>
            <span class="text-xs text-gray-400">
                {{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y') }}
            </span>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-6">No hay movimientos registrados</p>
        @endforelse
    </div>

</div>

@endsection