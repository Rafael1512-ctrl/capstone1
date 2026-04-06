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
            // Remove old simplified columns
            $table->dropColumn(['batch1_quota', 'batch1_price', 'batch1_sold', 'batch1_name',
                                'batch2_quota', 'batch2_price', 'batch2_sold', 'batch2_name']);

            // Add new categorical columns
            $table->integer('batch1_regular_quota')->default(0);
            $table->decimal('batch1_regular_price', 15, 2)->default(0);
            $table->integer('batch1_regular_sold')->default(0);
            
            $table->integer('batch1_vip_quota')->default(0);
            $table->decimal('batch1_vip_price', 15, 2)->default(0);
            $table->integer('batch1_vip_sold')->default(0);
            
            $table->integer('batch1_vvip_quota')->default(0);
            $table->decimal('batch1_vvip_price', 15, 2)->default(0);
            $table->integer('batch1_vvip_sold')->default(0);

            $table->integer('batch2_regular_quota')->default(0);
            $table->decimal('batch2_regular_price', 15, 2)->default(0);
            $table->integer('batch2_regular_sold')->default(0);
            
            $table->integer('batch2_vip_quota')->default(0);
            $table->decimal('batch2_vip_price', 15, 2)->default(0);
            $table->integer('batch2_vip_sold')->default(0);
            
            $table->integer('batch2_vvip_quota')->default(0);
            $table->decimal('batch2_vvip_price', 15, 2)->default(0);
            $table->integer('batch2_vvip_sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn([
                'batch1_regular_quota', 'batch1_regular_price', 'batch1_regular_sold',
                'batch1_vip_quota', 'batch1_vip_price', 'batch1_vip_sold',
                'batch1_vvip_quota', 'batch1_vvip_price', 'batch1_vvip_sold',
                'batch2_regular_quota', 'batch2_regular_price', 'batch2_regular_sold',
                'batch2_vip_quota', 'batch2_vip_price', 'batch2_vip_sold',
                'batch2_vvip_quota', 'batch2_vvip_price', 'batch2_vvip_sold',
            ]);
            
            // Re-add old simplified columns if needed for rollback logic (simplified)
            $table->string('batch1_name')->nullable();
            $table->integer('batch1_quota')->default(0);
            $table->decimal('batch1_price', 15, 2)->default(0);
            $table->integer('batch1_sold')->default(0);
            $table->string('batch2_name')->nullable();
            $table->integer('batch2_quota')->default(0);
            $table->decimal('batch2_price', 15, 2)->default(0);
            $table->integer('batch2_sold')->default(0);
        });
    }
};
