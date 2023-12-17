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
        Schema::create('eko_payout_charges', function (Blueprint $table) {
            $table->id();
            $table->text('counter_code');
            $table->text('from_amount')->unique();
            $table->text('to_amount')->unique();
            $table->text('room_charge');
            $table->text('charge_type');
            $table->text('charge');
            $table->text('charge_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eko_payout_charges');
    }
};
