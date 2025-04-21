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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image');
            $table->foreignUuid('category_id')->constrained()->onDelete('cascade');
            $table->string('author');
            $table->string('language')->default('English');
            $table->boolean('featured')->default(false);
            $table->integer('rating')->default(0);
            $table->timestamps();

            $table->index(['featured', 'rating']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
