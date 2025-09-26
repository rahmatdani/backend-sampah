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
        Schema::dropIfExists('rumah_tanggas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kita tidak perlu reverse migration karena ini adalah operasi penghapusan
    }
};
