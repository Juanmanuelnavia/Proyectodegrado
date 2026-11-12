<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Animal Veterinario — Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="icon" type="image/jpeg" href="/logo.jpg">
</head>
<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex">

    {{-- Panel izquierdo decorativo --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-purple-700 to-purple-900 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Círculos decorativos --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-purple-600 rounded-full opacity-30 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-800 rounded-full opacity-40 translate-x-1/3 translate-y-1/3"></div>

        <div class="relative z-10 text-center text-white">
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl overflow-hidden">
                <img src="/logo.jpg" alt="Logo" class="w-full h-full object-contain p-1">
            </div>
            <h1 class="text-4xl font-bold mb-2">Hospital Animal</h1>
            <h2 class="text-2xl font-light text-purple-200 mb-6">Veterinario</h2>
            <p class="text-purple-300 text-sm max-w-xs mx-auto leading-relaxed">
                Sistema de gestión integral para la administración de pacientes, citas médicas e inventario.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-6 text-center">
                <div class="bg-purple-800 bg-opacity-50 rounded-2xl p-4">
                    <i class="fa-solid fa-dog text-2xl mb-2 text-purple-300"></i>
                    <p class="text-xs text-purple-300">Mascotas</p>
                </div>
                <div class="bg-purple-800 bg-opacity-50 rounded-2xl p-4">
                    <i class="fa-solid fa-calendar-check text-2xl mb-2 text-purple-300"></i>
                    <p class="text-xs text-purple-300">Citas</p>
                </div>
                <div class="bg-purple-800 bg-opacity-50 rounded-2xl p-4">
                    <i class="fa-solid fa-file-medical text-2xl mb-2 text-purple-300"></i>
                    <p class="text-xs text-purple-300">Historias</p>
                </div>
            </div>

            <p class="mt-10 text-purple-400 text-xs">
                Cali, Valle del Cauca · Barrio El Caney
            </p>
        </div>
    </div>

    {{-- Panel derecho - formulario --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            {{-- Logo móvil --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 rounded-2xl overflow-hidden mx-auto mb-3 bg-white shadow">
                    <img src="/logo.jpg" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Hospital Animal Veterinario</h1>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Bienvenido</h2>
            <p class="text-gray-500 text-sm mb-8">Ingresa tus credenciales para continuar</p>

            {{-- Errores --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50"
                            placeholder="correo@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" name="password" required
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-purple-600">
                        Recordarme
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Iniciar sesión
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} Hospital Animal Veterinario · Cali, Colombia
            </p>
        </div>
    </div>

</div>

</body>
</html> 
