<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = [
            ['pet_id' => 1, 'owner_id' => 1, 'user_id' => 2, 'scheduled_at' => now()->setTime(8, 0), 'status' => 'pendiente', 'reason' => 'Vacunación anual'],
            ['pet_id' => 2, 'owner_id' => 1, 'user_id' => 2, 'scheduled_at' => now()->setTime(9, 0), 'status' => 'confirmada', 'reason' => 'Control de peso'],
            ['pet_id' => 3, 'owner_id' => 2, 'user_id' => 2, 'scheduled_at' => now()->setTime(10, 0), 'status' => 'pendiente', 'reason' => 'Revisión general'],
            ['pet_id' => 4, 'owner_id' => 3, 'user_id' => 2, 'scheduled_at' => now()->addDay()->setTime(8, 30), 'status' => 'pendiente', 'reason' => 'Desparasitación'],
            ['pet_id' => 5, 'owner_id' => 4, 'user_id' => 2, 'scheduled_at' => now()->addDay()->setTime(11, 0), 'status' => 'confirmada', 'reason' => 'Odontología'],
            ['pet_id' => 6, 'owner_id' => 5, 'user_id' => 2, 'scheduled_at' => now()->subDay()->setTime(9, 0), 'status' => 'completada', 'reason' => 'Esterilización'],
            ['pet_id' => 7, 'owner_id' => 6, 'user_id' => 2, 'scheduled_at' => now()->subDays(2)->setTime(14, 0), 'status' => 'completada', 'reason' => 'Vacunación'],
            ['pet_id' => 8, 'owner_id' => 7, 'user_id' => 2, 'scheduled_at' => now()->subDays(3)->setTime(10, 0), 'status' => 'cancelada', 'reason' => 'Consulta general'],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}