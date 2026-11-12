@extends('layouts.app')
@section('title', 'Propietarios')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('owners.index') }}" class="flex gap-2 flex-1 max-w-md">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar por nombre, teléfono, email..."
            class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search'))
        <a href="{{ route('owners.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('owners.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nuevo propietario
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Nombre</th>
                <th class="px-6 py-4 text-left">Documento</th>
                <th class="px-6 py-4 text-left">Teléfono</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Mascotas</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($owners as $owner)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $owner->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $owner->document ?? '—' }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $owner->phone }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $owner->email ?? '—' }}</td>
                <td class="px-6 py-4">
                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $owner->pets_count ?? 0 }} mascotas
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('owners.show', $owner) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('owners.edit', $owner) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('owners.destroy', $owner) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este propietario?')">
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
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-users text-4xl mb-2 block"></i>
                    No hay propietarios registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $owners->links() }}
    </div>
</div>

@endsection