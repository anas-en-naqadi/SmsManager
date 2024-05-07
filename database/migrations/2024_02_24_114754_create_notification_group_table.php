<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\ServiceCredentialsModel;

return new class extends Migration
{
/**
* Run the migrations.
*/
public function up(): void
{
Schema::create('notification_group', function (Blueprint $table) {
$table->id();
$table->string('from');
$table->json('to'); // Change the column type to json
$table->text('message');
$table->foreignIdFor(User::class, 'user_id');
$table->foreignIdFor(ServiceCredentialsModel::class,'service_id');
$table->timestamps();
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::dropIfExists('notification_group');
}
};
