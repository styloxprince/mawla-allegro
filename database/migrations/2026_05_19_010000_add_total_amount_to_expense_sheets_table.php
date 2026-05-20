<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expense_sheets', function (Blueprint $table): void {
            $table->decimal('total_amount', 12, 2)->default(0)->after('expense_date');
        });

        DB::statement('
            UPDATE expense_sheets
            SET total_amount = (
                SELECT COALESCE(SUM(expense_items.amount), 0)
                FROM expense_items
                WHERE expense_items.expense_sheet_id = expense_sheets.id
            )
        ');
    }

    public function down(): void
    {
        Schema::table('expense_sheets', function (Blueprint $table): void {
            $table->dropColumn('total_amount');
        });
    }
};
