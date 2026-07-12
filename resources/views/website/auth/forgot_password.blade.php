@extends('website.template.layout')
@section('title', 'Forgot Password')

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
        max-width: 440px;
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
    .btn-auth {
        display: block; width: 100%;
        background: var(--brand); color: #fff;
        border: none; border-radius: 8px;
        padding: 13px; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: background .2s;
        font-family: 'Poppins', sans-serif;
    }
    .btn-auth:hover { background: var(--brand-dark); }
    .auth-footer { text-align: center; font-size: 13px; color: #6b7280; margin-top: 18px; }
    .auth-footer a { color: var(--brand); font-weight: 600; }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo"><i class="la la-key"></i></div>
                <h3>Forgot Password?</h3>
                <p>Enter your email to receive reset instructions</p>
            </div>

            <div class="auth-body">

                @if(session('success'))
                    <div class="alert alert-success" style="font-size:13px;">
                        <i class="la la-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" style="font-size:13px;">
                        <i class="la la-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('website.forgot.password.post') }}">
                    @csrf
                    <div style="margin-bottom:18px;">
                        <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block;">Email Address</label>
                        <div style="position:relative;">
                            <i class="la la-envelope" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:18px;"></i>
                            <input type="email" name="email" class="form-control"
                                   placeholder="your@email.com"
                                   style="padding-left:44px !important;" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        Send Reset Link <i class="la la-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Remember your password?
                    <a href="{{ route('website.login') }}">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
