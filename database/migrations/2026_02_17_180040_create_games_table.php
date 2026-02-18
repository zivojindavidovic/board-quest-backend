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
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255)->index();
            $table->text('description')->nullable();
            $table->text('rules_summary')->nullable();
            $table->unsignedTinyInteger('min_players');
            $table->unsignedTinyInteger('max_players');
            $table->unsignedSmallInteger('play_time_minutes')->nullable();
            $table->decimal('complexity', 3, 2)->nullable();
            $table->unsignedSmallInteger('year_published')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->unsignedInteger('bgg_id')->nullable()->unique();
            $table->decimal('avg_rating', 4, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
