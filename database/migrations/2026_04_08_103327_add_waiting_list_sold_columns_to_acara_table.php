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
            $table->integer('batch1_regular_waiting_sold')->default(0)->after('batch1_regular_waiting_quota');
            $table->integer('batch1_vip_waiting_sold')->default(0)->after('batch1_vip_waiting_quota');
            $table->integer('batch1_vvip_waiting_sold')->default(0)->after('batch1_vvip_waiting_quota');
        });

        Schema::table('ticket_type', function (Blueprint $table) {
            $table->integer('waiting_list_sold')->default(0)->after('waiting_list_quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn(['batch1_regular_waiting_sold', 'batch1_vip_waiting_sold', 'batch1_vvip_waiting_sold']);
        });

        Schema::table('ticket_type', function (Blueprint $table) {
            $table->dropColumn('waiting_list_sold');
        });
    }
};
