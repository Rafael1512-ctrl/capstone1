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
            $table->string('organizer_id', 7)->nullable()->comment('FK to users table');
            $table->bigInteger('category_id')->nullable()->comment('FK to kategori_acara table');
            $table->string('banner_url')->nullable()->comment('URL path to banner image');
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft')->comment('Event status');

            // Add foreign keys
            $table->foreign('organizer_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('category_id')->on('kategori_acara')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acara', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['organizer_id', 'category_id', 'banner_url', 'status']);
        });
    }
};
