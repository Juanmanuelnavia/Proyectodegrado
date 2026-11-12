@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Buenos días' : ($hour < 18 ? 'Buenas tardes' : 'Buenas noches');
    $citasSemana = \App\Models\Appointment::whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    $ingresosSemana = \App\Models\Invoice::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'pagada')->sum('total');
    $mediadiaria = $citasSemana > 0 ? round($citasSemana / 7, 1) : 0;
    $facturasPendientes = \App\Models\Invoice::where('status', 'pendiente')->count();
@endphp

{{-- Saludo + acciones rápidas --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
        <p class="text-gray-400 text-sm mt-1">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('appointments.create') }}"
           class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus text-purple-600"></i> Nueva cita
        </a>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'veterinario')
        <a href="{{ route('medical-records.create') }}"
           class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-file-medical text-purple-600"></i> Nueva historia
        </a>
        @endif
        <a href="{{ route('invoices.create') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-file-invoice-dollar"></i> Nueva factura
        </a>
    </div>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <a href="{{ route('appointments.index') }}" class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Citas hoy</p>
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-blue-500 text-sm"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['appointments'] }}</p>
        <p class="text-xs text-gray-400 mt-1">programadas</p>
    </a>

    <a href="{{ route('owners.index') }}" class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Propietarios</p>
            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-users text-purple-500 text-sm"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['owners'] }}</p>
        <p class="text-xs text-gray-400 mt-1">registrados</p>
    </a>

    <a href="{{ route('pets.index') }}" class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Mascotas</p>
            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-dog text-purple-500 text-sm"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['pets'] }}</p>
        <p class="text-xs text-gray-400 mt-1">en sistema</p>
    </a>

    <a href="{{ route('inventory.index', ['stock' => 'bajo']) }}" class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Stock bajo</p>
            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-sm"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['low_stock'] }}</p>
        <p class="text-xs text-gray-400 mt-1">productos</p>
    </a>

    <a href="{{ route('notifications') }}" class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Notificaciones</p>
            <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-bell text-yellow-500 text-sm"></i>
            </div>
        </div>
        @php $notifCount = \App\Http\Controllers\NotificationController::getNotifications()->count(); @endphp
        <p class="text-3xl font-bold text-gray-800">{{ $notifCount }}</p>
        <p class="text-xs text-gray-400 mt-1">pendientes</p>
    </a>
</div>

{{-- Gráfica + resumen semanal --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Gráfica actividad semanal --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2 border border-gray-100">
        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Actividad semanal</h2>
            <p class="text-xs text-gray-400">Citas por día (7 días)</p>
        </div>
        <div class="flex gap-6">
            <div class="flex-1">
                <canvas id="citasChart" height="120"></canvas>
            </div>
            <div class="w-40 space-y-3">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Esta semana</p>
                    <p class="text-xl font-bold text-blue-600">{{ $citasSemana }} citas</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Ingresos semana</p>
                    <p class="text-xl font-bold text-green-600">${{ number_format($ingresosSemana, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Media diaria</p>
                    <p class="text-xl font-bold text-orange-500">{{ $mediadiaria }} citas/día</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Facturas pendientes</p>
                    <a href="{{ route('invoices.index', ['status' => 'pendiente']) }}"
                       class="text-xl font-bold text-red-500 hover:underline">{{ $facturasPendientes }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tipos de mascotas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700 mb-1">Tipos de mascotas</h2>
        <p class="text-xs text-gray-400 mb-4">Distribución por especie</p>
        <canvas id="mascotasChart" height="180"></canvas>
    </div>
</div>

{{-- Próximas citas + Alertas clínicas --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Próximas citas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-sm font-semibold text-gray-700">Próximas citas</h2>
                <p class="text-xs text-gray-400">{{ $upcoming->count() }} citas programadas hoy</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs text-purple-600 hover:underline">Ver agenda →</a>
        </div>

        <div class="mt-4 space-y-2">
            @forelse($upcoming as $appointment)
            <a href="{{ route('appointments.show', $appointment) }}"
               class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition border border-gray-100">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($appointment->pet->photo)
                    <img src="{{ Storage::url($appointment->pet->photo) }}" class="w-full h-full rounded-full object-cover">
                    @else
                    <i class="fa-solid fa-{{ $appointment->pet->species === 'gato' ? 'cat' : 'dog' }} text-purple-500 text-sm"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-purple-600">{{ $appointment->pet->name }}</p>
                        @if($appointment->reason)
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-xs">{{ $appointment->reason }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">{{ $appointment->owner->name }}</p>
                </div>
                <span class="text-sm font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                </span>
            </a>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="fa-solid fa-calendar-xmark text-3xl mb-2 block"></i>
                <p class="text-sm">No hay citas para hoy</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Alertas clínicas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-sm font-semibold text-gray-700">Alertas clínicas</h2>
                <p class="text-xs text-gray-400">Acciones que requieren atención</p>
            </div>
            <a href="{{ route('notifications') }}" class="text-xs text-purple-600 hover:underline">Ver todas →</a>
        </div>

        <div class="mt-4 space-y-2">
            @php
                $alerts = \App\Http\Controllers\NotificationController::getNotifications()->take(6);
                $alertColors = [
                    'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'badge' => 'bg-blue-100 text-blue-700'],
                    'purple'   => ['bg' => 'bg-purple-50',   'text' => 'text-purple-600',   'badge' => 'bg-purple-100 text-purple-700'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-700'],
                    'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'badge' => 'bg-orange-100 text-orange-700'],
                    'red'    => ['bg' => 'bg-red-50',    'text' => 'text-red-600',    'badge' => 'bg-red-100 text-red-700'],
                ];
                $alertLabels = [
                    'cita'            => 'agenda',
                    'cita_hoy'        => 'agenda',
                    'vacuna'          => 'historial',
                    'desparasitacion' => 'historial',
                    'inventario'      => 'inventario',
                    'vencimiento'     => 'inventario',
                ];
            @endphp

            @forelse($alerts as $alert)
            @php $c = $alertColors[$alert['color']] ?? $alertColors['purple']; @endphp
            <a href="{{ $alert['link'] }}"
               class="flex items-center gap-3 p-3 rounded-xl {{ $c['bg'] }} hover:opacity-80 transition">
                <i class="fa-solid fa-{{ $alert['icon'] }} {{ $c['text'] }} text-sm w-4 text-center"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $alert['title'] }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Str::limit($alert['message'], 45) }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $c['badge'] }} whitespace-nowrap">
                    {{ $alertLabels[$alert['type']] ?? 'alerta' }}
                </span>
            </a>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="fa-solid fa-circle-check text-3xl mb-2 block text-green-400"></i>
                <p class="text-sm">Todo en orden 🎉</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('citasChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Citas',
                data: {!! json_encode($citasData) !!},
                backgroundColor: function(context) {
                    const index = context.dataIndex;
                    const today = new Date().getDay();
                    const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                    return index === {!! now()->dayOfWeek !!} ? 'rgba(13, 148, 136, 0.9)' : 'rgba(219, 234, 254, 0.9)';
                },
                borderColor: function(context) {
                    return context.dataIndex === {!! now()->dayOfWeek !!} ? 'rgb(13, 148, 136)' : 'rgb(147, 197, 253)';
                },
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('mascotasChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($mascotasTipos->pluck('species')->map(fn($s) => ucfirst($s))) !!},
            datasets: [{
                data: {!! json_encode($mascotasTipos->pluck('total')) !!},
                backgroundColor: ['#0d9488', '#8b5cf6', '#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { padding: 15, font: { size: 11 } } } },
            cutout: '65%',
        }
    });
</script>

@endsection