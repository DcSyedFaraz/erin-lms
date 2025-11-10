<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\VideoLibraryItem;
use Illuminate\Http\Request;

class VideoLibraryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tierKey = $user->subscriptionTierKey();
        $filter = $request->query('type');

        $query = VideoLibraryItem::published()
            ->forTier($tierKey)
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at');

        if ($filter && array_key_exists($filter, VideoLibraryItem::CONTENT_TYPES)) {
            $query->where('content_type', $filter);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('video-library.index', [
            'items' => $items,
            'tierKey' => $tierKey,
            'tiers' => SubscriptionPlan::TIER_COPY,
            'contentTypes' => VideoLibraryItem::CONTENT_TYPES,
            'activeFilter' => $filter,
            'plan' => $user->currentSubscriptionPlan(),
        ]);
    }

    public function show(Request $request, VideoLibraryItem $videoLibraryItem)
    {
        $user = $request->user();
        if (!$user->canAccessTier($videoLibraryItem->access_tier)) {
            return redirect()
                ->route('video-library.index')
                ->with('error', 'Upgrade your plan to access this experience.');
        }

        return view('video-library.show', [
            'item' => $videoLibraryItem,
            'plan' => $user->currentSubscriptionPlan(),
        ]);
    }
}
