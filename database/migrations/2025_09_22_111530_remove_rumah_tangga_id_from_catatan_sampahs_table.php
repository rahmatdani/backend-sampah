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
            $table->dropColumn('rumah_tangga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->foreignId('rumah_tangga_id')->constrained('rumah_tanggas')->onDelete('cascade');
        });
    }
};
