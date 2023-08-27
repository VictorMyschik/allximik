<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('hike', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('name');
      $table->string('description', 8000)->nullable();
      $table->tinyInteger('status');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('country_id');
      $table->unsignedBigInteger('hike_type_id');
      $table->string('public_id', 15)->nullable();
      $table->tinyInteger('public')->default(0);

      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
      $table->timestamp('deleted_at')->nullable();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
      $table->foreign('country_id')->references('id')->on('country')->onDelete('set null');
      $table->foreign('hike_type_id')->references('id')->on('hike_type')->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('hike');
  }
};
