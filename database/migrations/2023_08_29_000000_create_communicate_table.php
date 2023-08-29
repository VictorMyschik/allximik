<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('communicate', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->unsignedBigInteger('user_id');
      $table->tinyInteger('kind')->default(0);// Тип: телефон, email, url...
      $table->string('address');
      $table->string('description', 8000)->nullable();

      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

      $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('communicate');
  }
};
