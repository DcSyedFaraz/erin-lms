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
        Schema::create('video_library_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('content_type');
            $table->text('description')->nullable();
            $table->longText('body')->nullable();
            $table->string('media_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('access_tier')->default('silver');
            $table->unsignedTinyInteger('access_priority')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_library_items');
    }
};
