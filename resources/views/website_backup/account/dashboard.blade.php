@extends('website.template.layout')

@section('title', 'Your Account')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Your Account</h3>

        <div class="card p-3">
            <h5>Welcome, {{ $user['name'] ?? 'User' }}</h5>
            <p>Email: {{ $user['email'] ?? '-' }}</p>

            <a href="{{ route('website.orders') }}" class="btn btn-primary btn-sm mt-3">View Your Orders</a>
        </div>
    </div>
@endsection
