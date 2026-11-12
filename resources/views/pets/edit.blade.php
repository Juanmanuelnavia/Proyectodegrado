@extends('layouts.app')
@section('title', 'Editar Mascota')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('pets.update', $pet) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                    <select name="owner_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($owners as $owner)
                        <option value="{{ $owner->id }}"
                            {{ old('owner_id', $pet->owner_id) == $owner->id ? 'selected' : '' }}>
                            {{ $owner->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('owner_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name', $pet->name) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especie *</label>
                    <select name="species"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="perro" {{ old('species', $pet->species) === 'perro' ? 'selected' : '' }}>Perro</option>
                        <option value="gato" {{ old('species', $pet->species) === 'gato' ? 'selected' : '' }}>Gato</option>
                        <option value="ave" {{ old('species', $pet->species) === 'ave' ? 'selected' : '' }}>Ave</option>
                        <option value="conejo" {{ old('species', $pet->species) === 'conejo' ? 'selected' : '' }}>Conejo</option>
                        <option value="otro" {{ old('species', $pet->species) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('species')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Raza</label>
                    <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('breed')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $pet->birth_date) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                    <select name="gender"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccionar</option>
                        <option value="macho" {{ old('gender', $pet->gender) === 'macho' ? 'selected' : '' }}>Macho</option>
                        <option value="hembra" {{ old('gender', $pet->gender) === 'hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" value="{{ old('color', $pet->color) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $pet->weight) }}" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">Foto de la mascota</label>
    @if($pet->photo)
    <div class="mb-3">
        <img src="{{ Storage::url($pet->photo) }}" alt="{{ $pet->name }}"
            class="w-24 h-24 rounded-xl object-cover border border-gray-200">
        <p class="text-xs text-gray-400 mt-1">Foto actual — sube una nueva para reemplazarla</p>
    </div>
    @endif
    <input type="file" name="photo" accept="image/*"
        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
    <p class="text-xs text-gray-400 mt-1">JPG, PNG. Máximo 2MB.</p>
</div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar
                </button>
                <a href="{{ route('pets.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection