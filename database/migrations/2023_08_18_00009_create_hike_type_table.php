<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('travel_type', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('name');
      $table->string('description', 8000)->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('travel_type');
  }
};
