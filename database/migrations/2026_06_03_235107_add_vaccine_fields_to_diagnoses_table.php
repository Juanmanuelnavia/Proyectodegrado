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
    Schema::table('diagnoses', function (Blueprint $table) {
        $table->date('vaccine_date')->nullable()->after('next_visit');
        $table->string('vaccine_name')->nullable()->after('vaccine_date');
        $table->date('deworming_date')->nullable()->after('vaccine_name');
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('diagnoses', function (Blueprint $table) {
        $table->dropColumn(['vaccine_date', 'vaccine_name', 'deworming_date']);
    });
}
};
