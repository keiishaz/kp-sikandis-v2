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
        Schema::create('kendaraan_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')
              ->constrained('kendaraans')
              ->cascadeOnDelete();

            $table->string('judul_aktivitas', 150);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_aktivitas');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan_aktivitas');
    }
};
