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
        Schema::table('user_service_credentials', function (Blueprint $table) {
            $table->softDeletes(); // Ajoute la colonne deleted_at pour soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_service_credentials', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Supprime la colonne deleted_at
        });
    }

};
