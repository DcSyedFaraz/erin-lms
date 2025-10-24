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

                            <hr>
                            <h5>Plan Features</h5>
                            <p class="text-muted">Add the features included with this package.</p>
                            <div id="features-list">
                                <div class="row g-2 mb-2 feature-row">
                                    <div class="col-md-6">
                                        <input type="text" name="features[0][name]" class="form-control" placeholder="Feature name (e.g., Access to all lessons)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="features[0][value]" class="form-control" placeholder="Value (optional)">
                                    </div>
                                    <div class="col-md-2 d-grid">
                                        <button type="button" class="btn btn-outline-danger remove-feature">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-feature" class="btn btn-outline-primary btn-sm mb-3">+ Add Feature</button>

                            <button type="submit" class="btn btn-success">Save Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    (function() {
        let idx = 1;
        const list = document.getElementById('features-list');
        document.getElementById('add-feature').addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 feature-row';
            row.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="features[${idx}][name]" class="form-control" placeholder="Feature name">
                </div>
                <div class="col-md-4">
                    <input type="text" name="features[${idx}][value]" class="form-control" placeholder="Value (optional)">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-outline-danger remove-feature">Remove</button>
                </div>
            `;
            list.appendChild(row);
            idx++;
        });

        list.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-feature')) {
                const row = e.target.closest('.feature-row');
                row.parentNode.removeChild(row);
            }
        });
    })();
</script>
@endsection
