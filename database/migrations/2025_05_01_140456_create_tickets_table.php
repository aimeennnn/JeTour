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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name_ticket');
            $table->longText('rules');
            $table->double('price')->default(0);
            $table->longText('open')->nullable();
            $table->time('close')->nullable();
            $table->enum('type', ['Regular', 'Special']);
            $table->enum('status', ['Active', 'Expired']);
          //  $table->boolean('is_active')->default(true);
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
