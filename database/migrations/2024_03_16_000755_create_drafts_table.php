<?php

use App\Models\ServiceCredentialsModel;
use App\Models\User;
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
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->string('from')->nullable();
            $table->json('to')->nullable(); // Change the column type to json
            $table->text('body')->nullable();
            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignIdFor(ServiceCredentialsModel::class, 'service_id')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
