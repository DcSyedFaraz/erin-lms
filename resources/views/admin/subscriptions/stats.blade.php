@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Reports & Stats</h1>
                <a class="btn btn-outline-secondary" href="{{ route('admin.subscriptions.index') }}">View Purchasers</a>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Subscription Stats</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between"><span>Active Subscriptions</span><strong>{{ $activeCount }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>Canceled/Ended</span><strong>{{ $canceledCount }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>Unique Subscribers</span><strong>{{ $uniqueSubscribers }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>MRR (est.)</span><strong>${{ number_format($mrr, 2) }}</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">Course Sales</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between"><span>Total Courses</span><strong>{{ $courseTotals }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>Published Courses</span><strong>{{ $coursePublished }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>Paid Purchases</span><strong>{{ $coursePurchases }}</strong></li>
                                <li class="list-group-item d-flex justify-content-between"><span>Revenue (all-time)</span><strong>${{ number_format($courseRevenue, 2) }}</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

