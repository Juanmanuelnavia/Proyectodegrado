<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Hospital Animal Veterinario</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="icon" type="image/jpeg" href="/logo.jpg">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white flex flex-col shadow-xl">
        {{-- Logo --}}
        <div class="px-6 py-6 border-b border-purple-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white">
                    <img src="/logo.jpg" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="font-bold text-sm leading-tight">Hospital Animal</p>
                    <p class="text-purple-300 text-xs">Veterinario</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('dashboard') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('owners.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('owners.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-users w-5 text-center"></i> Propietarios
            </a>
            <a href="{{ route('pets.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('pets.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-dog w-5 text-center"></i> Mascotas
            </a>
            <a href="{{ route('appointments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('appointments.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-calendar-check w-5 text-center"></i> Citas
            </a>
            <a href="{{ route('appointments.calendar') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('appointments.calendar') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center"></i> Calendario
            </a>
            <a href="{{ route('medical-records.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('medical-records.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-file-medical w-5 text-center"></i> Historias Clínicas
            </a>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'recepcionista')
            <a href="{{ route('invoices.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('invoices.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Facturación
            </a>
            @endif
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
            <a href="{{ route('inventory.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('inventory.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> Inventario
            </a>
            @endif
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
               {{ request()->routeIs('users.*') ? 'bg-white text-purple-800 font-semibold' : 'text-purple-100 hover:bg-purple-700' }}">
                <i class="fa-solid fa-user-gear w-5 text-center"></i> Usuarios
            </a>
            @endif
        </nav>

        {{-- Usuario --}}
        <div class="px-4 py-4 border-t border-purple-700">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 mb-3 hover:bg-purple-700 rounded-lg px-2 py-1.5 transition">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-purple-300 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
                <i class="fa-solid fa-pen text-purple-400 text-xs"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-purple-200 hover:bg-purple-700 transition">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 overflow-y-auto">
        {{-- Header --}}
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between gap-4">
            <h1 class="text-xl font-semibold text-gray-800 whitespace-nowrap">@yield('title')</h1>

            {{-- Buscador global --}}
            <form method="GET" action="{{ route('search') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Buscar mascota, propietario, cita..."
                        class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </form>

            <div class="flex items-center gap-3">
                {{-- Campanita --}}
                @php
                    $notifCount = \App\Http\Controllers\NotificationController::getNotifications()->count();
                @endphp
                <a href="{{ route('notifications') }}" class="relative">
                    <div class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition">
                        <i class="fa-solid fa-bell text-gray-600"></i>
                    </div>
                    @if($notifCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </a>

                <span class="text-sm text-gray-500 whitespace-nowrap">{{ now()->format('d/m/Y') }}</span>
            </div>
        </header>

        {{-- Alertas --}}
        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Contenido --}}
        <div class="px-8 py-4">
            @yield('content')
        </div>
    </main>

</div>

</body>
</html>