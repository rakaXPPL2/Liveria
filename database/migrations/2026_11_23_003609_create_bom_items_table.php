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
        // The bom_items table is created by the preceding designs migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally empty; the preceding migration owns this table.
    }
};
