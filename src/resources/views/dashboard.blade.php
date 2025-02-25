@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary bg-gradient text-white">
                    <h4 class="mb-0">Dashboard</h4>
                    <span class="badge bg-light text-primary">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </span>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-primary shadow-sm">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Messages</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $messageCount ?? 0 }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-success shadow-sm">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Tasks</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $taskCount ?? 0 }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-info shadow-sm">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Progress</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">50%</div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item py-3">
                                            <i class="fas fa-envelope-open text-primary me-2"></i>
                                            New message received
                                            <span class="float-end text-muted small">3 minutes ago</span>
                                        </li>
                                        <li class="list-group-item py-3">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Task completed
                                            <span class="float-end text-muted small">1 hour ago</span>
                                        </li>
                                        <li class="list-group-item py-3">
                                            <i class="fas fa-file-alt text-info me-2"></i>
                                            Report generated
                                            <span class="float-end text-muted small">Yesterday</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
