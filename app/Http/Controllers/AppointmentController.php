<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
   public function index(Request $request)
{
    $query = Appointment::with(['pet', 'owner', 'veterinarian']);

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->whereHas('pet', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('owner', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $query->whereDate('scheduled_at', $request->date);
    }

    $appointments = $query->latest()->paginate(10)->withQueryString();
    return view('appointments.index', compact('appointments'));
}

    public function create()
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $owners = Owner::orderBy('name')->get();
        $veterinarians = User::where('role', 'veterinario')->orderBy('name')->get();
        return view('appointments.create', compact('pets', 'owners', 'veterinarians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pet_id'       => 'required|exists:pets,id',
            'owner_id'     => 'required|exists:owners,id',
            'user_id'      => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'reason'       => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        if ($error = $this->scheduleConflict($request->user_id, $request->scheduled_at)) {
            return back()->withInput()->withErrors(['scheduled_at' => $error]);
        }

        Appointment::create($request->all());

        return redirect()->route('appointments.index')
            ->with('success', 'Cita agendada exitosamente.');
    }

    /**
     * Duración que se reserva para cada consulta (en minutos).
     * Dos citas del mismo veterinario no pueden quedar a menos de este tiempo.
     */
    private const SLOT_MINUTES = 30;

    /**
     * Devuelve un mensaje de error si el veterinario ya tiene otra cita activa
     * (no cancelada) dentro de la ventana de la consulta; si no, devuelve null.
     */
    private function scheduleConflict($vetId, $scheduledAt, $ignoreId = null): ?string
    {
        $start = \Carbon\Carbon::parse($scheduledAt);

        $conflict = Appointment::with('pet')
            ->where('user_id', $vetId)
            ->where('status', '!=', 'cancelada')
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('scheduled_at', '>', $start->copy()->subMinutes(self::SLOT_MINUTES))
            ->where('scheduled_at', '<', $start->copy()->addMinutes(self::SLOT_MINUTES))
            ->orderBy('scheduled_at')
            ->first();

        if (! $conflict) {
            return null;
        }

        $vet = User::find($vetId);

        return sprintf(
            '%s ya tiene una cita a las %s (%s). Cada consulta ocupa %d minutos; elige otra hora u otro veterinario.',
            $vet?->name ?? 'El veterinario',
            \Carbon\Carbon::parse($conflict->scheduled_at)->format('h:i A'),
            $conflict->pet?->name ?? 'otra mascota',
            self::SLOT_MINUTES
        );
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('pet', 'owner', 'veterinarian', 'medicalRecord');
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $owners = Owner::orderBy('name')->get();
        $veterinarians = User::where('role', 'veterinario')->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'pets', 'owners', 'veterinarians'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'pet_id'       => 'required|exists:pets,id',
            'owner_id'     => 'required|exists:owners,id',
            'user_id'      => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'status'       => 'required|in:pendiente,confirmada,completada,cancelada',
            'reason'       => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        // Una cita cancelada libera el horario, así que solo se valida si sigue activa.
        if ($request->status !== 'cancelada'
            && $error = $this->scheduleConflict($request->user_id, $request->scheduled_at, $appointment->id)) {
            return back()->withInput()->withErrors(['scheduled_at' => $error]);
        }

        $appointment->update($request->all());

        return redirect()->route('appointments.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'Cita eliminada exitosamente.');
    }
    public function calendar()
{
    $veterinarians = User::where('role', 'veterinario')->orderBy('name')->get();
    return view('appointments.calendar', compact('veterinarians'));
}

public function events(Request $request)
{
    $query = Appointment::with(['pet', 'owner', 'veterinarian']);

    if ($request->filled('vet')) {
        $query->where('user_id', $request->vet);
    }

    $appointments = $query->get()->map(function ($a) {
        $colors = [
            'pendiente'  => '#fbbf24',
            'confirmada' => '#3b82f6',
            'completada' => '#10b981',
            'cancelada'  => '#ef4444',
        ];
        return [
            'id'    => $a->id,
            'title' => $a->pet->name . ' — ' . $a->owner->name,
            'start' => $a->scheduled_at,
            'color' => $colors[$a->status] ?? '#6b7280',
            'extendedProps' => [
                'status'      => $a->status,
                'reason'      => $a->reason ?? 'Sin motivo',
                'vet'         => $a->veterinarian->name,
                'url'         => route('appointments.show', $a->id),
            ],
        ];
    });

    return response()->json($appointments);
}
}