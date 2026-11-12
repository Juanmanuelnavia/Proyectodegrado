@extends('layouts.app')
@section('title', 'Calendario de Citas')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        {{-- Filtro por veterinario --}}
        <select id="vetFilter"
            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Todos los veterinarios</option>
            @foreach($veterinarians as $vet)
            <option value="{{ $vet->id }}">{{ $vet->name }}</option>
            @endforeach
        </select>

        {{-- Leyenda --}}
        <div class="flex items-center gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> Pendiente</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Confirmada</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Completada</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Cancelada</span>
        </div>
    </div>

    <a href="{{ route('appointments.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nueva cita
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6">
    <div id="calendar"></div>
</div>

{{-- Modal detalle cita --}}
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800" id="modal-title"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center gap-2 text-gray-600">
                <i class="fa-solid fa-stethoscope text-purple-600 w-4"></i>
                <span id="modal-vet"></span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <i class="fa-solid fa-note-medical text-purple-600 w-4"></i>
                <span id="modal-reason"></span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <i class="fa-solid fa-circle w-4 text-center" id="modal-status-icon"></i>
                <span id="modal-status"></span>
            </div>
        </div>
        <div class="mt-5 flex gap-2">
            <a id="modal-link" href="#"
               class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition text-center">
                Ver detalle
            </a>
            <button onclick="closeModal()"
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                Cerrar
            </button>
        </div>
    </div>
</div>

{{-- FullCalendar --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/es.global.min.js"></script>

<script>
let calendar;

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        events: function(info, successCallback, failureCallback) {
            const vet = document.getElementById('vetFilter').value;
            fetch(`{{ route('appointments.events') }}?vet=${vet}`)
                .then(r => r.json())
                .then(data => successCallback(data))
                .catch(() => failureCallback());
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            const props = info.event.extendedProps;
            const statusLabels = {
                pendiente: 'Pendiente',
                confirmada: 'Confirmada',
                completada: 'Completada',
                cancelada: 'Cancelada',
            };
            const statusColors = {
                pendiente: 'text-yellow-500',
                confirmada: 'text-blue-500',
                completada: 'text-green-500',
                cancelada: 'text-red-500',
            };
            document.getElementById('modal-title').textContent = info.event.title;
            document.getElementById('modal-vet').textContent = props.vet;
            document.getElementById('modal-reason').textContent = props.reason;
            document.getElementById('modal-status').textContent = statusLabels[props.status] ?? props.status;
            document.getElementById('modal-status-icon').className = `fa-solid fa-circle w-4 text-center ${statusColors[props.status]}`;
            document.getElementById('modal-link').href = props.url;
            document.getElementById('modal').classList.remove('hidden');
        },
        height: 'auto',
        dayMaxEvents: 3,
    });

    calendar.render();

    document.getElementById('vetFilter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
});

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}
</script>

@endsection