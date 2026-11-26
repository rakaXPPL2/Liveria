<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('code_id')->unique(); // Contoh: CAD-01 / BLD-01
            $table->string('title');
            $table->enum('workflow_type', ['solidworks', 'blender', 'hybrid']);
            $table->string('material')->nullable();     // Atribut SolidWorks
            $table->decimal('mass_grams', 8, 2)->nullable();
            $table->string('poly_count')->nullable();   // Atribut Blender
            $table->string('render_image')->nullable();
            $table->string('glb_file')->nullable();     // File 3D interaktif
            $table->timestamps();
        });

        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained('designs')->onDelete('cascade');
            $table->string('part_name');
            $table->integer('quantity')->default(1);
            $table->string('spec_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('designs');
    }
};