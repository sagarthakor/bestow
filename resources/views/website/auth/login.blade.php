@extends('website.template.layout')

@section('title', 'Login')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        <h3 class="text-center mb-3">Welcome Back 👋</h3>
                        <p class="text-muted text-center mb-4">Login to access your account</p>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('website.login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email or Mobile</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter email or phone" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <label><input type="checkbox" name="remember"> Remember Me</label>
                                <a href="{{ route('website.forgot.password') }}" class="text-primary">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            Don’t have an account?
                            <a href="{{ route('website.register') }}">Register Now</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
