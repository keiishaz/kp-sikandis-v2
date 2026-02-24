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
        Schema::create('pemegangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->char('nip', 18)->unique();
            $table->string('jabatan', 100);
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('sub_unit_id')->constrained('sub_units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemegangs');
    }
};
