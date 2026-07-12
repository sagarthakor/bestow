@extends('website.template.layout')
@section('title', 'Sign In')

@section('page-css')
<style>
    .auth-page {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff3ef 100%);
        padding: 40px 0;
    }
    .auth-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,.1);
        overflow: hidden;
        max-width: 460px;
        width: 100%;
        margin: 0 auto;
    }
    .auth-header {
        background: linear-gradient(135deg, #1a1f36, #2d3561);
        color: #fff;
        padding: 30px 32px 24px;
        text-align: center;
    }
    .auth-header .auth-logo {
        width: 64px; height: 64px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        margin: 0 auto 14px;
    }
    .auth-header h3 { font-size: 22px; font-weight: 700; margin: 0 0 4px; }
    .auth-header p  { font-size: 13px; opacity: .75; margin: 0; }

    .auth-body { padding: 32px; }

    .form-group-ec { margin-bottom: 18px; }
    .form-group-ec label { font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block; color: #374151; }

    .input-icon-wrap {
        position: relative;
    }
    .input-icon-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 18px;
        pointer-events: none;
    }
    .input-icon-wrap input {
        padding-left: 44px !important;
    }

    .btn-auth {
        display: block;
        width: 100%;
        background: var(--brand);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 13px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .15s;
        font-family: 'Poppins', sans-serif;
    }
    .btn-auth:hover { background: var(--brand-dark); transform: translateY(-1px); }

    .auth-divider {
        display: flex; align-items: center; gap: 12px; margin: 20px 0;
        font-size: 12px; color: #9ca3af;
    }
    .auth-divider::before, .auth-divider::after {
        content: ''; flex: 1; height: 1px; background: #e5e7eb;
    }

    .auth-footer {
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        margin-top: 20px;
    }
    .auth-footer a { color: var(--brand); font-weight: 600; }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo"><i class="la la-lock"></i></div>
                <h3>Welcome Back!</h3>
                <p>Sign in to access your account</p>
            </div>

            <div class="auth-body">

                @if(session('error'))
                    <div class="alert alert-danger" style="font-size:13px;">
                        <i class="la la-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success" style="font-size:13px;">
                        <i class="la la-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('website.login.submit') }}">
                    @csrf

                    <div class="form-group-ec">
                        <label>Email or Mobile</label>
                        <div class="input-icon-wrap">
                            <i class="la la-user"></i>
                            <input type="text" name="email" class="form-control"
                                   placeholder="Enter email or phone number"
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group-ec">
                        <label>Password</label>
                        <div class="input-icon-wrap">
                            <i class="la la-lock"></i>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <label style="font-size:13px; font-weight:400; color:#6b7280; display:flex; gap:6px; align-items:center; cursor:pointer; margin:0;">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="{{ route('website.forgot.password') }}" style="font-size:13px; color:var(--brand); font-weight:600;">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="btn-auth">
                        Sign In <i class="la la-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Don't have an account?
                    <a href="{{ route('website.register') }}">Create one now</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
