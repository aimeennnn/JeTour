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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('regionCode')->nullable();
            $table->string('description');
            $table->string('image');
            $table->longText('multiple_images')->nullable();
            $table->string('content');
            $table->string('fasility');
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->decimal('price')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
