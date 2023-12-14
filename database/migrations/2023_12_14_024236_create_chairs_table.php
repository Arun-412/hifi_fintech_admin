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
        Schema::create('chairs', function (Blueprint $table) {
            $table->id();
            $table->text('room_code');
            $table->text('counter_code');
            $table->text('chair_code')->unique();
            $table->text('chair_from');
            $table->text('chair_to');
            $table->text('chair_api_charge');
            $table->text('chair_company_base_charge');
            $table->text('chair_user_charge_type');
            $table->text('chair_user_charge');
            $table->text('chair_mode');
            $table->text('chair_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairs');
    }
};
