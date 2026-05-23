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
    Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->enum('type', ['rumah','apartemen','tanah','ruko','villa']);
        $table->enum('status', ['dijual','disewakan','terikat_kontrak','sold_out'])->default('dijual');
        $table->boolean('is_approved')->default(false);
        $table->boolean('is_featured')->default(false);
        $table->decimal('price', 15, 2);
        $table->decimal('price_monthly', 15, 2)->nullable();
        $table->decimal('price_yearly', 15, 2)->nullable();
        $table->decimal('deposit', 15, 2)->nullable();
        $table->string('city');
        $table->string('province')->nullable();
        $table->text('address');
        $table->unsignedTinyInteger('bedrooms')->default(0);
        $table->unsignedTinyInteger('bathrooms')->default(0);
        $table->unsignedSmallInteger('land_area')->nullable();
        $table->unsignedSmallInteger('building_area')->nullable();
        $table->unsignedSmallInteger('garages')->default(0);
        $table->unsignedSmallInteger('electricity')->nullable();
        $table->string('certificate')->nullable();
        $table->string('virtual_tour_url')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
