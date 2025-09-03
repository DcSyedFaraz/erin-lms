@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $ownedCount }}</h3>
                    <p>My Courses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <a href="{{ route('parent.courses.my') }}" class="small-box-footer">View Library <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>${{ number_format($totalSpent, 2) }}</h3>
                    <p>Total Spent</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('parent.courses.my') }}" class="small-box-footer">See Purchases <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $availableCount }}</h3>
                    <p>Available To Buy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-store"></i>
                </div>
                <a href="{{ route('parent.courses.index') }}" class="small-box-footer">Browse Courses <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Recent Purchases</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchases as $p)
                                    <tr>
                                        <td>{{ optional($p->course)->title }}</td>
                                        <td>${{ number_format($p->amount / 100, 2) }}</td>
                                        <td>{{ optional($p->purchased_at ?? $p->created_at)->format('M d, Y') }}</td>
                                        <td><span class="badge badge-{{ $p->status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($p->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted text-center">No purchases yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('parent.courses.my') }}" class="btn btn-outline-secondary btn-sm">Go to My Courses</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-outline card-primary h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Suggested for You</h3>
                    <a href="{{ route('parent.courses.index') }}" class="btn btn-sm btn-outline-primary">Browse</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($suggestedCourses as $course)
                        <div class="col-12 mb-3">
                            <div class="d-flex">
                                @if($course->thumbnail)
                                    <img src="{{ asset($course->thumbnail) }}" class="mr-3" style="width: 72px; height: 72px; object-fit: cover; border-radius: 6px;" alt="{{ $course->title }}">
                                @endif
                                <div class="flex-fill">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <strong>{{ $course->title }}</strong>
                                        <span class="badge badge-success">${{ number_format($course->price, 2) }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($course->description, 90) }}</div>
                                    <button class="btn btn-sm btn-primary btn-buy-modal"
                                        data-course-id="{{ $course->id }}"
                                        data-course-title="{{ $course->title }}"
                                        data-course-price="${{ number_format($course->price, 2) }}">
                                        <i class="fas fa-credit-card mr-1"></i> Buy
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="col-12 text-muted">No suggestions at the moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('parent.courses._buy_modal')
@endsection
