<?php

use App\Services\ParsingService\Enum\SiteType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table): void {
            $table->id();
            $table->enum('type', array_keys(SiteType::getSelectList()));
            $table->string('offer_id')->index();
            $table->jsonb('sl');

            $table->unique(['offer_id', 'type']);
            $table->index(['offer_id', 'type']);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
