@extends('admin.layout.app')

@section('content')
    <section class="video-library-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="eyebrow"><i class="fas fa-star me-1"></i> Subscriber Exclusive</p>
                    <h1 class="black-head mb-3"><i class="fas fa-film me-2"></i>Video Library</h1>
                    <p class="lead mb-4">
                        Stream curated videos, premium short films, inspiring poems, and cinematic clips organised by
                        subscription tier.
                        New drops arrive on the cadence defined by your plan.
                    </p>
                    @if ($plan)
                        @php $meta = \App\Models\SubscriptionPlan::tierMeta($plan->tier_key); @endphp
                        <div class="current-plan-pill">
                            <span class="label"><i class="fas fa-crown me-1"></i>Current Plan</span>
                            <strong>{{ $plan->name }}</strong>
                            <span class="price-tag"><i
                                    class="fas fa-tag me-1"></i>${{ number_format($plan->price, 2) }}/{{ $plan->interval }}</span>
                        </div>
                        <ul class="plan-meta">
                            <li><i class="fas fa-unlock-alt me-2"></i><strong>Access:</strong>
                                {{ $plan->access_summary ?? $meta['access'] }}</li>
                            <li><i class="fas fa-sync-alt me-2"></i><strong>Content Updates:</strong>
                                {{ $plan->content_updates_summary ?? $meta['updates'] }}</li>
                            <li><i class="fas fa-bullseye me-2"></i><strong>Purpose:</strong>
                                {{ $plan->purpose_summary ?? $meta['purpose'] }}</li>
                        </ul>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="tier-cards">
                        @foreach ($tiers as $tier => $copy)
                            <div class="tier-card {{ $tierKey === $tier ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        @if ($tier === 'bronze')
                                            <i class="fas fa-medal text-warning me-2"></i>
                                        @elseif($tier === 'silver')
                                            <i class="fas fa-award text-secondary me-2"></i>
                                        @elseif($tier === 'gold')
                                            <i class="fas fa-crown text-warning me-2"></i>
                                        @endif
                                        {{ ucfirst($tier) }} Plan
                                    </h5>
                                    @if ($tierKey === $tier)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Yours</span>
                                    @endif
                                </div>
                                <p class="small text-muted mb-1">{{ $copy['tagline'] }}</p>
                                <ul class="small list-unstyled mb-0">
                                    <li><i class="fas fa-check-circle text-success me-1"></i><strong>Access:</strong>
                                        {{ $copy['access'] }}</li>
                                    <li><i class="fas fa-check-circle text-success me-1"></i><strong>Updates:</strong>
                                        {{ $copy['updates'] }}</li>
                                    <li><i class="fas fa-check-circle text-success me-1"></i><strong>Purpose:</strong>
                                        {{ $copy['purpose'] }}</li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="video-library-content pb-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div class="filters-title">
                    <h4 class="mb-0"><i class="fas fa-grid-2 me-2"></i>Browse Library</h4>
                    <p class="small text-muted mb-0">Filter by format to find your next stream.</p>
                </div>
                <div class="filter-buttons">
                    <a href="{{ route('video-library.index') }}"
                        class="btn btn-sm {{ $activeFilter ? 'btn-outline-secondary' : 'btn-primary' }}">
                        <i class="fas fa-th me-1"></i>All
                    </a>
                    @foreach ($contentTypes as $key => $label)
                        <a href="{{ route('video-library.index', ['type' => $key]) }}"
                            class="btn btn-sm {{ $activeFilter === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                            @if ($key === 'video')
                                <i class="fas fa-video me-1"></i>
                            @elseif($key === 'short_film')
                                <i class="fas fa-film me-1"></i>
                            @elseif($key === 'poem')
                                <i class="fas fa-scroll me-1"></i>
                            @elseif($key === 'clip')
                                <i class="fas fa-play-circle me-1"></i>
                            @else
                                <i class="fas fa-file me-1"></i>
                            @endif
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($items->count() === 0)
                <div class="empty-state">
                    <h5><i class="fas fa-inbox me-2"></i>No releases yet</h5>
                    <p class="text-muted mb-0">We're preparing your first drop for this plan tier. Stay tuned!</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($items as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="library-card h-100">
                                <div class="media-thumb {{ $item->thumbnail_url ? '' : 'no-thumb' }}"
                                    @if ($item->thumbnail_url) style="background-image: url('{{ asset('storage/' . $item->thumbnail_path) }}')" @endif>
                                    <span class="badge bg-dark">
                                        @if ($item->content_type === 'video')
                                            <i class="fas fa-video me-1"></i>
                                        @elseif($item->content_type === 'short_film')
                                            <i class="fas fa-film me-1"></i>
                                        @elseif($item->content_type === 'poem')
                                            <i class="fas fa-scroll me-1"></i>
                                        @elseif($item->content_type === 'clip')
                                            <i class="fas fa-play-circle me-1"></i>
                                        @else
                                            <i class="fas fa-file me-1"></i>
                                        @endif
                                        {{ \App\Models\VideoLibraryItem::CONTENT_TYPES[$item->content_type] ?? $item->content_type }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="tier-label text-uppercase mb-1">
                                        @if ($item->access_tier === 'bronze')
                                            <i class="fas fa-medal me-1"></i>
                                        @elseif($item->access_tier === 'silver')
                                            <i class="fas fa-award me-1"></i>
                                        @elseif($item->access_tier === 'gold')
                                            <i class="fas fa-crown me-1"></i>
                                        @endif
                                        {{ ucfirst($item->access_tier) }} Access
                                    </p>
                                    <h5>{{ $item->title }}</h5>
                                    <p class="text-muted small">
                                        {{ \Illuminate\Support\Str::limit($item->description ?? $item->body, 110) }}</p>
                                    <a href="{{ route('video-library.show', $item) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-play me-1"></i>Watch Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($items->hasPages())
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $items->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
