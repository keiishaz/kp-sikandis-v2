<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_kendaraans', function (Blueprint $table) {
            $table->unsignedBigInteger('scan_count')->default(0)->after('token');
        });
    }

    public function down(): void
    {
        Schema::table('qr_kendaraans', function (Blueprint $table) {
            $table->dropColumn('scan_count');
        });
    }
};
