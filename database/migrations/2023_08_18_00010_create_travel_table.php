<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('travels', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('title');
      $table->string('description', 8000)->nullable();
      $table->tinyInteger('status');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('country_id');
      $table->unsignedBigInteger('travel_type_id');
      $table->string('public_id', 15)->nullable();
      $table->tinyInteger('visible_kind')->default(0);

      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
      $table->timestamp('deleted_at')->nullable();

      $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
      $table->foreign('country_id')->references('id')->on('country')->onDelete('set null');
      $table->foreign('travel_type_id')->references('id')->on('travel_type')->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('travels');
  }
};
