@extends('layouts/fullLayoutMaster')

@section('title', 'Verify 2FA')

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
    <div class="auth-wrapper auth-basic px-2">
        <div class="auth-inner my-2">
            <!-- verify 2FA -->
            <div class="card mb-0">
                <div class="card-body">
                    <a href="#" class="brand-logo">
                        <h2 class="brand-text text-primary ms-1">Medsecure</h2>
                    </a>

                    <h2 class="card-title fw-bolder mb-1">Verify your 2FA Code ✉️</h2>
                    <div class="text-center">
                        <p>Scan this QR code with your Google Authenticator App:</p>
                        <div>
                            {!! $QR_Image !!}
                        </div>
                    </div>
                    <form action="{{ route('2fa.verify') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="secret">Enter the code from Google Authenticator App</label>
                            <input type="text" name="secret" class="form-control" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Verify</button>
                    </form>
                </div>
            </div>
            <!-- / verify 2FA -->
        </div>
    </div>
@endsection
