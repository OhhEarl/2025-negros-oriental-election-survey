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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('governor_id')->nullable();
            $table->unsignedBigInteger('vice_governor_id')->nullable();
            $table->string('device_cookie')->unique(); // ensure one vote per device
            $table->timestamps();

            $table->foreign('governor_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('vice_governor_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
