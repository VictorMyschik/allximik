<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function down(): void
    {
        Schema::dropIfExists('cron');
    }

    public function up(): void
    {
        Schema::create('cron', function (Blueprint $table): void {
            $table->id();

            $table->string('name', 50);
            $table->boolean('active')->default(false);
            $table->unsignedBigInteger('period');
            $table->timestamp('last_work')->nullable();
            $table->string('description')->nullable();
            $table->string('cron_key', 50)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }
};
