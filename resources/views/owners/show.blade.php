@extends('layouts.app')
@section('title', 'Detalle Propietario')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Info propietario --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-user text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $owner->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $owner->document ?? 'Sin documento' }}</p>
            </div>
            <a href="{{ route('owners.edit', $owner) }}"
               class="ml-auto bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class="fa-solid fa-pen mr-1"></i> Editar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Teléfono</p>
                <p class="text-sm font-medium text-gray-800">{{ $owner->phone }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Email</p>
                <p class="text-sm font-medium text-gray-800">{{ $owner->email ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Dirección</p>
                <p class="text-sm font-medium text-gray-800">{{ $owner->address ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Mascotas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Mascotas</h3>
            <a href="{{ route('pets.create', ['owner_id' => $owner->id]) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class="fa-solid fa-plus mr-1"></i> Nueva mascota
            </a>
        </div>

        @forelse($owner->pets as $pet)
        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-dog text-purple-600"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $pet->name }}</p>
                <p class="text-xs text-gray-500">{{ $pet->species }} — {{ $pet->breed ?? 'Sin raza' }}</p>
            </div>
            <a href="{{ route('pets.show', $pet) }}"
               class="text-purple-600 hover:underline text-sm">Ver detalle</a>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-6">Este propietario no tiene mascotas registradas</p>
        @endforelse
    </div>

</div>

@endsection