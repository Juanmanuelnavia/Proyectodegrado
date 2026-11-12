@extends('layouts.app')
@section('title', 'Nueva Mascota')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                    <select name="owner_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccionar propietario</option>
                        @foreach($owners as $owner)
                        <option value="{{ $owner->id }}"
                            {{ old('owner_id', $selected_owner) == $owner->id ? 'selected' : '' }}>
                            {{ $owner->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('owner_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: Max">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especie *</label>
                    <select name="species"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccionar especie</option>
                        <option value="perro" {{ old('species') === 'perro' ? 'selected' : '' }}>Perro</option>
                        <option value="gato" {{ old('species') === 'gato' ? 'selected' : '' }}>Gato</option>
                        <option value="ave" {{ old('species') === 'ave' ? 'selected' : '' }}>Ave</option>
                        <option value="conejo" {{ old('species') === 'conejo' ? 'selected' : '' }}>Conejo</option>
                        <option value="otro" {{ old('species') === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('species')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Raza</label>
                    <input type="text" name="breed" value="{{ old('breed') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: Labrador">
                    @error('breed')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                    <select name="gender"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccionar</option>
                        <option value="macho" {{ old('gender') === 'macho' ? 'selected' : '' }}>Macho</option>
                        <option value="hembra" {{ old('gender') === 'hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" value="{{ old('color') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: Café con blanco">
                    @error('color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight') }}" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ej: 5.50">
                    @error('weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">Foto de la mascota</label>
    <input type="file" name="photo" accept="image/*"
        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
    <p class="text-xs text-gray-400 mt-1">JPG, PNG. Máximo 2MB.</p>
</div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar
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