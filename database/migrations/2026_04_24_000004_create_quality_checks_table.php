<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_schedule_id')->constrained('production_schedules')->cascadeOnDelete();
            $table->foreignId('inspected_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('result', ['passed', 'failed']);
            $table->unsignedInteger('qty_passed')->default(0);
            $table->unsignedInteger('qty_failed')->default(0);
            $table->string('remarks', 255)->nullable();

            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();

            $table->index(['production_schedule_id', 'result']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
