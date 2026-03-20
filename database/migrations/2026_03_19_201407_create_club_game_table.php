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
        Schema::create('club_game', function (Blueprint $table) {
            $table->foreignUuid('club_id');
            $table->foreignUuid('game_id')->constrained();
            $table->timestamps();

            $table->primary(['club_id', 'game_id']);
        });
    }
};
