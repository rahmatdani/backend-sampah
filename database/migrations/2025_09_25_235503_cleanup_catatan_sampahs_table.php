<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Coba hapus foreign key constraint jika ada
        $this->dropForeignKeyIfExists('catatan_sampahs', 'divalidasi_oleh');
        $this->dropForeignKeyIfExists('catatan_sampahs', 'jenis_sampah_id');
        
        // Setelah itu, hapus kolom-kolom yang tidak diperlukan
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $columnsToRemove = [
                'jenis_manual', 
                'volume_manual_liter', 
                'volume_final_liter', 
                'divalidasi_oleh',
                'is_validasi', // Kolom ini dari migration sebelumnya, hapus jika ada
                'jenis_sampah_id' // Juga hapus jenis_sampah_id
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('catatan_sampahs', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Pastikan kolom is_divalidasi ada
            if (!Schema::hasColumn('catatan_sampahs', 'is_divalidasi')) {
                $table->boolean('is_divalidasi')->default(0)->after('foto_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            // Kembalikan kolom-kolom yang dihapus (untuk rollback)
            $table->unsignedBigInteger('jenis_sampah_id')->nullable();
            $table->string('jenis_manual')->nullable();
            $table->decimal('volume_manual_liter', 8, 2)->nullable();
            $table->decimal('volume_final_liter', 8, 2)->nullable();
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            $table->boolean('is_validasi')->default(0); // Kembalikan juga kolom yang diganti
            
            // Hapus kolom is_divalidasi jika rollback
            if (Schema::hasColumn('catatan_sampahs', 'is_divalidasi')) {
                $table->dropColumn('is_divalidasi');
            }
        });
    }
    
    /**
     * Drop foreign key if it exists.
     */
    private function dropForeignKeyIfExists($table, $column)
    {
        try {
            $foreignKeyName = "{$table}_{$column}_foreign";
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$foreignKeyName}`");
        } catch (\Exception $e) {
            // Jika foreign key tidak ada, abaikan error
        }
    }
};