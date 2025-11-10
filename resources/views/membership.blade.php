@extends('layout.app')

@section('content')
    <section class="membership-main">
        <section class="sec1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="innersec1">
                            <h2 class="black-head">Membership Options</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 "></div>
                </div>
            </div>
        </section>
        <section class="sec2">
            <div class="container">
                <div class="inner-sec2">
                    <h3 class="black-head">Our Pricing Plans</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the
                        industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                        type
                        and
                        scrambled it to make a type specimen book.</p>
                </div>
                <div class="row align-items-center">
                    @php $currentPriceId = $currentPriceId ?? null; @endphp
                    @forelse(($plans ?? []) as $plan)
                        @php
                            $tierMeta = \App\Models\SubscriptionPlan::tierMeta($plan->tier_key);
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card p-3 h-100 d-flex flex-column">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h2 class="card-heading mb-0">{{ $plan->name }}</h2>
                                        @if($plan->tier_key)
                                            <span class="badge bg-primary text-uppercase">{{ $plan->tier_key }}</span>
                                        @endif
                                    </div>
                                    <div class="price">
                                        <h4>${{ number_format($plan->price, 2) }} <span class="small-price">/{{ $plan->interval }}</span></h4>
                                    </div>
                                    @if($tierMeta['tagline'])
                                        <p class="text-muted small">{{ $tierMeta['tagline'] }}</p>
                                    @endif
                                    <ul class="list-unstyled small mb-3">
                                        @if($plan->access_summary ?? $tierMeta['access'])
                                            <li><strong>Access:</strong> {{ $plan->access_summary ?? $tierMeta['access'] }}</li>
                                        @endif
                                        @if($plan->content_updates_summary ?? $tierMeta['updates'])
                                            <li><strong>Content Updates:</strong> {{ $plan->content_updates_summary ?? $tierMeta['updates'] }}</li>
                                        @endif
                                        @if($plan->purpose_summary ?? $tierMeta['purpose'])
                                            <li><strong>Purpose:</strong> {{ $plan->purpose_summary ?? $tierMeta['purpose'] }}</li>
                                        @endif
                                    </ul>
                                    @if(($plan->features ?? null) && $plan->features->count())
                                        <ul class="features">
                                            @foreach($plan->features as $feature)
                                                <li>
                                                    {{ $feature->name }}
                                                    @if($feature->value)
                                                        - <small>{{ $feature->value }}</small>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="d-grid gap-2">
                                    @auth
                                        @php $isSubscribed = auth()->user()->subscribed('default'); @endphp
                                        @if($isSubscribed && $currentPriceId === $plan->stripe_price_id)
                                            <button type="button" class="btn btn-success w-100" disabled>Current Plan</button>
                                            <a href="{{ route('subscriptions.me') }}" class="btn btn-outline-secondary w-100">Manage Subscription</a>
                                        @else
                                            <form method="POST" action="{{ route('subscriptions.checkout', $plan) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">{{ $isSubscribed ? 'Change to ' . $plan->name : 'Choose ' . $plan->name }}</button>
                                            </form>
                                            @if($isSubscribed)
                                                <a href="{{ route('subscriptions.me') }}" class="btn btn-outline-secondary w-100">Manage Subscription</a>
                                            @endif
                                        @endif
                                    @else
                                        <form method="POST" action="{{ route('subscriptions.checkout', $plan) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">Choose {{ $plan->name }}</button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">No subscription packages available yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </section>
  @endsection
