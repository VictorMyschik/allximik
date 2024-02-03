<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMrLanguageTable extends Migration
{
  public function up(): void
  {
    Schema::create('language', function (Blueprint $table) {
      $table->id();
      $table->string('code', 2);
      $table->string('name', 50);
      $table->boolean('active')->default(0);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('language');
  }
}
