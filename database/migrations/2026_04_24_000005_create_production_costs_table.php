<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_schedule_id')->constrained('production_schedules')->cascadeOnDelete();

            $table->decimal('material_cost', 12, 2)->default(0);
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('cost_per_unit', 12, 4)->default(0);

            $table->foreignId('computed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('computed_at')->nullable();

            $table->timestamps();

            $table->unique('production_schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_costs');
    }
};
