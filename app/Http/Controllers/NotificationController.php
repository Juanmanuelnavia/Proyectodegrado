<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Inventory;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->getNotifications();
        return view('notifications.index', compact('notifications'));
    }

    public static function getNotifications()
    {
        $notifications = collect();

        // Citas de mañana
        $tomorrow = Appointment::with(['pet', 'owner'])
            ->whereDate('scheduled_at', now()->addDay())
            ->where('status', 'pendiente')
            ->get();

        foreach ($tomorrow as $apt) {
            $notifications->push([
                'type'    => 'cita',
                'color'   => 'blue',
                'icon'    => 'calendar-check',
                'title'   => 'Cita mañana',
                'message' => "{$apt->pet->name} tiene cita mañana a las " .
                             \Carbon\Carbon::parse($apt->scheduled_at)->format('h:i A') .
                             " — {$apt->owner->name}",
                'link'    => route('appointments.show', $apt->id),
                'time'    => 'Mañana',
            ]);
        }

        // Citas de hoy pendientes
        $today = Appointment::with(['pet', 'owner'])
            ->whereDate('scheduled_at', today())
            ->where('status', 'pendiente')
            ->get();

        foreach ($today as $apt) {
            $notifications->push([
                'type'    => 'cita_hoy',
                'color'   => 'purple',
                'icon'    => 'clock',
                'title'   => 'Cita hoy',
                'message' => "{$apt->pet->name} tiene cita hoy a las " .
                             \Carbon\Carbon::parse($apt->scheduled_at)->format('h:i A') .
                             " — {$apt->owner->name}",
                'link'    => route('appointments.show', $apt->id),
                'time'    => 'Hoy',
            ]);
        }

        // Vacunas vencidas o por vencer en 7 días
        $vaccines = Diagnosis::with(['medicalRecord.pet.owner'])
            ->whereNotNull('vaccine_date')
            ->whereNotNull('vaccine_name')
            ->where('vaccine_date', '<=', now()->addDays(7))
            ->get();

        foreach ($vaccines as $diag) {
            $pet = $diag->medicalRecord->pet;
            $days = now()->diffInDays($diag->vaccine_date, false);
            $isExpired = $days < 0;

            $notifications->push([
                'type'    => 'vacuna',
                'color'   => $isExpired ? 'red' : 'yellow',
                'icon'    => 'syringe',
                'title'   => $isExpired ? 'Vacuna vencida' : 'Vacuna próxima',
                'message' => "{$pet->name} — {$diag->vaccine_name} " .
                             ($isExpired
                                ? "venció hace " . abs($days) . " día(s)"
                                : "vence en {$days} día(s)"),
                'link'    => route('pets.show', $pet->id),
                'time'    => $isExpired ? 'Vencida' : "En {$days} días",
            ]);
        }

        // Desparasitación vencida o por vencer
        $dewormings = Diagnosis::with(['medicalRecord.pet'])
            ->whereNotNull('deworming_date')
            ->where('deworming_date', '<=', now()->addDays(7))
            ->get();

        foreach ($dewormings as $diag) {
            $pet = $diag->medicalRecord->pet;
            $days = now()->diffInDays($diag->deworming_date, false);
            $isExpired = $days < 0;

            $notifications->push([
                'type'    => 'desparasitacion',
                'color'   => $isExpired ? 'red' : 'orange',
                'icon'    => 'bug',
                'title'   => $isExpired ? 'Desparasitación vencida' : 'Desparasitación próxima',
                'message' => "{$pet->name} necesita desparasitación " .
                             ($isExpired ? "desde hace " . abs($days) . " día(s)" : "en {$days} día(s)"),
                'link'    => route('pets.show', $pet->id),
                'time'    => $isExpired ? 'Vencida' : "En {$days} días",
            ]);
        }

        // Stock bajo en inventario
        $stock = Inventory::whereColumn('stock', '<=', 'min_stock')->get();

        foreach ($stock as $item) {
            $notifications->push([
                'type'    => 'inventario',
                'color'   => 'red',
                'icon'    => 'triangle-exclamation',
                'title'   => 'Stock bajo',
                'message' => "{$item->name} tiene solo {$item->stock} {$item->unit}(s) disponibles",
                'link'    => route('inventory.show', $item->id),
                'time'    => 'Ahora',
            ]);
        }

        // Productos vencidos o por vencer en 30 días
        $expiring = Inventory::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->get();

        foreach ($expiring as $item) {
            $days = now()->diffInDays($item->expiry_date, false);
            $isExpired = $days < 0;

            $notifications->push([
                'type'    => 'vencimiento',
                'color'   => $isExpired ? 'red' : 'orange',
                'icon'    => 'box-open',
                'title'   => $isExpired ? 'Producto vencido' : 'Producto por vencer',
                'message' => "{$item->name} " .
                             ($isExpired ? "venció hace " . abs($days) . " día(s)" : "vence en {$days} día(s)"),
                'link'    => route('inventory.show', $item->id),
                'time'    => $isExpired ? 'Vencido' : "En {$days} días",
            ]);
        }

        return $notifications;
    }
}
