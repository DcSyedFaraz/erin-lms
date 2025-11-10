@extends('admin.layout.app')

@section('content')
    <section class="video-detail-hero">
        <div class="container">
            <a href="{{ route('video-library.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to library</a>
            <div class="row">
                <div class="col-lg-7">
                    <p class="tier-label text-uppercase">{{ ucfirst($item->access_tier) }} Plan</p>
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
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

                    @if ($plan && $plan->tier_key !== $item->access_tier)
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
