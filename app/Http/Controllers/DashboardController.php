<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'owners'       => Owner::count(),
            'pets'         => Pet::count(),
            'appointments' => Appointment::whereDate('scheduled_at', today())->count(),
            'low_stock'    => Inventory::whereColumn('stock', '<=', 'min_stock')->count(),
        ];

        $upcoming = Appointment::with(['pet', 'owner'])
            ->whereDate('scheduled_at', today())
            ->where('status', 'pendiente')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $low_stock = Inventory::whereColumn('stock', '<=', 'min_stock')
            ->take(5)
            ->get();

        // Citas por día últimos 7 días
        $citasPorDia = Appointment::select(
                DB::raw('DATE(scheduled_at) as fecha'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('scheduled_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $labels = [];
        $citasData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->locale('es')->isoFormat('ddd D');
            $citasData[] = $citasPorDia[$date]->total ?? 0;
        }

        // Tipos de mascotas
        $mascotasTipos = Pet::select('species', DB::raw('COUNT(*) as total'))
            ->groupBy('species')
            ->get();

        // Citas por estado
        $citasEstado = Appointment::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        return view('dashboard', compact(
            'stats', 'upcoming', 'low_stock',
            'labels', 'citasData', 'mascotasTipos', 'citasEstado'
        ));
    }
}