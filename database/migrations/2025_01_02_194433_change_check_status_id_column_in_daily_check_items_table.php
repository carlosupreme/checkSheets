<?php

use App\Models\CheckStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('daily_check_items', function (Blueprint $table) {
            $table->foreignIdFor(CheckStatus::class)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('daily_check_items', function (Blueprint $table) {
            $table->foreignIdFor(CheckStatus::class)->change()->constrained();
        });
    }
};
