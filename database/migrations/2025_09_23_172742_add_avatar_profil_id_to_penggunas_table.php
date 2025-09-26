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
            $table->unsignedBigInteger('avatar_profil_id')->nullable()->after('kecamatan_id');
            $table->foreign('avatar_profil_id')->references('id')->on('avatar_profil')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->dropForeign(['avatar_profil_id']);
            $table->dropColumn('avatar_profil_id');
        });
    }
};
