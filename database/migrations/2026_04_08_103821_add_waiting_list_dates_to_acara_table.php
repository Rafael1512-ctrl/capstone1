<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dateTime('batch1_waiting_start_at')->nullable()->after('batch1_vvip_waiting_sold');
            $table->dateTime('batch1_waiting_ended_at')->nullable()->after('batch1_waiting_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn(['batch1_waiting_start_at', 'batch1_waiting_ended_at']);
        });
    }
};
