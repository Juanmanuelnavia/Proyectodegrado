<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('owner_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('invoice_number')->unique();
        $table->enum('status', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
        $table->enum('payment_method', ['efectivo', 'tarjeta', 'transferencia'])->nullable();
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('tax', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->text('notes')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
