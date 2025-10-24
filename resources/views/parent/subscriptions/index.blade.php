@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>My Subscription</h1>
                <a class="btn btn-outline-secondary" href="{{ route('membership') }}">Browse Plans</a>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Current Status</div>
                        <div class="card-body">
                            @if(!$subscription)
                                <div class="alert alert-info mb-0">You do not have an active subscription.</div>
                            @else
                                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">{{ $subscription->stripe_status }}</span></p>
                                <p class="mb-1"><strong>Plan:</strong> {{ $plan?->name ?? '—' }}</p>
                                <p class="mb-1"><strong>Price/Interval:</strong> @if($plan) ${{ number_format($plan->price,2) }} / {{ $plan->interval }} @else — @endif</p>
                                <p class="mb-1"><strong>Stripe Price ID:</strong> {{ $subscription->stripe_price }}</p>
                                @if($subscription->trial_ends_at)
                                    <p class="mb-1"><strong>Trial ends:</strong> {{ \Illuminate\Support\Carbon::parse($subscription->trial_ends_at)->toDayDateTimeString() }}</p>
                                @endif
                                @if($subscription->ends_at)
                                    <p class="mb-1"><strong>Access until:</strong> {{ \Illuminate\Support\Carbon::parse($subscription->ends_at)->toDayDateTimeString() }}</p>
                                @endif
                                <div class="mt-3">
                                    <a href="{{ route('membership') }}" class="btn btn-primary">Change Plan</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Recent Updates</div>
                        <div class="card-body">
                            @if(($history ?? collect())->isEmpty())
                                <div class="text-muted">No recent updates found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>When</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Price</th>
                                                <th>Ends At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($history as $h)
                                                <tr>
                                                    <td>{{ $h->occurred_at ? \Illuminate\Support\Carbon::parse($h->occurred_at)->toDayDateTimeString() : \Illuminate\Support\Carbon::parse($h->created_at)->toDayDateTimeString() }}</td>
                                                    <td>{{ $h->type }}</td>
                                                    <td>{{ $h->status ?? '—' }}</td>
                                                    <td>{{ $h->stripe_price_id ?? '—' }}</td>
                                                    <td>{{ $h->ends_at ? \Illuminate\Support\Carbon::parse($h->ends_at)->toDayDateTimeString() : '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <small class="text-muted d-block mt-2">Showing up to 5 recent updates (webhooks and app actions).</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
