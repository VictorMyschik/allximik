<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyTable extends Migration
{
  public function up(): void
  {
    Schema::create('currency', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('code', 3);
      $table->string('text_code', 3);
      $table->string('name', 200);
      $table->tinyInteger('rounding');
      $table->string('description')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('currency');
  }
}
