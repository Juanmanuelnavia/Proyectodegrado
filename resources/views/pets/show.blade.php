@extends('layouts.app')
@section('title', 'Detalle Mascota')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Perfil mascota --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-start gap-6 mb-6">
            {{-- Foto --}}
            <div class="flex-shrink-0">
                @if($pet->photo)
                <img src="{{ Storage::url($pet->photo) }}" alt="{{ $pet->name }}"
                    class="w-28 h-28 rounded-2xl object-cover border-4 border-purple-100 shadow">
                @else
                <div class="w-28 h-28 bg-purple-100 rounded-2xl flex items-center justify-center border-4 border-purple-50">
                    <i class="fa-solid fa-{{ $pet->species === 'gato' ? 'cat' : 'dog' }} text-purple-400 text-5xl"></i>
                </div>
                @endif
            </div>

            {{-- Info principal --}}
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $pet->name }}</h2>
                        <p class="text-gray-500">{{ ucfirst($pet->species) }} · {{ $pet->breed ?? 'Sin raza' }}</p>
                        @if($pet->birth_date)
                        <p class="text-sm text-purple-600 font-medium mt-1">
                            <i class="fa-solid fa-cake-candles mr-1"></i>
                            {{ \Carbon\Carbon::parse($pet->birth_date)->age }} años
                            <span class="text-gray-400 font-normal">({{ \Carbon\Carbon::parse($pet->birth_date)->format('d/m/Y') }})</span>
                        </p>
                        @endif
                    </div>
                    <a href="{{ route('pets.edit', $pet) }}"
                       class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                        <i class="fa-solid fa-pen mr-1"></i> Editar
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 mb-1">Propietario</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pet->owner->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 mb-1">Género</p>
                        <p class="text-sm font-medium text-gray-800 capitalize">{{ $pet->gender ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 mb-1">Peso</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pet->weight ? $pet->weight . ' kg' : '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 mb-1">Color</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pet->color ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $pet->medicalRecords->count() }}</p>
            <p class="text-sm text-gray-500 mt-1">Visitas médicas</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $pet->appointments->count() }}</p>
            <p class="text-sm text-gray-500 mt-1">Citas totales</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-purple-600">
                {{ $pet->medicalRecords->flatMap->diagnoses->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Diagnósticos</p>
        </div>
    </div>

    {{-- Historias clínicas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fa-solid fa-file-medical text-purple-600 mr-2"></i>Historias clínicas
            </h3>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
            <a href="{{ route('medical-records.create', ['pet_id' => $pet->id]) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class="fa-solid fa-plus mr-1"></i> Nueva historia
            </a>
            @endif
        </div>

        @forelse($pet->medicalRecords->sortByDesc('visit_date') as $record)
        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-stethoscope text-purple-600 text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($record->visit_date)->format('d/m/Y') }}
                    <span class="text-gray-400 font-normal">· {{ $record->veterinarian->name }}</span>
                </p>
                <p class="text-xs text-gray-500 truncate">{{ Str::limit($record->symptoms, 70) }}</p>
                @if($record->diagnoses->count() > 0)
                <div class="flex gap-1 mt-1 flex-wrap">
                    @foreach($record->diagnoses as $diagnosis)
                    <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-xs">
                        {{ $diagnosis->condition }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('medical-records.show', $record) }}"
                   class="text-purple-600 hover:underline text-sm">Ver</a>
                <a href="{{ route('medical-records.pdf', $record) }}"
                   class="text-red-500 hover:underline text-sm">PDF</a>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-6">No hay historias clínicas registradas</p>
        @endforelse
    </div>

</div>

@endsection