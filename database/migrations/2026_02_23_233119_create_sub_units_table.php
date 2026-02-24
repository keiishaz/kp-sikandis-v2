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
        Schema::create('sub_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();
            $table->string('nama_sub_unit', 150);
            $table->unique(['unit_id', 'nama_sub_unit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_units');
    }
};
