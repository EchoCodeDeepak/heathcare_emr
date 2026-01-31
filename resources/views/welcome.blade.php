@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">



@section('content')
<div class="container-fluid p-0">
    <!-- <div class="banner-wrapper" style="background-image: url('{{asset('images/banner.jpg')}}')"> -->
    <div class="banner-wrapper" style="background-image: url('{{ asset('images/banner.jpg') }}'); width:100%; height:100vh;">
        <div class="banner-overlay">
            <div class="overlay-box">
                <h3 class="text-center mb-3">Demo Access</h3>
                <div class="row">
                    <div class="col-md-6 col-12"> <!-- Responsive columns -->
                        <p><strong>Admin:</strong> admin@emr.com</p>
                        <p><strong>Doctor:</strong> doctor@emr.com</p>
                        <p><strong>Nurse:</strong> nurse@emr.com</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <p><strong>Lab:</strong> lab@emr.com</p>
                        <p><strong>Patient:</strong> patient@emr.com</p>
                        <p><strong>Password:</strong> password123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <br><br><br><br><br><br><br> -->
</div>
@endsection