<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('faq', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('language_id')->index();
      $table->string('title')->unique();
      $table->string('text', 8000);
      $table->boolean('active')->default(false);
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('faq');
  }
};
