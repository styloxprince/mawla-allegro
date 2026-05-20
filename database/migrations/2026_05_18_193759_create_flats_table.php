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
        Schema::create('flats', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('flat_no');

            $table->string('unit')->nullable();

            $table->integer('floor')->nullable();

            $table->decimal('size', 10, 2)->nullable();

            $table->decimal('price', 15, 2)->nullable();

            $table->enum('status', [
                'available',
                'booked',
                'sold',
                'cancelled'
            ])->default('available');

            $table->string('facing')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};