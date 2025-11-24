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
        Schema::table('video_library_items', function (Blueprint $table) {
            $table->boolean('is_standalone')->default(false)->after('access_priority');
            $table->decimal('standalone_price', 10, 2)->nullable()->after('is_standalone');
            $table->decimal('standalone_rental_price', 10, 2)->nullable()->after('standalone_price');
            $table->unsignedSmallInteger('standalone_rental_hours')->nullable()->default(72)->after('standalone_rental_price');
            $table->string('standalone_category')->nullable()->after('standalone_rental_hours');
        });

        Schema::create('video_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_library_item_id')->constrained()->cascadeOnDelete();
            $table->string('access_type', 20); // buy or rent
            $table->integer('amount'); // stored in cents
            $table->string('currency', 10)->default('usd');
            $table->string('status', 30)->default('pending');
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('rental_expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'video_library_item_id']);
            $table->index(['status', 'access_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_purchases');

        Schema::table('video_library_items', function (Blueprint $table) {
            $table->dropColumn([
                'is_standalone',
                'standalone_price',
                'standalone_rental_price',
                'standalone_rental_hours',
                'standalone_category',
            ]);
        });
    }
};
