<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\VideoLibraryItem;
use App\Models\VideoPurchase;
use Illuminate\Http\Request;

class VideoLibraryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tierKey = $user->subscriptionTierKey();
        $filter = $request->query('type');
        $scope = $request->query('scope');
        if (!in_array($scope, ['standalone', 'owned'], true)) {
            $scope = null;
        }

        $activePurchases = VideoPurchase::query()
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('rental_expires_at')
                    ->orWhere('rental_expires_at', '>', now());
            })
            ->orderByRaw("CASE WHEN access_type = 'rent' THEN 0 ELSE 1 END")
            ->orderBy('purchased_at')
            ->orderBy('id')
            ->get()
            ->keyBy('video_library_item_id');

        $ownedIds = $activePurchases->keys()->all();

        $query = VideoLibraryItem::published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at');

        if ($filter && array_key_exists($filter, VideoLibraryItem::CONTENT_TYPES)) {
            $query->where('content_type', $filter);
        }

        if ($scope === 'standalone') {
            $query->where('is_standalone', true);
        } elseif ($scope === 'owned') {
            $query->whereIn('id', $ownedIds ?: [-1]);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('video-library.index', [
            'items' => $items,
            'tierKey' => $tierKey,
            'tiers' => SubscriptionPlan::TIER_COPY,
            'contentTypes' => VideoLibraryItem::CONTENT_TYPES,
            'activeFilter' => $filter,
            'plan' => $user->currentSubscriptionPlan(),
            'activePurchases' => $activePurchases,
            'scope' => $scope,
            'ownedIds' => $ownedIds,
        ]);
    }

    public function show(Request $request, VideoLibraryItem $videoLibraryItem)
    {
        $user = $request->user();
        $planIncluded = $user->canAccessTier($videoLibraryItem->access_tier);
        $activePurchase = $videoLibraryItem->activePurchaseFor($user);

        return view('video-library.show', [
            'item' => $videoLibraryItem,
            'plan' => $user->currentSubscriptionPlan(),
            'planIncluded' => $planIncluded,
            'activePurchase' => $activePurchase,
            'canStream' => $planIncluded || (bool) $activePurchase,
        ]);
    }
}
