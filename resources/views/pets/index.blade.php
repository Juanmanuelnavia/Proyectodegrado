@extends('layouts.app')
@section('title', 'Mascotas')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('pets.index') }}" class="flex gap-2 flex-1 max-w-lg">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar por nombre, especie, raza, propietario..."
            class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <select name="species"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todas</option>
            <option value="perro" {{ request('species') === 'perro' ? 'selected' : '' }}>Perro</option>
            <option value="gato" {{ request('species') === 'gato' ? 'selected' : '' }}>Gato</option>
            <option value="ave" {{ request('species') === 'ave' ? 'selected' : '' }}>Ave</option>
            <option value="conejo" {{ request('species') === 'conejo' ? 'selected' : '' }}>Conejo</option>
            <option value="otro" {{ request('species') === 'otro' ? 'selected' : '' }}>Otro</option>
        </select>
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('species'))
        <a href="{{ route('pets.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('pets.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nueva mascota
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Mascota</th>
                <th class="px-6 py-4 text-left">Propietario</th>
                <th class="px-6 py-4 text-left">Especie</th>
                <th class="px-6 py-4 text-left">Raza</th>
                <th class="px-6 py-4 text-left">Género</th>
                <th class="px-6 py-4 text-left">Peso</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pets as $pet)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-{{ $pet->species === 'gato' ? 'cat' : 'dog' }} text-purple-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-800">{{ $pet->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $pet->owner->name }}</td>
                <td class="px-6 py-4 text-gray-500 capitalize">{{ $pet->species }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $pet->breed ?? '—' }}</td>
                <td class="px-6 py-4">
                    @if($pet->gender)
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $pet->gender === 'macho' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ ucfirst($pet->gender) }}
                    </span>
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $pet->weight ? $pet->weight . ' kg' : '—' }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('pets.show', $pet) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('pets.edit', $pet) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('pets.destroy', $pet) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta mascota?')">
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
                    <i class="fa-solid fa-dog text-4xl mb-2 block"></i>
                    No hay mascotas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $pets->links() }}
    </div>
</div>

@endsection