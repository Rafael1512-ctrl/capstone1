<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->integer('batch1_regular_waiting_quota')->default(0)->after('batch1_regular_sold');
            $table->integer('batch1_vip_waiting_quota')->default(0)->after('batch1_vip_sold');
            $table->integer('batch1_vvip_waiting_quota')->default(0)->after('batch1_vvip_sold');
        });

        Schema::table('ticket_type', function (Blueprint $table) {
            $table->integer('waiting_list_quota')->default(0)->after('quantity_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn(['batch1_regular_waiting_quota', 'batch1_vip_waiting_quota', 'batch1_vvip_waiting_quota']);
        });

        Schema::table('ticket_type', function (Blueprint $table) {
            $table->dropColumn('waiting_list_quota');
        });
    }
};
