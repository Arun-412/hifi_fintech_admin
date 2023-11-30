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
        Schema::create('doors', function (Blueprint $table) {
            $table->id();
            $table->string('door_code')->unique()->nullable();
            $table->string('shop_name');
            $table->string('mobile_number')->unique();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('mobile_otp')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_otp')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('kyc_status');
            $table->string('door_mode');
            $table->string('door_opened_by');
            $table->string('door_status');
            $table->string('door_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
