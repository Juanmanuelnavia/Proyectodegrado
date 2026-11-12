@extends('layouts.app')
@section('title', 'Detalle Cita')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Cita #{{ $appointment->id }}</h2>
                    <p class="text-gray-500 text-sm">
                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y h:i A') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('appointments.edit', $appointment) }}"
               class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class="fa-solid fa-pen mr-1"></i> Editar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Mascota</p>
                <p class="text-sm font-medium text-gray-800">{{ $appointment->pet->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Propietario</p>
                <p class="text-sm font-medium text-gray-800">{{ $appointment->owner->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Veterinario</p>
                <p class="text-sm font-medium text-gray-800">{{ $appointment->veterinarian->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Estado</p>
                @php
                    $colors = [
                        'pendiente'  => 'bg-yellow-100 text-yellow-700',
                        'confirmada' => 'bg-blue-100 text-blue-700',
                        'completada' => 'bg-green-100 text-green-700',
                        'cancelada'  => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$appointment->status] }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                <p class="text-xs text-gray-400 mb-1">Motivo</p>
                <p class="text-sm font-medium text-gray-800">{{ $appointment->reason ?? '—' }}</p>
            </div>
            @if($appointment->notes)
            <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                <p class="text-xs text-gray-400 mb-1">Notas</p>
                <p class="text-sm text-gray-800">{{ $appointment->notes }}</p>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection