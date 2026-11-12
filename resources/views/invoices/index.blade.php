@extends('layouts.app')
@section('title', 'Facturación')

@section('content')

{{-- Resumen --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-cash-register text-purple-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Ingresos hoy</p>
            <p class="text-3xl font-bold text-gray-800">${{ number_format($totalHoy, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-chart-line text-purple-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Ingresos este mes</p>
            <p class="text-3xl font-bold text-gray-800">${{ number_format($totalMes, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('invoices.index') }}" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar por número o propietario..."
            class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <select name="status"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="pagada"    {{ request('status') === 'pagada'    ? 'selected' : '' }}>Pagada</option>
            <option value="anulada"   {{ request('status') === 'anulada'   ? 'selected' : '' }}>Anulada</option>
        </select>
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('invoices.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('invoices.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Nueva factura
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Número</th>
                <th class="px-6 py-4 text-left">Propietario</th>
                <th class="px-6 py-4 text-left">Fecha</th>
                <th class="px-6 py-4 text-left">Total</th>
                <th class="px-6 py-4 text-left">Método pago</th>
                <th class="px-6 py-4 text-left">Estado</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($invoices as $invoice)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-purple-600">{{ $invoice->invoice_number }}</td>
                <td class="px-6 py-4 text-gray-800">{{ $invoice->owner->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 font-semibold text-gray-800">${{ number_format($invoice->total, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-gray-500 capitalize">{{ $invoice->payment_method ?? '—' }}</td>
                <td class="px-6 py-4">
                    @php
                        $colors = [
                            'pendiente' => 'bg-yellow-100 text-yellow-700',
                            'pagada'    => 'bg-green-100 text-green-700',
                            'anulada'   => 'bg-red-100 text-red-600',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('invoices.show', $invoice) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('invoices.pdf', $invoice) }}"
                           class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition">
                            <i class="fa-solid fa-file-pdf text-xs"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-file-invoice text-4xl mb-2 block"></i>
                    No hay facturas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $invoices->links() }}
    </div>
</div>

@endsection