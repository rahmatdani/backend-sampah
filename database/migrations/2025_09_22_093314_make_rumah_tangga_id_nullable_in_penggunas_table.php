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
            // Drop foreign key constraint dulu
            $table->dropForeign(['rumah_tangga_id']);
            
            // Ubah field menjadi nullable
            $table->unsignedBigInteger('rumah_tangga_id')->nullable()->change();
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('rumah_tangga_id')->references('id')->on('rumah_tanggas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['rumah_tangga_id']);
            
            // Ubah field menjadi tidak nullable
            $table->unsignedBigInteger('rumah_tangga_id')->nullable(false)->change();
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('rumah_tangga_id')->references('id')->on('rumah_tanggas')->onDelete('cascade');
        });
    }
};
