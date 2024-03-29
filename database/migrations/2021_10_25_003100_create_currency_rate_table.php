<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyRateTable extends Migration
{
  public function up(): void
  {
    Schema::create('currency_rate', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->unsignedBigInteger('currency_id');
      $table->decimal('rate', 10, 4);

      $table->timestamp('created_at')->useCurrent();
      $table->foreign('currency_id')->references('id')->on('currency')->cascadeOnDelete();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('currency_rate');
  }
}
