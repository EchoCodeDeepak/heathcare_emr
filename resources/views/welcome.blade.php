@extends('layouts.app')

@push('styles')
<style>
    .banner-wrapper {
        background-image: url('{{asset('images/banner.jpg')}}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
    }
</style>
@endpush

@section('content')
<div class="container-fluid banner-wrapper pt-5 pb-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card shadow-sm border-0 rounded-3 bg-white bg-opacity-75 backdrop-blur">
                
                <!-- Header -->
                <div class="card-header bg-info text-white text-center rounded-top">
                    <h4 class="mb-0">🩺 Healthcare EMR System</h4>
                    <small>Welcome to Your Medical Records</small>
                </div>
                
                <!-- Body -->
                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <p class="text-muted">
                            Secure, efficient, and comprehensive healthcare management system designed for modern medical facilities.
                        </p>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('login') }}" class="btn btn-info text-light btn-lg fw-bold">
                            Login to Account
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-info btn-lg fw-bold" style="display: none;">
                            Create New Account
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        Secure Healthcare EMR System
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection