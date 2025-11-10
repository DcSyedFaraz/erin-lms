@extends('admin.layout.app')

@section('content')
    <section class="video-library-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="eyebrow">Subscriber Exclusive</p>
                    <h1 class="black-head mb-3">Video Library</h1>
                    <p class="lead mb-4">
                        Stream curated videos, premium short films, inspiring poems, and cinematic clips organised by subscription tier.
                        New drops arrive on the cadence defined by your plan.
                    </p>
                    @if($plan)
                        @php $meta = \App\Models\SubscriptionPlan::tierMeta($plan->tier_key); @endphp
                        <div class="current-plan-pill">
                            <span class="label">Current Plan</span>
                            <strong>{{ $plan->name }}</strong>
                            <span class="price-tag">${{ number_format($plan->price, 2) }}/{{ $plan->interval }}</span>
                        </div>
                        <ul class="plan-meta">
                            <li><strong>Access:</strong> {{ $plan->access_summary ?? $meta['access'] }}</li>
                            <li><strong>Content Updates:</strong> {{ $plan->content_updates_summary ?? $meta['updates'] }}</li>
                            <li><strong>Purpose:</strong> {{ $plan->purpose_summary ?? $meta['purpose'] }}</li>
                        </ul>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="tier-cards">
                        @foreach($tiers as $tier => $copy)
                            <div class="tier-card {{ $tierKey === $tier ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ ucfirst($tier) }} Plan</h5>
                                    @if($tierKey === $tier)
                                        <span class="badge bg-success">Yours</span>
                                    @endif
                                </div>
                                <p class="small text-muted mb-1">{{ $copy['tagline'] }}</p>
                                <ul class="small list-unstyled mb-0">
                                    <li><strong>Access:</strong> {{ $copy['access'] }}</li>
                                    <li><strong>Updates:</strong> {{ $copy['updates'] }}</li>
                                    <li><strong>Purpose:</strong> {{ $copy['purpose'] }}</li>
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
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="filters-title">
                    <h4 class="mb-0">Browse Library</h4>
                    <p class="small text-muted mb-0">Filter by format to find your next stream.</p>
                </div>
                <div class="filter-buttons">
                    <a href="{{ route('video-library.index') }}"
                        class="btn btn-sm {{ $activeFilter ? 'btn-outline-secondary' : 'btn-primary' }}">All</a>
                    @foreach($contentTypes as $key => $label)
                        <a href="{{ route('video-library.index', ['type' => $key]) }}"
                            class="btn btn-sm {{ $activeFilter === $key ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            @if($items->count() === 0)
                <div class="card empty-state">
                    <div class="card-body text-center">
                        <h5>No releases yet</h5>
                        <p class="text-muted mb-0">We're preparing your first drop for this plan tier.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($items as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="library-card h-100">
                                <div class="media-thumb {{ $item->thumbnail_url ? '' : 'no-thumb' }}" @if($item->thumbnail_url) style="background-image: url('{{ asset('storage/' . $item->thumbnail_path) }}')" @endif>
                                    <span class="badge bg-dark">{{ \App\Models\VideoLibraryItem::CONTENT_TYPES[$item->content_type] ?? $item->content_type }}</span>
                                </div>
                                <div class="card-body">
                                    <p class="tier-label text-uppercase mb-1">{{ ucfirst($item->access_tier) }} Access</p>
                                    <h5>{{ $item->title }}</h5>
                                    <p class="text-muted small">{{ \Illuminate\Support\Str::limit($item->description ?? $item->body, 110) }}</p>
                                    <a href="{{ route('video-library.show', $item) }}" class="btn btn-outline-primary btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
