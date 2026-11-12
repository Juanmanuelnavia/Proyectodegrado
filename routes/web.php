<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SearchController;
use App\Models\ConsultationTemplate;

Route::get('/', function () {
    return redirect()->route('login');
});

// Todas las rutas del sistema requieren sesión iniciada.
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Propietarios
    Route::resource('owners', OwnerController::class);

    // Mascotas
    Route::resource('pets', PetController::class);

    // Citas y calendario
    Route::get('calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::get('calendar/events', [AppointmentController::class, 'events'])->name('appointments.events');
    Route::resource('appointments', AppointmentController::class);

    // Historias clínicas
    // Crear, editar y eliminar: solo administrador y veterinario.
    // Se declara antes que index/show para que /medical-records/create
    // no sea interpretado como /medical-records/{medicalRecord}.
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::resource('medical-records', MedicalRecordController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    });
    // Consultar y exportar: todos los roles autenticados (incluida la recepcionista).
    Route::resource('medical-records', MedicalRecordController::class)
        ->only(['index', 'show']);
    Route::get('medical-records/{medicalRecord}/pdf', [MedicalRecordController::class, 'pdf'])->name('medical-records.pdf');
    Route::get('medical-records/{medicalRecord}/formula', [MedicalRecordController::class, 'formula'])->name('medical-records.formula');

    // Plantillas de consulta (usadas por el formulario de historia clínica)
    Route::get('templates', function () {
        return response()->json(ConsultationTemplate::all());
    })->name('templates.index');

    // Inventario - solo admin y veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::resource('inventory', InventoryController::class);
        Route::post('inventory/{inventory}/movement', [InventoryController::class, 'addMovement'])->name('inventory.movement');
    });

    // Usuarios - solo admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Facturación y notificaciones
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('invoices', InvoiceController::class);

    // Buscador global
    Route::get('search', [SearchController::class, 'index'])->name('search');

    // Perfil
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [ProfileController::class, 'password'])->name('profile.password');
});

require __DIR__.'/auth.php';