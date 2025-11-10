@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1"><i class="fas fa-user-plus mr-2"></i>Assign Subscription</h1>
                        <p class="text-muted mb-0">Manually assign a subscription plan to a user</p>
                    </div>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Subscriptions
                    </a>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Please correct the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Main Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Subscription Assignment Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.subscriptions.assign.store') }}" method="POST">
                            @csrf

                            <!-- User Selection Section -->
                            <div class="form-section mb-4">
                                <h6 class="text-uppercase text-muted mb-3" style="font-weight: 600; letter-spacing: 0.5px;">
                                    <i class="fas fa-user mr-2"></i>User Information
                                </h6>
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">
                                        User <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control" name="user_id" required style="height: 45px;">
                                        <option value="">-- Select a user --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Choose the user who will receive this
                                        subscription
                                    </small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Plan Selection Section -->
                            <div class="form-section mb-4">
                                <h6 class="text-uppercase text-muted mb-3" style="font-weight: 600; letter-spacing: 0.5px;">
                                    <i class="fas fa-crown mr-2"></i>Plan Details
                                </h6>
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">
                                        Subscription Plan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control" name="subscription_plan_id" required
                                        style="height: 45px;">
                                        <option value="">-- Select a plan --</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }} -
                                                ${{ number_format($plan->price, 2) }} / {{ $plan->interval }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Select the subscription plan to assign
                                    </small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Duration Section -->
                            <div class="form-section mb-4">
                                <h6 class="text-uppercase text-muted mb-3" style="font-weight: 600; letter-spacing: 0.5px;">
                                    <i class="fas fa-calendar-alt mr-2"></i>Duration & Dates
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">
                                            <i class="fas fa-hourglass-start mr-1"></i> Trial Ends At
                                        </label>
                                        <input type="date" class="form-control" name="trial_ends_at"
                                            style="height: 45px;">
                                        <small class="form-text text-muted">Optional: Set when the trial period
                                            expires</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">
                                            <i class="fas fa-hourglass-end mr-1"></i> Subscription Ends At
                                        </label>
                                        <input type="date" class="form-control" name="ends_at" style="height: 45px;">
                                        <small class="form-text text-muted">Optional: Set subscription expiration
                                            date</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Additional Information Section -->
                            <div class="form-section mb-4">
                                <h6 class="text-uppercase text-muted mb-3" style="font-weight: 600; letter-spacing: 0.5px;">
                                    <i class="fas fa-sticky-note mr-2"></i>Additional Information
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">
                                        <i class="fas fa-comment mr-1"></i> Note
                                    </label>
                                    <textarea class="form-control" name="note" rows="3"
                                        placeholder="Add a note or reason for this manual assignment (optional)"></textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Add any relevant notes about why this
                                        subscription is being assigned manually
                                    </small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-check mr-2"></i> Assign Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3 border-info">
                    <div class="card-body bg-light">
                        <h6 class="text-info mb-2"><i class="fas fa-lightbulb mr-2"></i>Quick Tips</h6>
                        <ul class="mb-0 small text-muted">
                            <li>Manual assignments bypass the normal payment process</li>
                            <li>If no end date is specified, the subscription will use the plan's default billing cycle</li>
                            <li>Trial periods give users full access before billing begins</li>
                            <li>Always add a note for audit purposes when making manual assignments</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-section {
            padding: 0;
        }

        .form-label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .card {
            border: none;
            border-radius: 0.5rem;
        }

        .card-header {
            border-top-left-radius: 0.5rem !important;
            border-top-right-radius: 0.5rem !important;
            padding: 1rem 1.5rem;
        }

        .btn-lg {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
        }

        hr {
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection
