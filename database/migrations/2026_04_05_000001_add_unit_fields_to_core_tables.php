<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tambah kolom ke tabel units ──────────────────────────────────
        Schema::table('units', function (Blueprint $table) {
            $table->enum('type', ['internal', 'external'])->default('external')->after('nama_unit');
            $table->string('api_mapping_key')->nullable()->after('type')
                  ->comment('Nama OPD persis seperti yang dikirim oleh API Karyawan, untuk validasi pencocokan.');
        });

        // ── 2. Tambah unit_id ke tabel kendaraans ───────────────────────────
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('kategori_id')
                  ->constrained('units')->nullOnDelete()
                  ->comment('Unit/OPD pemilik kendaraan ini. Nullable untuk data lama.');
        });

        // ── 3. Tambah unit_id ke tabel users ────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('role_id')
                  ->constrained('units')->nullOnDelete()
                  ->comment('Unit/OPD tempat operator ini terdaftar. Null untuk Admin.');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['type', 'api_mapping_key']);
        });
    }
};
