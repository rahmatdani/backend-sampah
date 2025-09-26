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
            // Drop foreign key constraint first
            $table->dropForeign(['divalidasi_oleh']);
            
            // Drop the unnecessary columns
            $table->dropColumn(['jenis_manual', 'volume_manual_liter', 'volume_final_liter', 'divalidasi_oleh']);
            
            // Change the column name from is_divalidasi to is_validasi and set default to 0
            // First add the new column
            $table->boolean('is_validasi')->default(false)->after('foto_path');
        });
        
        // Copy data from old column to new column
        \DB::statement('UPDATE catatan_sampahs SET is_validasi = is_divalidasi');
        
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            // Then drop the old column
            $table->dropColumn('is_divalidasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->string('jenis_manual')->nullable();
            $table->decimal('volume_manual_liter', 8, 2)->nullable();
            $table->decimal('volume_final_liter', 8, 2)->nullable();
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            
            // Revert the column changes
            $table->boolean('is_divalidasi')->default(false);
            $table->dropColumn('is_validasi');
            
            // Restore the foreign key
            $table->foreign('divalidasi_oleh')->references('id')->on('penggunas')->onDelete('set null');
        });
    }
};
