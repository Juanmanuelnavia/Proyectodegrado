@extends('layouts.app')
@section('title', 'Citas')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('appointments.index') }}" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar mascota o propietario..."
            class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <select name="status"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todos los estados</option>
            <option value="pendiente"  {{ request('status') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmada" {{ request('status') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
            <option value="completada" {{ request('status') === 'completada' ? 'selected' : '' }}>Completada</option>
            <option value="cancelada"  {{ request('status') === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('status') || request('date'))
        <a href="{{ route('appointments.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('appointments.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Nueva cita
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Mascota</th>
                <th class="px-6 py-4 text-left">Propietario</th>
                <th class="px-6 py-4 text-left">Veterinario</th>
                <th class="px-6 py-4 text-left">Fecha y hora</th>
                <th class="px-6 py-4 text-left">Motivo</th>
                <th class="px-6 py-4 text-left">Estado</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($appointments as $appointment)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $appointment->pet->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $appointment->owner->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $appointment->veterinarian->name }}</td>
                <td class="px-6 py-4 text-gray-500">
                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y h:i A') }}
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $appointment->reason ?? '—' }}</td>
                <td class="px-6 py-4">
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
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('appointments.show', $appointment) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('appointments.edit', $appointment) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta cita?')">
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
                    <i class="fa-solid fa-calendar-xmark text-4xl mb-2 block"></i>
                    No hay citas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $appointments->links() }}
    </div>
</div>

@endsection