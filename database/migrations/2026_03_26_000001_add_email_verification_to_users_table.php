<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom untuk email verification
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            
            // Tambah kolom untuk password reset token
            if (!Schema::hasColumn('users', 'password_reset_token')) {
                $table->string('password_reset_token')->nullable()->unique()->after('email_verified_at');
            }
            
            // Tambah kolom untuk expiry time dari reset token
            if (!Schema::hasColumn('users', 'password_reset_expires_at')) {
                $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_token');
            }
            
            // Tambah kolom untuk email verification token
            if (!Schema::hasColumn('users', 'email_verification_token')) {
                $table->string('email_verification_token')->nullable()->unique()->after('password_reset_expires_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'password_reset_token')) {
                $table->dropColumn('password_reset_token');
            }
            if (Schema::hasColumn('users', 'password_reset_expires_at')) {
                $table->dropColumn('password_reset_expires_at');
            }
            if (Schema::hasColumn('users', 'email_verification_token')) {
                $table->dropColumn('email_verification_token');
            }
        });
    }
};
