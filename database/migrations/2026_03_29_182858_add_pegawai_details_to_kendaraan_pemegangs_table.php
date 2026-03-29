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
        Schema::table('kendaraan_pemegangs', function (Blueprint $table) {
            $table->string('nama_pegawai', 150)->nullable()->after('nip');
            $table->string('jabatan_pegawai', 150)->nullable()->after('nama_pegawai');
            $table->string('unit_pegawai', 150)->nullable()->after('jabatan_pegawai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraan_pemegangs', function (Blueprint $table) {
            $table->dropColumn(['nama_pegawai', 'jabatan_pegawai', 'unit_pegawai']);
        });
    }
};
