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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kendaraan', 100);
            $table->char('no_polisi', 15)->unique();
            $table->year('tahun');
            $table->char('no_rangka', 17)->unique();
            $table->char('no_mesin', 30)->unique();
            $table->date('pajak')->index();
            $table->enum('jenis_penggunaan', ['jabatan', 'operasional'])->index();
            $table->string('lokasi_operasional', 100)->nullable();
            $table->string('warna', 50);
                
            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', ['aktif','nonaktif'])
                ->default('aktif')
                ->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
