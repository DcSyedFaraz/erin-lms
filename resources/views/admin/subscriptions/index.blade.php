@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Plan Purchasers</h1>
                <a href="{{ route('admin.subscriptions.stats') }}" class="btn btn-outline-info">View Stats</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form class="row g-2 mb-3" method="GET">
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                @foreach (['active','trialing','past_due','incomplete','incomplete_expired','canceled','unpaid'] as $opt)
                                    <option value="{{ $opt }}" @selected(($status ?? '') === $opt)>{{ ucfirst(str_replace('_',' ', $opt)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Trial Ends</th>
                                    <th>Ends At</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $sub)
                                    <tr>
                                        <td>{{ $sub->id }}</td>
                                        <td>{{ $sub->user_name }}</td>
                                        <td>{{ $sub->user_email }}</td>
                                        <td>
                                            {{ $sub->plan_name ?? 'Unknown' }}
                                            @if($sub->plan_price)
                                                <small class="text-muted">${{ number_format($sub->plan_price, 2) }} / {{ $sub->plan_interval }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $sub->stripe_status }}</span></td>
                                        <td>{{ $sub->trial_ends_at ? \Illuminate\Support\Carbon::parse($sub->trial_ends_at)->toDateString() : '—' }}</td>
                                        <td>{{ $sub->ends_at ? \Illuminate\Support\Carbon::parse($sub->ends_at)->toDateString() : '—' }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($sub->created_at)->toDateString() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No subscriptions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

