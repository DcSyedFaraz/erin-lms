<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('local_subscription_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('type'); // e.g., customer.subscription.updated, customer.subscription.deleted, manual.assigned
            $table->string('status')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['stripe_subscription_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_events');
    }
};

