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
        Schema::table('penggunas', function (Blueprint $table) {
            $table->dropForeign('penggunas_rumah_tangga_id_foreign');
            $table->dropColumn('rumah_tangga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->foreignId('rumah_tangga_id')->nullable()->constrained('rumah_tanggas')->onDelete('cascade');
        });
    }
};
