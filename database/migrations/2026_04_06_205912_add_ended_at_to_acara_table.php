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
            $table->dateTime('batch1_ended_at')->nullable()->after('batch1_start_at');
            $table->dateTime('batch2_ended_at')->nullable()->after('batch2_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn(['batch1_ended_at', 'batch2_ended_at']);
        });
    }
};
