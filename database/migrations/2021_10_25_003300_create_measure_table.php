<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasureTable extends Migration
{
  public function up(): void
  {
    Schema::create('measure', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('code', 3);
      $table->string('text_code', 20);
      $table->string('name', 50);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('measure');
  }
}
