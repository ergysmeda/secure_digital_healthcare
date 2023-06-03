@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

@section('content')
    <style>
        .card {
            margin-top: 50px;
        }

        .display-4 {
            font-size: 3rem;
            font-weight: bold;
        }

        .lead {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        hr {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
<!-- Kick start -->
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Welcome</h4>
  </div>
  <div class="card-body">
    <div class="card-text">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Welcome to MedSecure</div>

                        <div class="card-body">
                            <h1>Welcome to MedSecure!</h1>
                            <p>This is the welcome page of your MedSecure application.</p>
                            <p>You can customize this page as per your requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!--/ Kick start -->

@endsection
