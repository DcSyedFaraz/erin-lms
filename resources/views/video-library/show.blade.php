@extends('admin.layout.app')

@section('content')
    <section class="video-detail-hero">
        <div class="container">
            <a href="{{ route('video-library.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to library</a>
            <div class="row">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <p class="tier-label text-uppercase mb-0">
                            <i class="fas fa-crown me-1"></i>{{ ucfirst($item->access_tier) }} Tier
                        </p>
                        @if ($item->is_standalone)
                            <span class="badge bg-dark text-uppercase">Standalone</span>
                        @endif
                        @if ($item->standalone_category)
                            <span class="badge bg-info text-dark">{{ $item->standalone_category }}</span>
                        @endif
                    </div>
                    <h1 class="black-head">{{ $item->title }}</h1>
                    <p class="lead">{{ $item->description }}</p>
                    <ul class="small text-muted list-inline">
                        <li class="list-inline-item"><i
                                class="fas fa-video me-1"></i>{{ \App\Models\VideoLibraryItem::CONTENT_TYPES[$item->content_type] ?? $item->content_type }}
                        </li>
                        <li class="list-inline-item"><i class="far fa-clock me-1"></i>Published
                            {{ optional($item->published_at)->format('M d, Y') ?? $item->created_at->format('M d, Y') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="video-detail-body pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if ($canStream)
                                @if ($item->isPoem())
                                    <article class="poem-body">
                                        {!! nl2br(e($item->body)) !!}
                                    </article>
                                @endif

                                @if ($item->media_path)
                                    <video controls controlsList="nodownload" class="w-100 rounded overflow-hidden">
                                        <source src="{{ asset('storage/' . $item->media_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($item->external_url)
                                    <div class="text-center py-4">
                                        <p>This experience is hosted externally.</p>
                                        <a href="{{ $item->external_url }}" target="_blank" rel="noreferrer"
                                            class="btn btn-primary">
                                            Open External Player
                                        </a>
                                    </div>
                                @endif

                                @if ($item->body && !$item->isPoem())
                                    <hr>
                                    <p class="mb-0">{{ $item->body }}</p>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-lock fa-2x text-warning mb-3"></i>
                                    <h5 class="mb-2">Unlock this experience</h5>
                                    <p class="text-muted mb-4">
                                        Choose Buy for lifetime access or Rent for a 48-72 hour window. Your access will
                                        appear instantly in your library once payment succeeds.
                                    </p>
                                    @if ($item->isStandaloneEnabled())
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            @if ($item->buyPrice())
                                                <form method="POST" action="{{ route('video-library.purchase', $item) }}">
                                                    @csrf
                                                    <input type="hidden" name="access_type" value="buy">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-unlock me-1"></i>Buy • ${{ number_format($item->buyPrice(), 2) }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($item->supportsRental())
                                                <form method="POST" action="{{ route('video-library.purchase', $item) }}">
                                                    @csrf
                                                    <input type="hidden" name="access_type" value="rent">
                                                    <button type="submit" class="btn btn-outline-primary">
                                                        <i class="fas fa-clock me-1"></i>Rent • ${{ number_format($item->rentPrice(), 2) }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-info d-inline-block">
                                            This drop is subscription-only. Upgrade your plan to watch instantly.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Your Access</h6>
                            @if ($planIncluded)
                                <p class="mb-1"><i class="fas fa-check-circle text-success me-1"></i>Included in your
                                    {{ $plan?->name ?? 'subscription' }}</p>
                            @endif
                            @if ($activePurchase)
                                @if ($activePurchase->isRental())
                                    <p class="mb-1"><i class="fas fa-film me-1 text-primary"></i>Rental active
                                        @if ($activePurchase->rental_expires_at)
                                            until {{ $activePurchase->rental_expires_at->format('M d, h:ia') }}
                                        @endif
                                    </p>
                                @else
                                    <p class="mb-1"><i class="fas fa-infinity me-1 text-success"></i>You own this video
                                        forever</p>
                                @endif
                            @endif

                            @if (!$planIncluded && !$activePurchase)
                                <p class="text-muted mb-0 small">Not unlocked yet. Buy for permanent access or rent for a
                                    limited window.</p>
                            @endif
                        </div>
                    </div>

                    @if ($item->isStandaloneEnabled())
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted">Standalone Options</h6>
                                <ul class="list-unstyled small mb-3">
                                    @if ($item->buyPrice())
                                        <li><i class="fas fa-unlock me-2 text-success"></i>Buy: Lifetime access within
                                            platform</li>
                                    @endif
                                    @if ($item->supportsRental())
                                        <li><i class="fas fa-clock me-2 text-primary"></i>Rent: {{ $item->rentalDurationHours() }}
                                            hrs from purchase</li>
                                    @endif
                                </ul>
                                <div class="d-grid gap-2">
                                    @if ($item->buyPrice())
                                        <form method="POST" action="{{ route('video-library.purchase', $item) }}">
                                            @csrf
                                            <input type="hidden" name="access_type" value="buy">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Buy • ${{ number_format($item->buyPrice(), 2) }}
                                            </button>
                                        </form>
                                    @endif
                                    @if ($item->supportsRental())
                                        <form method="POST" action="{{ route('video-library.purchase', $item) }}">
                                            @csrf
                                            <input type="hidden" name="access_type" value="rent">
                                            <button type="submit" class="btn btn-outline-primary w-100">
                                                Rent • ${{ number_format($item->rentPrice(), 2) }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Plan Coverage</h6>
                            @php $meta = \App\Models\SubscriptionPlan::tierMeta($item->access_tier); @endphp
                            <ul class="list-unstyled small mb-0">
                                <li><strong>Access:</strong> {{ $meta['access'] }}</li>
                                <li><strong>Content Updates:</strong> {{ $meta['updates'] }}</li>
                                <li><strong>Purpose:</strong> {{ $meta['purpose'] }}</li>
                            </ul>
                        </div>
                    </div>

                    @if ($plan && $planIncluded && $plan->tier_key !== $item->access_tier)
                        <div class="alert alert-info mt-3">
                            You can stream all {{ ucfirst($item->access_tier) }} releases with your current
                            {{ $plan->name }} plan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
