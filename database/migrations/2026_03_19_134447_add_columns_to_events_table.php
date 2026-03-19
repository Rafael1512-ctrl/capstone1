<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->after('organizer_id');
            $table->text('description')->nullable()->after('title');
            $table->dateTime('date')->after('description');
            $table->string('location')->after('date');
            $table->text('banner_url')->nullable()->after('location');
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft')->after('banner_url');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'date', 'location', 'banner_url', 'status']);
        });
    }
};