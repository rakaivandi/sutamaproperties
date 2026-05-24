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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('property_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('period', ['monthly', 'yearly'])->default('monthly');
        $table->decimal('total_price', 15, 2);
        $table->decimal('deposit_paid', 15, 2)->default(0);
        $table->enum('status', [
            'pending',
            'confirmed',
            'active',
            'completed',
            'cancelled',
        ])->default('pending');
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
