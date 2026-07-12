@extends('website.template.layout')
@section('title', 'Forgot Password')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-3">Forgot Password?</h3>
                        <p class="text-muted text-center">Enter your email to receive reset instructions</p>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('website.forgot.password.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                        </form>

                        <p class="text-center mt-3">
                            <a href="{{ route('website.login') }}">Back to Login</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
