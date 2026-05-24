<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('expense_sheet_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('category');

            $table->string('description')->nullable();

            $table->decimal('amount', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
