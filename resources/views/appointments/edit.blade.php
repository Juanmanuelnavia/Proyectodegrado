@extends('layouts.app')
@section('title', 'Editar Cita')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mascota *</label>
                    <select name="pet_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($pets as $pet)
                        <option value="{{ $pet->id }}"
                            {{ old('pet_id', $appointment->pet_id) == $pet->id ? 'selected' : '' }}>
                            {{ $pet->name }} ({{ $pet->owner->name }})
                        </option>
                        @endforeach
                    </select>
                    @error('pet_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                    <select name="owner_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($owners as $owner)
                        <option value="{{ $owner->id }}"
                            {{ old('owner_id', $appointment->owner_id) == $owner->id ? 'selected' : '' }}>
                            {{ $owner->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('owner_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Veterinario *</label>
                    <select name="user_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($veterinarians as $vet)
                        <option value="{{ $vet->id }}"
                            {{ old('user_id', $appointment->user_id) == $vet->id ? 'selected' : '' }}>
                            {{ $vet->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora *</label>
                    <input type="datetime-local" name="scheduled_at"
                        value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('scheduled_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="pendiente"  {{ old('status', $appointment->status) === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ old('status', $appointment->status) === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completada" {{ old('status', $appointment->status) === 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada"  {{ old('status', $appointment->status) === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de consulta</label>
                <input type="text" name="reason" value="{{ old('reason', $appointment->reason) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas adicionales</label>
                <textarea name="notes" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar
                </button>
                <a href="{{ route('appointments.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection