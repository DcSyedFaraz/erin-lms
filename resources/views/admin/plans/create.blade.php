@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Page Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Create New Plan</h1>
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
                        <form action="{{ route('plans.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            {{-- <div class="mb-3">
                                <label>Stripe Price ID</label>
                                <input type="text" name="stripe_price_id" class="form-control" required>
                            </div> --}}

                            <div class="mb-3">
                                <label>Price (e.g., 9.99)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Interval</label>
                                <select name="interval" class="form-control" required>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_trial" value="1" class="form-check-input">
                                <label class="form-check-label">Is Trial Plan?</label>
                            </div>

                            <button type="submit" class="btn btn-success">Save Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
