<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();

            $table->enum('movement_type', ['in', 'out']);
            $table->decimal('quantity', 10, 4);
            $table->string('unit', 20)->default('pcs');

            $table->string('reference_type', 30)->nullable();
            $table->foreignId('production_schedule_id')->nullable()->constrained('production_schedules')->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();

            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('remarks', 255)->nullable();

            $table->timestamps();

            $table->index(['material_id', 'movement_type']);
            $table->index(['production_schedule_id']);
            $table->index(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_movements');
    }
};
