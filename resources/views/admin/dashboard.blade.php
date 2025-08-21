@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <h2>Admin Dashboard</h2>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Total Users</h5>
                                    <h2>{{ $userCount }}</h2>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Total Courses</h5>
                                    <h2>{{ $courseCount }}</h2>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Total Revenue</h5>
                                    <h2>${{ $revenue }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
