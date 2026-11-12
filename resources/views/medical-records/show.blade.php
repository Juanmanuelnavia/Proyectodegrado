@extends('layouts.app')
@section('title', 'Historia Clínica')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-file-medical text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $medicalRecord->pet->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $medicalRecord->pet->owner->name }} —
                        {{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
                <a href="{{ route('medical-records.edit', $medicalRecord) }}"
                   class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-pen mr-1"></i> Editar
                </a>
                @endif
                <a href="{{ route('medical-records.pdf', $medicalRecord) }}"
                   class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-file-pdf mr-1"></i> Historia PDF
                </a>
                <a href="{{ route('medical-records.formula', $medicalRecord) }}"
                   class="bg-purple-50 text-purple-600 hover:bg-purple-100 px-4 py-2 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-prescription mr-1"></i> Fórmula médica
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Veterinario</p>
                <p class="text-sm font-medium text-gray-800">{{ $medicalRecord->veterinarian->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Peso</p>
                <p class="text-sm font-medium text-gray-800">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Temperatura</p>
                <p class="text-sm font-medium text-gray-800">{{ $medicalRecord->temperature ? $medicalRecord->temperature . ' °C' : '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Fecha visita</p>
                <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Síntomas</p>
                <p class="text-sm text-gray-800">{{ $medicalRecord->symptoms }}</p>
            </div>
            @if($medicalRecord->treatment)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Tratamiento</p>
                <p class="text-sm text-gray-800">{{ $medicalRecord->treatment }}</p>
            </div>
            @endif
            @if($medicalRecord->observations)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Observaciones</p>
                <p class="text-sm text-gray-800">{{ $medicalRecord->observations }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Diagnósticos --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-stethoscope text-purple-600 mr-2"></i>Diagnósticos
        </h3>

        @forelse($medicalRecord->diagnoses as $diagnosis)
        <div class="border border-gray-100 rounded-xl p-4 mb-3 last:mb-0">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Condición</p>
                    <p class="text-sm font-medium text-gray-800">{{ $diagnosis->condition }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Medicamento</p>
                    <p class="text-sm text-gray-800">{{ $diagnosis->medication ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Dosis</p>
                    <p class="text-sm text-gray-800">{{ $diagnosis->dosage ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Próxima visita</p>
                    <p class="text-sm text-gray-800">
                        {{ $diagnosis->next_visit ? \Carbon\Carbon::parse($diagnosis->next_visit)->format('d/m/Y') : '—' }}
                    </p>
                </div>
            </div>
            @if($diagnosis->vaccine_name || $diagnosis->deworming_date)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Vacuna aplicada</p>
                    <p class="text-sm font-medium text-gray-800">{{ $diagnosis->vaccine_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Próxima dosis</p>
                    <p class="text-sm text-gray-800">
                        {{ $diagnosis->vaccine_date ? \Carbon\Carbon::parse($diagnosis->vaccine_date)->format('d/m/Y') : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Próxima desparasitación</p>
                    <p class="text-sm text-gray-800">
                        {{ $diagnosis->deworming_date ? \Carbon\Carbon::parse($diagnosis->deworming_date)->format('d/m/Y') : '—' }}
                    </p>
                </div>
            </div>
            @endif
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-6">No hay diagnósticos registrados</p>
        @endforelse
    </div>

</div>

@endsection