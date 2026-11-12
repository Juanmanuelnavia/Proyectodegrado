@extends('layouts.app')
@section('title', 'Búsqueda')

@section('content')

<div class="mb-6">
    <p class="text-gray-500 text-sm">
        @if($query)
            Resultados para <span class="font-semibold text-gray-800">"{{ $query }}"</span>
        @else
            Escribe algo para buscar
        @endif
    </p>
</div>

@if(isset($owners) && ($owners->count() || $pets->count() || $appointments->count() || $records->count()))

    {{-- Propietarios --}}
    @if($owners->count())
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-users text-purple-600 mr-2"></i>Propietarios
        </h2>
        @foreach($owners as $owner)
        <a href="{{ route('owners.show', $owner) }}"
           class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-xl px-2 transition">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user text-purple-600 text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $owner->name }}</p>
                <p class="text-xs text-gray-500">{{ $owner->phone }} · {{ $owner->document ?? 'Sin documento' }}</p>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Mascotas --}}
    @if($pets->count())
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-dog text-purple-600 mr-2"></i>Mascotas
        </h2>
        @foreach($pets as $pet)
        <a href="{{ route('pets.show', $pet) }}"
           class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-xl px-2 transition">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center overflow-hidden">
                @if($pet->photo)
                <img src="{{ Storage::url($pet->photo) }}" class="w-full h-full object-cover">
                @else
                <i class="fa-solid fa-{{ $pet->species === 'gato' ? 'cat' : 'dog' }} text-purple-600 text-sm"></i>
                @endif
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $pet->name }}</p>
                <p class="text-xs text-gray-500">
    {{ ucfirst($pet->species) }} · {{ $pet->owner->name }}
    @if($pet->birth_date)
    · {{ \Carbon\Carbon::parse($pet->birth_date)->age }} años
    @endif
</p>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Citas --}}
    @if($appointments->count())
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-calendar-check text-blue-600 mr-2"></i>Citas
        </h2>
        @foreach($appointments as $appointment)
        <a href="{{ route('appointments.show', $appointment) }}"
           class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-xl px-2 transition">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-calendar text-blue-600 text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $appointment->pet->name }} — {{ $appointment->owner->name }}</p>
                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y h:i A') }}
                    · {{ $appointment->reason ?? 'Sin motivo' }}
                </p>
            </div>
            @php
                $colors = ['pendiente' => 'bg-yellow-100 text-yellow-700', 'confirmada' => 'bg-blue-100 text-blue-700', 'completada' => 'bg-green-100 text-green-700', 'cancelada' => 'bg-red-100 text-red-600'];
            @endphp
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$appointment->status] }}">
                {{ ucfirst($appointment->status) }}
            </span>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Historias clínicas --}}
    @if($records->count())
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-file-medical text-purple-600 mr-2"></i>Historias clínicas
        </h2>
        @foreach($records as $record)
        <a href="{{ route('medical-records.show', $record) }}"
           class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-xl px-2 transition">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-file-medical text-purple-600 text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $record->pet->name }}</p>
                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($record->visit_date)->format('d/m/Y') }} · {{ Str::limit($record->symptoms, 60) }}</p>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </a>
        @endforeach
    </div>
    @endif

@elseif($query)
<div class="bg-white rounded-2xl shadow-sm p-12 text-center text-gray-400">
    <i class="fa-solid fa-magnifying-glass text-5xl mb-3 block"></i>
    <p class="text-lg font-medium">Sin resultados</p>
    <p class="text-sm mt-1">No encontramos nada para "{{ $query }}"</p>
</div>
@endif

@endsection