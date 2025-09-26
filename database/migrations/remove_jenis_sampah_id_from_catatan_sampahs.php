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
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            if (Schema::hasColumn('catatan_sampahs', 'jenis_sampah_id')) {
                $table->dropForeign(['jenis_sampah_id']); // Hapus foreign key constraint jika ada
                $table->dropColumn('jenis_sampah_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_sampah_id')->nullable();
            $table->foreign('jenis_sampah_id')->references('id')->on('jenis_sampahs');
        });
    }
};