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
        Schema::create('kendaraan_pemegangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')
              ->constrained('kendaraans')
              ->cascadeOnDelete();
            $table->enum('source_system', ['API', 'Manual']);  
            $table->string('nip', 18)->nullable();  
            $table->foreignId('pegawai_id')
              ->nullable()
              ->constrained('pegawais')
              ->cascadeOnDelete();
            
            $table->string('nomor_sk');
            $table->date('tanggal_sk');
            $table->boolean('is_active')->default(true);
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan_pemegangs');
    }
};
