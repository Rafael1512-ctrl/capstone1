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
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name'); // VIP, Regular, Student, etc.
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('total_tickets');
            $table->integer('available_tickets');
            $table->integer('sold_tickets')->default(0);
            $table->integer('queue_count')->default(0); // waiting list count
            $table->enum('status', ['active', 'inactive', 'sold_out'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
