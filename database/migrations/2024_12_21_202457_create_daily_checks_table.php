<?php

use App\Models\CheckSheet;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('daily_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CheckSheet::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(User::class, 'operator_id')->constrained();
            $table->string('operator_name');
            $table->timestamp('checked_at');
            $table->longText('operator_signature');
            $table->longText('supervisor_signature')->nullable();
            $table->longText('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('daily_checks');
    }
};
