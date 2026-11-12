@extends('layouts.app')
@section('title', 'Historias Clínicas')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('medical-records.index') }}" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar mascota, propietario, síntomas..."
            class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('date_from') || request('date_to'))
        <a href="{{ route('medical-records.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
    <a href="{{ route('medical-records.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Nueva historia
    </a>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Mascota</th>
                <th class="px-6 py-4 text-left">Propietario</th>
                <th class="px-6 py-4 text-left">Veterinario</th>
                <th class="px-6 py-4 text-left">Fecha visita</th>
                <th class="px-6 py-4 text-left">Síntomas</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($records as $record)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $record->pet->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $record->pet->owner->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $record->veterinarian->name }}</td>
                <td class="px-6 py-4 text-gray-500">
                    {{ \Carbon\Carbon::parse($record->visit_date)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-gray-500">{{ Str::limit($record->symptoms, 50) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('medical-records.show', $record) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
                        <a href="{{ route('medical-records.edit', $record) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('medical-records.destroy', $record) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta historia clínica?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-file-medical text-4xl mb-2 block"></i>
                    No hay historias clínicas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $records->links() }}
    </div>
</div>

@endsection