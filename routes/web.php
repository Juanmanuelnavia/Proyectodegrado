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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Propietarios
    Route::resource('owners', OwnerController::class);

    // Mascotas
    Route::resource('pets', PetController::class);

    // Citas
    Route::resource('appointments', AppointmentController::class);

    // Historias clínicas
    Route::resource('medical-records', MedicalRecordController::class);

    // Inventario - solo admin y veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::resource('inventory', InventoryController::class);
    });
    Route::middleware(['role:admin,veterinario'])->group(function () {
    Route::resource('inventory', InventoryController::class);
    Route::post('inventory/{inventory}/movement', [InventoryController::class, 'addMovement'])->name('inventory.movement');
});
Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});
//auth
Route::get('calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
Route::get('calendar/events', [AppointmentController::class, 'events'])->name('appointments.events');
Route::get('medical-records/{medicalRecord}/pdf', [MedicalRecordController::class, 'pdf'])->name('medical-records.pdf');
Route::get('search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::patch('profile/password', [ProfileController::class, 'password'])->name('profile.password');
});



Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');


Route::get('templates', function() {
    return response()->json(\App\Models\ConsultationTemplate::all());
})->name('templates.index');
Route::get('medical-records/{medicalRecord}/formula', [MedicalRecordController::class, 'formula'])->name('medical-records.formula');
require __DIR__.'/auth.php';