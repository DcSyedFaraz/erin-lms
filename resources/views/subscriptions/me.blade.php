@extends('layout.app')

@section('content')
<section class="membership-main">
    <section class="sec1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="innersec1">
                        <h2 class="black-head">My Subscription</h2>
                    </div>
                </div>
                <div class="col-lg-6 "></div>
            </div>
        </div>
    </section>
    <section class="sec2">
        <div class="container">
            @if(!$subscription)
                <div class="alert alert-info">You do not have an active subscription.</div>
                <a class="btn btn-primary" href="{{ route('membership') }}">Browse Plans</a>
            @else
                <div class="card p-3">
                    <h3 class="black-head mb-3">Status: <span class="badge bg-success text-white">{{ $subscription->stripe_status }}</span></h3>
                    <p><strong>Plan:</strong> {{ $plan?->name ?? 'Unknown plan' }}</p>
                    <p><strong>Price:</strong> @if($plan) ${{ number_format($plan->price, 2) }} / {{ $plan->interval }} @else — @endif</p>
                    <p><strong>Stripe Price ID:</strong> {{ $subscription->stripe_price }}</p>
                    @if($subscription->trial_ends_at)
                        <p><strong>Trial ends:</strong> {{ \Illuminate\Support\Carbon::parse($subscription->trial_ends_at)->toDayDateTimeString() }}</p>
                    @endif
                    @if($subscription->ends_at)
                        <p><strong>Access until:</strong> {{ \Illuminate\Support\Carbon::parse($subscription->ends_at)->toDayDateTimeString() }}</p>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('membership') }}" class="btn btn-outline-secondary">Change Plan</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</section>
@endsection

