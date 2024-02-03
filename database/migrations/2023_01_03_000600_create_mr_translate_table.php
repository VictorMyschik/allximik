<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMrTranslateTable extends Migration
{
  public function up(): void
  {
    Schema::create('translate', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->unsignedSmallInteger('language_id');
      $table->string('translate');
      $table->timestamp('created_at')->useCurrent();

      $table->foreign('language_id')->references('id')->on('language')->cascadeOnDelete();
      $table->unique(['code', 'language_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('translate');
  }
}
