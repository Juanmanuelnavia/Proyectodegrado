@extends('layouts.app')
@section('title', 'Factura ' . $invoice->invoice_number)

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6">
        {{-- Header factura --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                <p class="text-gray-500 text-sm">{{ $invoice->created_at->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $colors = [
                        'pendiente' => 'bg-yellow-100 text-yellow-700',
                        'pagada'    => 'bg-green-100 text-green-700',
                        'anulada'   => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colors[$invoice->status] }}">
                    {{ ucfirst($invoice->status) }}
                </span>
                <a href="{{ route('invoices.pdf', $invoice) }}"
                   class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                </a>
            </div>
        </div>

        {{-- Info cliente --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Propietario</p>
                <p class="text-sm font-medium text-gray-800">{{ $invoice->owner->name }}</p>
                <p class="text-xs text-gray-500">{{ $invoice->owner->phone }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Atendido por</p>
                <p class="text-sm font-medium text-gray-800">{{ $invoice->user->name }}</p>
                @if($invoice->appointment)
                <p class="text-xs text-gray-500">Mascota: {{ $invoice->appointment->pet->name }}</p>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <table class="w-full text-sm mb-6">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Descripción</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-center">Cant.</th>
                    <th class="px-4 py-3 text-right">Precio unit.</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->items as $item)
                <tr>
                    <td class="px-4 py-3 text-gray-800">{{ $item->description }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs capitalize">
                            {{ $item->type }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">${{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total --}}
        <div class="flex justify-end">
            <div class="w-64">
                <div class="flex justify-between py-2 border-t border-gray-200">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-medium">${{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-t-2 border-gray-800">
                    <span class="font-bold text-gray-800">Total:</span>
                    <span class="font-bold text-purple-600 text-lg">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Cambiar estado --}}
        @if($invoice->status === 'pendiente')
        <div class="mt-6 pt-6 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Registrar pago</h3>
            <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="flex gap-3">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="pagada">
                <select name="payment_method"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-check mr-1"></i> Marcar como pagada
                </button>
                <a href="{{ route('invoices.destroy', $invoice) }}"
                   onclick="event.preventDefault(); if(confirm('¿Anular esta factura?')) { var f=document.createElement('form'); f.method='POST'; f.action='{{ route('invoices.destroy', $invoice) }}'; var m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE'; f.appendChild(m); var c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}'; f.appendChild(c); document.body.appendChild(f); f.submit(); }"
                   class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2.5 rounded-xl text-sm font-medium transition">
                    Anular
                </a>
            </form>
        </div>
        @endif

        @if($invoice->payment_method)
        <div class="mt-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            Pagada con {{ ucfirst($invoice->payment_method) }}
            @if($invoice->paid_at) el {{ $invoice->paid_at->format('d/m/Y h:i A') }} @endif
        </div>
        @endif
    </div>

</div>

@endsection