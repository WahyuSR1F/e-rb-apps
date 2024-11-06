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
        Schema::create('target_anggarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rencana_aksi_id')->nullable();
            $table->foreign('rencana_aksi_id')->references('id')->on('rencana_aksis');
            $table->foreignUuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('twI')->nullable()->comment('target anggran');
            $table->bigInteger('twII')->nullable()->comment('target anggran');
            $table->bigInteger('twIII')->nullable()->comment('target anggran');
            $table->bigInteger('twIV')->nullable()->comment('target anggran');
            $table->bigInteger('jumlah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_angarans');
    }
};
