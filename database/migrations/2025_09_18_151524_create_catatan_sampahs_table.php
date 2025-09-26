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
        Schema::create('catatan_sampahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('rumah_tangga_id')->constrained('rumah_tanggas')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->string('jenis_terdeteksi')->nullable();
            $table->string('jenis_manual')->nullable();
            $table->decimal('volume_terdeteksi_liter', 8, 2)->nullable();
            $table->decimal('volume_manual_liter', 8, 2)->nullable();
            $table->decimal('volume_final_liter', 8, 2)->nullable();
            $table->decimal('berat_kg', 8, 2)->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamp('waktu_setoran')->nullable();
            $table->boolean('is_divalidasi')->default(false);
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            $table->integer('points_diberikan')->default(0);
            $table->timestamps();
            
            // Menambahkan foreign key untuk divalidasi_oleh
            $table->foreign('divalidasi_oleh')->references('id')->on('penggunas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_sampahs');
    }
};
