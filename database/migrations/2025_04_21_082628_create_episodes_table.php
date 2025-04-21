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
        Schema::create('episodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('podcast_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('audio_url');
            $table->integer('duration_in_seconds');
            $table->date('published_at');
            $table->boolean('featured')->default(false);
            $table->string('guest_name')->nullable();
            $table->timestamps();

            $table->index('podcast_id');
            $table->index('published_at');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
