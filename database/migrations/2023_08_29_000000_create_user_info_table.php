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
    Schema::create('user_info', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id')->unique();
      $table->string('full_name', 100);
      $table->tinyInteger('gender')->default(0);
      $table->date('birthday')->nullable();
      $table->string('about', 8000)->nullable();

      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

      $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_info');
  }
};
