<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('global_category_stuff', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('name')->unique();
      $table->string('description', 8000)->nullable();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->timestamp('created_at')->useCurrent();
      // $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

      $table->foreign('parent_id')->references('id')->on('global_category_stuff')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('global_category_stuff');
  }
};
