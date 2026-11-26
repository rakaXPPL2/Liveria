<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bom_items')) {
            Schema::create('bom_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('design_id')->constrained('designs')->cascadeOnDelete();
                $table->string('part_name');
                $table->integer('quantity')->default(1);
                $table->string('spec_note')->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('bom_items', function (Blueprint $table) {
            if (! Schema::hasColumn('bom_items', 'design_id')) {
                $table->unsignedBigInteger('design_id')->nullable();
            }
            if (! Schema::hasColumn('bom_items', 'part_name')) {
                $table->string('part_name')->nullable();
            }
            if (! Schema::hasColumn('bom_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            if (! Schema::hasColumn('bom_items', 'spec_note')) {
                $table->string('spec_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Preserve existing data when this compatibility migration is rolled back.
    }
};
