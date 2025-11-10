<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\VideoLibraryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class VideoLibraryAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_video_library_items(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $response = $this->actingAs($admin)->post(route('admin.video-library.store'), [
            'title' => 'Found Footage',
            'content_type' => 'curated_video',
            'description' => 'A curated clip for testing.',
            'media' => UploadedFile::fake()->create('clip.mp4', 1024, 'video/mp4'),
            'access_tier' => 'silver',
            'is_published' => true,
        ]);

        $response->assertRedirect(route('admin.video-library.index'));
        $this->assertDatabaseHas('video_library_items', [
            'title' => 'Found Footage',
            'access_tier' => 'silver',
            'is_published' => true,
        ]);

        Storage::disk('public')->assertExists(
            VideoLibraryItem::first()->media_path
        );
    }

    /** @test */
    public function lower_tier_subscriber_cannot_access_higher_tier_release(): void
    {
        $silverPlan = $this->makePlan('Silver Plan', 'price_silver', 'silver', 4.99);
        $goldenPlan = $this->makePlan('Golden Plan', 'price_golden', 'golden', 8.99);

        $silverUser = User::factory()->create();
        $this->attachSubscription($silverUser, $silverPlan);

        $item = VideoLibraryItem::create([
            'title' => 'Golden Short Film',
            'content_type' => 'short_film',
            'description' => 'Only golden and above.',
            'media_path' => 'video-library/media/test.mp4',
            'access_tier' => 'golden',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->actingAs($silverUser)
            ->get(route('video-library.index'))
            ->assertOk()
            ->assertDontSee($item->title);

        $this->actingAs($silverUser)
            ->get(route('video-library.show', $item))
            ->assertRedirect(route('video-library.index'));

        $goldenUser = User::factory()->create();
        $this->attachSubscription($goldenUser, $goldenPlan);

        $this->actingAs($goldenUser)
            ->get(route('video-library.show', $item))
            ->assertOk()
            ->assertSee($item->title);
    }

    protected function makePlan(string $name, string $stripePrice, string $tier, float $price): SubscriptionPlan
    {
        return SubscriptionPlan::create([
            'name' => $name,
            'stripe_price_id' => $stripePrice,
            'price' => $price,
            'interval' => 'month',
            'is_trial' => false,
            'tier_key' => $tier,
            'tier_priority' => SubscriptionPlan::tierPriority($tier),
            'access_summary' => SubscriptionPlan::tierMeta($tier)['access'],
            'content_updates_summary' => SubscriptionPlan::tierMeta($tier)['updates'],
            'purpose_summary' => SubscriptionPlan::tierMeta($tier)['purpose'],
        ]);
    }

    protected function attachSubscription(User $user, SubscriptionPlan $plan): void
    {
        $subscriptionId = DB::table('subscriptions')->insertGetId([
            'user_id' => $user->id,
            'type' => 'default',
            'stripe_id' => 'sub_' . uniqid(),
            'stripe_status' => 'active',
            'stripe_price' => $plan->stripe_price_id,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subscription_items')->insert([
            'subscription_id' => $subscriptionId,
            'stripe_id' => 'si_' . uniqid(),
            'stripe_product' => 'prod_' . uniqid(),
            'stripe_price' => $plan->stripe_price_id,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
