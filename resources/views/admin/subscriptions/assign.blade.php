@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Assign Subscription</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.assign.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <select class="form-select" name="user_id" required>
                                <option value="">Select user</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subscription Plan</label>
                            <select class="form-select" name="subscription_plan_id" required>
                                <option value="">Select plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 2) }} / {{ $plan->interval }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trial Ends At (optional)</label>
                                <input type="date" class="form-control" name="trial_ends_at">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ends At (optional)</label>
                                <input type="date" class="form-control" name="ends_at">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note (optional)</label>
                            <input type="text" class="form-control" name="note" placeholder="Reason or note for manual assignment">
                        </div>

                        <button type="submit" class="btn btn-primary">Assign Subscription</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

