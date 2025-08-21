@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Page Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Edit Plan</h1>
                    <a href="{{ route('plans.index') }}" class="btn btn-secondary mb-3">← Back to Plans</a>
                </div>

                {{-- Validation Errors --}}
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
                        <form action="{{ route('plans.update', $plan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $plan->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Stripe Price ID</label>
                                <input type="text" name="stripe_price_id" class="form-control"
                                    value="{{ old('stripe_price_id', $plan->stripe_price_id) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Price (e.g., 9.99)</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                    value="{{ old('price', $plan->price) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Interval</label>
                                <select name="interval" class="form-control" required>
                                    <option value="monthly"
                                        {{ old('interval', $plan->interval) == 'monthly' ? 'selected' : '' }}>Monthly
                                    </option>
                                    <option value="yearly"
                                        {{ old('interval', $plan->interval) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_trial" value="1" class="form-check-input"
                                    {{ old('is_trial', $plan->is_trial) ? 'checked' : '' }}>
                                <label class="form-check-label">Is Trial Plan?</label>
                            </div>
                            <button type="submit" class="btn btn-success">Update Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
