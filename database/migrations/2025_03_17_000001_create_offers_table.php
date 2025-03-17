<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table): void {
            $table->id();
            $table->string('offer_id');
            $table->unsignedBigInteger('link_id');
            $table->jsonb('sl');

            $table->foreign('link_id')->references('id')->on('links')->cascadeOnDelete();

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
