<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('uih', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->unsignedBigInteger('hike_id');
      $table->unsignedBigInteger('user_id');
      $table->tinyInteger('status')->default(0);
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

      $table->foreign('hike_id')->references('id')->on('hike')->cascadeOnDelete();
      $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('uih');
  }
};
