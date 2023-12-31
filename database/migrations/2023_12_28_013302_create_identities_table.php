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
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->string('kyc_code')->unique();
            $table->string('door_code')->unique();
            $table->text('name')->nullable();
            $table->text('date_of_birth')->nullable();
            $table->string('pan_number')->unique();
            $table->text('pan_response')->nullable();
            $table->string('aadhar_number')->unique()->nullable();
            $table->text('aadhar_response')->nullable();
            $table->text('address')->nullable();
            $table->text('documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identities');
    }
};
