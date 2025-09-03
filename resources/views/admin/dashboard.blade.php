@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $userCount }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('users.index') }}" class="small-box-footer">Manage Users <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $courseCount }}</h3>
                    <p>Total Courses</p>
                </div>
                <div class="icon"><i class="fas fa-book-open"></i></div>
                <a href="{{ route('courses.index') }}" class="small-box-footer">Manage Courses <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>${{ number_format($revenue, 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <span class="small-box-footer text-white-50">From paid course purchases</span>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $purchasesCount }}</h3>
                    <p>Paid Purchases</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <span class="small-box-footer text-white-50">Lifetime</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header"><h3 class="card-title">Course Status</h3></div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h3 mb-0">{{ $publishedCourses }}</div>
                            <div class="text-muted">Published</div>
                        </div>
                        <div class="col-6">
                            <div class="h3 mb-0">{{ $draftCourses }}</div>
                            <div class="text-muted">Draft</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-success h-100">
                <div class="card-header"><h3 class="card-title">Top Courses</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Course</th><th class="text-right pr-3">Purchases</th></tr></thead>
                            <tbody>
                                @forelse($topCourses as $c)
                                    <tr>
                                        <td>{{ $c->title }}</td>
                                        <td class="text-right pr-3">{{ $c->purchases_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-muted text-center">No data yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Recent Purchases</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchases as $p)
                                    <tr>
                                        <td>{{ optional($p->user)->name }}</td>
                                        <td>{{ optional($p->course)->title }}</td>
                                        <td>${{ number_format($p->amount / 100, 2) }}</td>
                                        <td>{{ optional($p->purchased_at ?? $p->created_at)->format('M d, Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted text-center">No purchases yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
