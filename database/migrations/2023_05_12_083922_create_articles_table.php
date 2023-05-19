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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->text('url')->nullable();
            $table->text('url_to_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('source')->nullable();
            $table->text('author')->nullable();
            $table->text('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
