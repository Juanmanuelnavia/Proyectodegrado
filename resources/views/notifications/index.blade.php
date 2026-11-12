@extends('layouts.app')
@section('title', 'Notificaciones')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">{{ $notifications->count() }} notificaciones activas</p>
    </div>

    @forelse($notifications as $notification)
    @php
        $colors = [
            'blue'   => 'bg-blue-50 border-blue-200',
            'purple'   => 'bg-purple-50 border-purple-200',
            'yellow' => 'bg-yellow-50 border-yellow-200',
            'orange' => 'bg-orange-50 border-orange-200',
            'red'    => 'bg-red-50 border-red-200',
        ];
        $iconColors = [
            'blue'   => 'bg-blue-100 text-blue-600',
            'purple'   => 'bg-purple-100 text-purple-600',
            'yellow' => 'bg-yellow-100 text-yellow-600',
            'orange' => 'bg-orange-100 text-orange-600',
            'red'    => 'bg-red-100 text-red-600',
        ];
    @endphp
    <a href="{{ $notification['link'] }}"
       class="flex items-center gap-4 p-4 rounded-2xl border mb-3 hover:shadow-md transition {{ $colors[$notification['color']] }}">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconColors[$notification['color']] }}">
            <i class="fa-solid fa-{{ $notification['icon'] }} text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">{{ $notification['title'] }}</p>
            <p class="text-sm text-gray-600 truncate">{{ $notification['message'] }}</p>
        </div>
        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification['time'] }}</span>
    </a>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center text-gray-400">
        <i class="fa-solid fa-bell-slash text-5xl mb-3 block"></i>
        <p class="text-lg font-medium">Sin notificaciones</p>
        <p class="text-sm mt-1">Todo está en orden 🎉</p>
    </div>
    @endforelse
</div>

@endsection