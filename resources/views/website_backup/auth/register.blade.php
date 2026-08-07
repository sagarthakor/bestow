@extends('website.template.layout')

@section('title', 'Register')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        <h3 class="text-center mb-3">Create Account ✅</h3>
                        <p class="text-muted text-center mb-4">Fill the form to register</p>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('website.register.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter phone number">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            Already have an account?
                            <a href="{{ route('website.login') }}">Login Now</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
