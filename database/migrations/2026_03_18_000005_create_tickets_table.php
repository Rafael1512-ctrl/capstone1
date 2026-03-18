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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('ticket_category_id')->constrained('ticket_categories')->cascadeOnDelete();
            $table->string('ticket_number')->unique();
            $table->string('qr_code')->nullable(); // QR code data/filename
            $table->string('qr_code_path')->nullable(); // file path
            $table->enum('status', ['active', 'used', 'cancelled', 'voided'])->default('active');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->string('validated_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
