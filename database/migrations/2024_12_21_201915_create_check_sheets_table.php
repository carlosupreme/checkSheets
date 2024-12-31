<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('check_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_area');
            $table->string('name')->unique()->index();
            $table->string('equipment_tag')->unique()->index();
            $table->string('equipment_name')->index();
            $table->longText('notes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignIdFor(User::class, 'created_by')->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('check_sheets');
    }
};
