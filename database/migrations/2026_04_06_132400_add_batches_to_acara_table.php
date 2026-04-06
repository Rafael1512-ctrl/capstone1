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
            $table->string('batch1_name')->nullable();
            $table->dateTime('batch1_start_at')->nullable();
            $table->integer('batch1_quota')->default(0);
            $table->decimal('batch1_price', 15, 2)->default(0);
            $table->integer('batch1_sold')->default(0);

            $table->string('batch2_name')->nullable();
            $table->dateTime('batch2_start_at')->nullable();
            $table->integer('batch2_quota')->default(0);
            $table->decimal('batch2_price', 15, 2)->default(0);
            $table->integer('batch2_sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn([
                'batch1_name', 'batch1_start_at', 'batch1_quota', 'batch1_price', 'batch1_sold',
                'batch2_name', 'batch2_start_at', 'batch2_quota', 'batch2_price', 'batch2_sold'
            ]);
        });
    }
};
