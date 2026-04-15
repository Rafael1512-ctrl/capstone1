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
        Schema::create('waiting_list', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 255); // Match Laravel default string length for acara event_id
            $table->integer('ticket_type_id'); // Legacy ticket_type id is int(11)
            $table->string('user_email');
            $table->boolean('notified')->default(false);
            $table->timestamps();
            
            // Foreign key constraints
            // Skipping foreign keys to avoid mismatch with the legacy tables
            $table->unique(['event_id', 'ticket_type_id', 'user_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_list');
    }
};
