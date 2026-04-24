<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_schedules', function (Blueprint $table) {
            $table->timestamp('expected_end_at')->nullable()->after('started_at');
            $table->index(['status', 'expected_end_at']);
        });
    }

    public function down(): void
    {
        Schema::table('production_schedules', function (Blueprint $table) {
            $table->dropIndex(['status', 'expected_end_at']);
            $table->dropColumn('expected_end_at');
        });
    }
};
