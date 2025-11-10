@extends('admin.layout.app')


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Heading and Create Button --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Subscription Plans</h4>
                    <a href="{{ route('plans.create') }}" class="btn btn-primary">+ Create Plan</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table id="plansTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Tier</th>
                            <th>Price</th>
                            <th>Interval</th>
                            <th>Trial?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->tier_key ? ucfirst($plan->tier_key) : '—' }}</td>
                                <td>${{ $plan->price }}</td>
                                <td>{{ $plan->interval }}</td>
                                <td>{{ $plan->is_trial ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('plans.edit', $plan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#plansTable').DataTable();
        });
    </script>
@endsection
