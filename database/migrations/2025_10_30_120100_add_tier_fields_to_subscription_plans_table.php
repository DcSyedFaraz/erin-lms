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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('tier_key')->nullable()->after('is_trial');
            $table->unsignedTinyInteger('tier_priority')->default(0)->after('tier_key');
            $table->string('access_summary')->nullable()->after('tier_priority');
            $table->string('content_updates_summary')->nullable()->after('access_summary');
            $table->string('purpose_summary')->nullable()->after('content_updates_summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn([
                'tier_key',
                'tier_priority',
                'access_summary',
                'content_updates_summary',
                'purpose_summary',
            ]);
        });
    }
};
