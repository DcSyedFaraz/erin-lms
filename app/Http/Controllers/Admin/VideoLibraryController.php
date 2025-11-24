<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\VideoLibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VideoLibraryController extends Controller
{
    public function index()
    {
        $items = VideoLibraryItem::orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->paginate(20);

        return view('admin.video-library.index', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.video-library.create', [
            'contentTypes' => VideoLibraryItem::CONTENT_TYPES,
            'tiers' => SubscriptionPlan::TIER_LEVELS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        $data['media_path'] = $this->handleMediaUpload($request);
        $data['thumbnail_path'] = $this->handleThumbnailUpload($request);
        $data['uploaded_by'] = $request->user()->id;
        $data['published_at'] = $data['is_published']
            ? ($data['published_at'] ?? now())
            : null;

        $item = VideoLibraryItem::create($data);

        return redirect()
            ->route('admin.video-library.index')
            ->with('success', "{$item->title} added to the video library.");
    }

    public function edit(VideoLibraryItem $video_library)
    {
        return view('admin.video-library.edit', [
            'item' => $video_library,
            'contentTypes' => VideoLibraryItem::CONTENT_TYPES,
            'tiers' => SubscriptionPlan::TIER_LEVELS,
        ]);
    }

    public function update(Request $request, VideoLibraryItem $video_library)
    {
        $data = $this->validatePayload($request, $video_library->id);
        $mediaPath = $this->handleMediaUpload($request, $video_library);
        $thumbPath = $this->handleThumbnailUpload($request, $video_library);

        if ($mediaPath) {
            $data['media_path'] = $mediaPath;
        }

        if ($thumbPath) {
            $data['thumbnail_path'] = $thumbPath;
        }

        $data['published_at'] = $data['is_published']
            ? ($data['published_at'] ?? $video_library->published_at ?? now())
            : null;

        $video_library->update($data);

        return redirect()
            ->route('admin.video-library.index')
            ->with('success', "{$video_library->title} updated.");
    }

    public function destroy(VideoLibraryItem $video_library)
    {
        $title = $video_library->title;
        if ($video_library->media_path) {
            Storage::disk('public')->delete($video_library->media_path);
        }
        if ($video_library->thumbnail_path) {
            Storage::disk('public')->delete($video_library->thumbnail_path);
        }

        $video_library->delete();

        return redirect()
            ->route('admin.video-library.index')
            ->with('success', "{$title} removed from the library.");
    }

    protected function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        $tierKeys = array_keys(SubscriptionPlan::TIER_LEVELS);
        $contentKeys = array_keys(VideoLibraryItem::CONTENT_TYPES);

        $rules = [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('video_library_items', 'slug')->ignore($ignoreId),
            ],
            'content_type' => ['required', Rule::in($contentKeys)],
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'media' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,application/pdf,audio/mpeg|max:512000',
            'thumbnail' => 'nullable|image|max:3072',
            'external_url' => 'nullable|url',
            'access_tier' => ['required', Rule::in($tierKeys)],
            'is_featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'is_standalone' => 'nullable|boolean',
            'standalone_price' => 'nullable|numeric|min:1',
            'standalone_rental_price' => 'nullable|numeric|min:1',
            'standalone_rental_hours' => 'nullable|integer|min:24|max:168',
            'standalone_category' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        $isVideo = in_array($validated['content_type'], ['curated_video', 'short_film', 'short_clip'], true);
        if ($isVideo && !$request->hasFile('media') && empty($validated['external_url']) && !$ignoreId) {
            $request->validate([
                'media' => 'required_without:external_url',
                'external_url' => 'required_without:media|nullable|url',
            ]);
        }

        if ($validated['content_type'] === 'poem') {
            $request->validate([
                'body' => 'required|string',
            ]);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_standalone'] = $request->boolean('is_standalone');
        $validated['published_at'] = $request->filled('published_at')
            ? Carbon::parse($request->input('published_at'))
            : null;

        if ($validated['is_standalone']) {
            if ($request->missing('standalone_price') && $request->missing('standalone_rental_price')) {
                $request->validate([
                    'standalone_price' => 'required_without:standalone_rental_price|numeric|min:1',
                    'standalone_rental_price' => 'required_without:standalone_price|numeric|min:1',
                ]);
            }

            $validated['standalone_rental_hours'] = $request->integer('standalone_rental_hours') ?: 72;
        } else {
            $validated['standalone_price'] = null;
            $validated['standalone_rental_price'] = null;
            $validated['standalone_rental_hours'] = null;
            $validated['standalone_category'] = null;
        }

        return $validated;
    }

    protected function handleMediaUpload(Request $request, ?VideoLibraryItem $existing = null): ?string
    {
        if (!$request->hasFile('media')) {
            return null;
        }

        if ($existing?->media_path) {
            Storage::disk('public')->delete($existing->media_path);
        }

        return $request->file('media')->store('video-library/media', 'public');
    }

    protected function handleThumbnailUpload(Request $request, ?VideoLibraryItem $existing = null): ?string
    {
        if (!$request->hasFile('thumbnail')) {
            return null;
        }

        if ($existing?->thumbnail_path) {
            Storage::disk('public')->delete($existing->thumbnail_path);
        }

        return $request->file('thumbnail')->store('video-library/thumbnails', 'public');
    }
}
