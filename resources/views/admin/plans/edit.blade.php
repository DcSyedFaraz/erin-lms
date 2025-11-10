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
                                    value="{{ old('stripe_price_id', $plan->stripe_price_id) }}">
                                <small class="text-muted">Changing Stripe Price ID will not update Stripe; it only links this plan to an existing Stripe Price.</small>
                            </div>

                            <div class="mb-3">
                                <label>Price (e.g., 9.99)</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                    value="{{ old('price', $plan->price) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Interval</label>
                                <select name="interval" class="form-control" required>
                                    <option value="month" {{ old('interval', $plan->interval) == 'month' ? 'selected' : '' }}>Monthly</option>
                                    <option value="year" {{ old('interval', $plan->interval) == 'year' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Tier (optional)</label>
                                <select name="tier_key" class="form-control">
                                    <option value="">-- Select Tier --</option>
                                    @foreach(\App\Models\SubscriptionPlan::TIER_LEVELS as $tier => $order)
                                        <option value="{{ $tier }}" {{ old('tier_key', $plan->tier_key) === $tier ? 'selected' : '' }}>{{ ucfirst($tier) }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block">Tier selection powers the new video library gating.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Access Summary</label>
                                    <input type="text" name="access_summary" value="{{ old('access_summary', $plan->access_summary) }}" class="form-control" placeholder="Basic curated content library">
                                </div>
                                <div class="col-md-4">
                                    <label>Content Updates</label>
                                    <input type="text" name="content_updates_summary" value="{{ old('content_updates_summary', $plan->content_updates_summary) }}" class="form-control" placeholder="Weekly">
                                </div>
                                <div class="col-md-4">
                                    <label>Purpose</label>
                                    <input type="text" name="purpose_summary" value="{{ old('purpose_summary', $plan->purpose_summary) }}" class="form-control" placeholder="Ideal for casual users">
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_trial" value="1" class="form-check-input"
                                    {{ old('is_trial', $plan->is_trial) ? 'checked' : '' }}>
                                <label class="form-check-label">Is Trial Plan?</label>
                            </div>
                            <hr>
                            <h5>Plan Features</h5>
                            <p class="text-muted">Update, add, or remove features for this package.</p>
                            <div id="features-list">
                                @php $featureIndex = 0; @endphp
                                @forelse(($plan->features ?? []) as $feature)
                                    <div class="row g-2 mb-2 feature-row" data-id="{{ $feature->id }}">
                                        <input type="hidden" name="features[{{ $featureIndex }}][id]" value="{{ $feature->id }}">
                                        <div class="col-md-6">
                                            <input type="text" name="features[{{ $featureIndex }}][name]" class="form-control" value="{{ $feature->name }}" placeholder="Feature name">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="features[{{ $featureIndex }}][value]" class="form-control" value="{{ $feature->value }}" placeholder="Value (optional)">
                                        </div>
                                        <div class="col-md-2 d-grid">
                                            <button type="button" class="btn btn-outline-danger remove-feature" data-feature-id="{{ $feature->id }}">Remove</button>
                                        </div>
                                    </div>
                                    @php $featureIndex++; @endphp
                                @empty
                                    <div class="row g-2 mb-2 feature-row">
                                        <div class="col-md-6">
                                            <input type="text" name="features[0][name]" class="form-control" placeholder="Feature name">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="features[0][value]" class="form-control" placeholder="Value (optional)">
                                        </div>
                                        <div class="col-md-2 d-grid">
                                            <button type="button" class="btn btn-outline-danger remove-feature">Remove</button>
                                        </div>
                                    </div>
                                    @php $featureIndex = 1; @endphp
                                @endforelse
                            </div>
                            {{-- Hidden delete ids will be injected dynamically on submit --}}
                            <button type="button" id="add-feature" class="btn btn-outline-primary btn-sm mb-3">+ Add Feature</button>

                            <button type="submit" class="btn btn-success">Update Plan</button>
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
        let idx = {{ isset($featureIndex) ? $featureIndex : 0 }};
        const list = document.getElementById('features-list');
        const deleteIds = [];

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
                const fid = e.target.getAttribute('data-feature-id');
                if (fid) {
                    deleteIds.push(fid);
                }
                row.parentNode.removeChild(row);
            }
        });

        // On submit, append hidden inputs for delete ids
        const form = list.closest('form');
        form.addEventListener('submit', function() {
            deleteIds.forEach(function(id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'feature_delete_ids[]';
                input.value = id;
                form.appendChild(input);
            });
        });
    })();
</script>
@endsection
