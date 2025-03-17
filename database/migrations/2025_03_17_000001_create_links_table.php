<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table): void {
            $table->id();
            $table->string('user');
            $table->string('type')->index(); // SiteType::class
            $table->string('path')->nullable();
            $table->string('query', 1000);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
