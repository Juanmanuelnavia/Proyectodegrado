@extends('layouts.app')
@section('title', 'Nueva Historia Clínica')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Plantillas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-wand-magic-sparkles text-purple-600 mr-2"></i>Plantillas rápidas
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="templates-container">
            <div class="col-span-full text-center text-gray-400 text-sm py-2">
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Cargando plantillas...
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('medical-records.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Datos básicos --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    <i class="fa-solid fa-file-medical text-purple-600 mr-2"></i>Datos de la visita
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mascota *</label>
                        <select name="pet_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Seleccionar mascota</option>
                            @foreach($pets as $pet)
                            <option value="{{ $pet->id }}"
                                {{ old('pet_id', $selected_pet) == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} ({{ $pet->owner->name }})
                            </option>
                            @endforeach
                        </select>
                        @error('pet_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Veterinario *</label>
                        <select name="user_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Seleccionar veterinario</option>
                            @foreach($veterinarians as $vet)
                            <option value="{{ $vet->id }}" {{ old('user_id') == $vet->id ? 'selected' : '' }}>
                                {{ $vet->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de visita *</label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('visit_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight') }}" step="0.01"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Ej: 5.50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temperatura (°C)</label>
                            <input type="number" name="temperature" value="{{ old('temperature') }}" step="0.1"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Ej: 38.5">
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Síntomas *</label>
                    <textarea name="symptoms" id="symptoms" rows="3"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Describe los síntomas observados...">{{ old('symptoms') }}</textarea>
                    @error('symptoms')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                    <textarea name="treatment" id="treatment" rows="3"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Tratamiento indicado...">{{ old('treatment') }}</textarea>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea name="observations" id="observations" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Observaciones adicionales...">{{ old('observations') }}</textarea>
                </div>
            </div>

            {{-- Diagnóstico --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    <i class="fa-solid fa-stethoscope text-purple-600 mr-2"></i>Diagnóstico
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condición / Enfermedad</label>
                        <input type="text" name="condition" id="condition" value="{{ old('condition') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: Gastroenteritis aguda">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                        <input type="text" name="medication" value="{{ old('medication') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: Metronidazol">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                        <input type="text" name="dosage" value="{{ old('dosage') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: 250mg cada 12 horas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Próxima visita</label>
                        <input type="date" name="next_visit" value="{{ old('next_visit') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar
                </button>
                <a href="{{ route('medical-records.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Cargar plantillas
fetch('{{ route("templates.index") }}')
    .then(r => r.json())
    .then(templates => {
        const container = document.getElementById('templates-container');
        const icons = {
            vacunacion:     'syringe',
            general:        'stethoscope',
            desparasitacion:'bug',
            cirugia:        'scissors',
            enfermedad:     'virus',
        };
        const colors = {
            vacunacion:     'bg-blue-50 text-blue-700 hover:bg-blue-100 border-blue-200',
            general:        'bg-purple-50 text-purple-700 hover:bg-purple-100 border-purple-200',
            desparasitacion:'bg-orange-50 text-orange-700 hover:bg-orange-100 border-orange-200',
            cirugia:        'bg-purple-50 text-purple-700 hover:bg-purple-100 border-purple-200',
            enfermedad:     'bg-red-50 text-red-700 hover:bg-red-100 border-red-200',
        };

        container.innerHTML = templates.map(t => `
            <button type="button"
                onclick="applyTemplate(${JSON.stringify(t).replace(/"/g, '&quot;')})"
                class="flex items-center gap-2 px-4 py-3 rounded-xl border text-sm font-medium transition ${colors[t.category] || 'bg-gray-50 text-gray-700 hover:bg-gray-100 border-gray-200'}">
                <i class="fa-solid fa-${icons[t.category] || 'file-medical'}"></i>
                ${t.name}
            </button>
        `).join('');
    });

function applyTemplate(template) {
    document.getElementById('symptoms').value    = template.symptoms;
    document.getElementById('treatment').value   = template.treatment || '';
    document.getElementById('observations').value = template.observations || '';
    document.getElementById('condition').value   = template.condition || '';

    // Scroll al formulario
    document.getElementById('symptoms').scrollIntoView({ behavior: 'smooth', block: 'center' });

    // Efecto visual
    ['symptoms', 'treatment', 'observations', 'condition'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('ring-2', 'ring-purple-400');
            setTimeout(() => el.classList.remove('ring-2', 'ring-purple-400'), 2000);
        }
    });
}
</script>

@endsection