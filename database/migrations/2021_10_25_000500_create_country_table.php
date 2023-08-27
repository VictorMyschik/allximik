<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
{
  public function up(): void
  {
    Schema::create('country', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('name', 50);
      $table->char('iso3166alpha2', 3);
      $table->char('iso3166alpha3', 4);
      $table->char('iso3166numeric', 3);
      $table->tinyInteger('continent');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('country');
  }
}
