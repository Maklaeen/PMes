<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL enum alteration (keeps existing values and adds 'cancelled')
        DB::statement("ALTER TABLE work_orders MODIFY status ENUM('pending','ongoing','done','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Convert any cancelled rows back to pending before removing the enum value
        DB::statement("UPDATE work_orders SET status='pending' WHERE status='cancelled'");
        DB::statement("ALTER TABLE work_orders MODIFY status ENUM('pending','ongoing','done') NOT NULL DEFAULT 'pending'");
    }
};
