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
            $table->string('no_polisi', 15)->unique();
            $table->smallInteger('tahun');
            $table->string('no_rangka', 50)->unique();
            $table->string('no_mesin', 50)->unique();
            $table->date('pajak');
            $table->enum('jenis_penggunaan', ['jabatan', 'operasional']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

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
