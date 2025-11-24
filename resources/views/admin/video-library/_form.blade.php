@php
    $editing = isset($item);
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}" placeholder="auto-generated if empty">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Content Type</label>
                <select name="content_type" class="form-control" required>
                    @foreach($contentTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('content_type', $item->content_type ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Poems expect body text; video types expect a file or external URL.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Access Tier</label>
                <select name="access_tier" class="form-control" required>
                    @foreach($tiers as $key => $order)
                        <option value="{{ $key }}" {{ old('access_tier', $item->access_tier ?? '') === $key ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="One paragraph intro">{{ old('description', $item->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Body / Script (for poems or liner notes)</label>
            <textarea name="body" class="form-control" rows="5" placeholder="Paste the poem or behind-the-scenes notes">{{ old('body', $item->body ?? '') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Upload Media</label>
                <input type="file" name="media" class="form-control">
                <small class="text-muted d-block">MP4, MOV, AVI, MP3, or PDF up to 500MB.</small>
                @if($editing && $item->media_path)
                    <small class="text-muted">Current file: {{ basename($item->media_path) }}</small>
                @endif
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">External URL (optional)</label>
                <input type="url" name="external_url" class="form-control" value="{{ old('external_url', $item->external_url ?? '') }}" placeholder="https://vimeo.com/...">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
            <small class="text-muted">Square JPG/PNG up to 3MB.</small>
            @if($editing && $item->thumbnail_path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $item->thumbnail_path) }}" alt="Thumbnail preview" class="img-thumbnail" style="max-width: 140px;">
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="border rounded p-3 mb-3">
            <label class="form-label">Publishing</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="isFeatured">Mark as featured</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublished" {{ old('is_published', $item->is_published ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="isPublished">Publish immediately</label>
            </div>
            <div class="mt-3">
                <label class="form-label">Publish At</label>
                <input type="datetime-local" name="published_at" class="form-control"
                    value="{{ old('published_at', isset($item->published_at) ? $item->published_at->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div class="alert alert-info">
            Assigning a lower tier automatically unlocks higher tiers (e.g., Golden & Platinum subscribers can see Silver releases).
        </div>

        <div class="border rounded p-3">
            <label class="form-label d-flex align-items-center justify-content-between">
                <span>Standalone Purchase</span>
                <span class="badge bg-dark">Buy / Rent</span>
            </label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_standalone" value="1" id="isStandalone"
                    {{ old('is_standalone', $item->is_standalone ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="isStandalone">Enable pay-per-view (outside subscription)</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Standalone Category</label>
                <input type="text" name="standalone_category" class="form-control"
                    value="{{ old('standalone_category', $item->standalone_category ?? '') }}"
                    placeholder="Poems Library, Special Performances, Behind-the-Scenes...">
                <small class="text-muted">Used to group premium drops for storefront filters.</small>
            </div>

            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Buy Price (USD)</label>
                    <input type="number" step="0.01" min="0" name="standalone_price" class="form-control"
                        value="{{ old('standalone_price', $item->standalone_price ?? '') }}" placeholder="e.g. 9.99">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Rent Price (USD)</label>
                    <input type="number" step="0.01" min="0" name="standalone_rental_price" class="form-control"
                        value="{{ old('standalone_rental_price', $item->standalone_rental_price ?? '') }}" placeholder="e.g. 4.99">
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label">Rental Window (hours)</label>
                <input type="number" name="standalone_rental_hours" class="form-control" min="24" max="168"
                    value="{{ old('standalone_rental_hours', $item->standalone_rental_hours ?? 72) }}" placeholder="48-72 recommended">
                <small class="text-muted">Rentals expire after this window from the payment approval time.</small>
            </div>
        </div>
    </div>
</div>
