<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('travel_images', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('travel_id')->unique();
      $table->string('name', 50);
      $table->tinyInteger('kind')->default(0);
      $table->timestamp('created_at')->useCurrent();

      $table->foreign('travel_id')->references('id')->on('travel')->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('travel_images');
  }
};
