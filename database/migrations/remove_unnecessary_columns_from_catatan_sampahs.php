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
            // Hapus kolom-kolom yang tidak diperlukan
            $columnsToRemove = [
                'jenis_sampah_id', 
                'jenis_manual', 
                'volume_manual_liter', 
                'volume_final_liter', 
                'divalidasi_oleh'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('catatan_sampahs', $column)) {
                    // Hapus foreign key constraint terlebih dahulu jika ada
                    try {
                        $table->dropForeign(["{$column}_foreign"]);
                    } catch (\Exception $e) {
                        // Jika foreign key tidak ada, lanjutkan
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            // Tambahkan kembali kolom-kolom yang dihapus (untuk rollback)
            $table->unsignedBigInteger('jenis_sampah_id')->nullable();
            $table->string('jenis_manual')->nullable();
            $table->decimal('volume_manual_liter', 8, 2)->nullable();
            $table->decimal('volume_final_liter', 8, 2)->nullable();
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            
            // Tambahkan foreign key kembali
            $table->foreign('jenis_sampah_id')->references('id')->on('jenis_sampahs');
            $table->foreign('divalidasi_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }
};