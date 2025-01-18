<?php

use App\Models\CheckStatus;
use App\Models\DailyCheck;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('daily_check_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DailyCheck::class)->constrained()->cascadeOnDelete();
            $table->json("item");
            $table->text('notes')->nullable();
            $table->foreignIdFor(CheckStatus::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('daily_check_items');
    }
};
