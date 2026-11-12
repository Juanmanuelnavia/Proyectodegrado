@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar por nombre o email..."
            class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        <select name="role"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todos los roles</option>
            <option value="admin"          {{ request('role') === 'admin'          ? 'selected' : '' }}>Admin</option>
            <option value="veterinario"    {{ request('role') === 'veterinario'    ? 'selected' : '' }}>Veterinario</option>
            <option value="recepcionista"  {{ request('role') === 'recepcionista'  ? 'selected' : '' }}>Recepcionista</option>
        </select>
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if(request('search') || request('role'))
        <a href="{{ route('users.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </form>
    <a href="{{ route('users.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Nuevo usuario
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Usuario</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Teléfono</th>
                <th class="px-6 py-4 text-left">Rol</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        @if($user->id === auth()->id())
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">Tú</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $user->phone ?? '—' }}</td>
                <td class="px-6 py-4">
                    @php
                        $roleColors = [
                            'admin'         => 'bg-purple-100 text-purple-700',
                            'veterinario'   => 'bg-blue-100 text-blue-700',
                            'recepcionista' => 'bg-green-100 text-green-700',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColors[$user->role] }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('users.edit', $user) }}"
                           class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este usuario?')">
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
                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-users text-4xl mb-2 block"></i>
                    No hay usuarios registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>

@endsection