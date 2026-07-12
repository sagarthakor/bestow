@extends('website.template.layout')
@section('title', 'Create Account')

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
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
    }
    .auth-header {
        background: linear-gradient(135deg, #e83e10, #c23209);
        color: #fff;
        padding: 28px 32px 22px;
        text-align: center;
    }
    .auth-header .auth-logo {
        width: 60px; height: 60px;
        background: rgba(255,255,255,.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px;
        margin: 0 auto 12px;
    }
    .auth-header h3 { font-size: 21px; font-weight: 700; margin: 0 0 4px; }
    .auth-header p  { font-size: 13px; opacity: .8; margin: 0; }

    .auth-body { padding: 30px 32px; }

    .form-group-ec { margin-bottom: 16px; }
    .form-group-ec label { font-size: 13px; font-weight: 600; margin-bottom: 5px; display: block; color: #374151; }

    .input-icon-wrap { position: relative; }
    .input-icon-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 18px; pointer-events: none;
    }
    .input-icon-wrap input { padding-left: 44px !important; }

    .btn-auth {
        display: block; width: 100%;
        background: var(--brand); color: #fff;
        border: none; border-radius: 8px;
        padding: 13px; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: background .2s, transform .15s;
        font-family: 'Poppins', sans-serif;
    }
    .btn-auth:hover { background: var(--brand-dark); transform: translateY(-1px); }

    .auth-footer {
        text-align: center; font-size: 13px; color: #6b7280; margin-top: 18px;
    }
    .auth-footer a { color: var(--brand); font-weight: 600; }

    .strength-bar {
        height: 4px; border-radius: 2px; background: #e5e7eb; margin-top: 6px; overflow: hidden;
    }
    .strength-bar div { height: 100%; border-radius: 2px; transition: width .3s, background .3s; }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo"><i class="la la-user-plus"></i></div>
                <h3>Create Your Account</h3>
                <p>Join thousands of happy customers</p>
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
                @if($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('website.register.submit') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group-ec">
                                <label>Full Name *</label>
                                <div class="input-icon-wrap">
                                    <i class="la la-user"></i>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="Enter your full name"
                                           value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-ec">
                                <label>Email Address *</label>
                                <div class="input-icon-wrap">
                                    <i class="la la-envelope"></i>
                                    <input type="email" name="email" class="form-control"
                                           placeholder="your@email.com"
                                           value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-ec">
                                <label>Mobile Number</label>
                                <div class="input-icon-wrap">
                                    <i class="la la-phone"></i>
                                    <input type="text" name="mobile" class="form-control"
                                           placeholder="10-digit mobile"
                                           value="{{ old('mobile') }}"
                                           pattern="[0-9]{10}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-ec">
                                <label>Password *</label>
                                <div class="input-icon-wrap">
                                    <i class="la la-lock"></i>
                                    <input type="password" name="password" id="passwordField"
                                           class="form-control" placeholder="Min. 8 characters" required
                                           oninput="checkStrength(this.value)">
                                </div>
                                <div class="strength-bar mt-1">
                                    <div id="strengthBar" style="width:0%; background:#dc2626;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-ec">
                                <label>Confirm Password *</label>
                                <div class="input-icon-wrap">
                                    <i class="la la-lock"></i>
                                    <input type="password" name="password_confirmation"
                                           class="form-control" placeholder="Re-enter password" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="background:#f8f9fa; border-radius:8px; padding:12px 14px; font-size:12px; color:#6b7280; margin-bottom:16px;">
                        <i class="la la-shield-alt" style="color:var(--brand);"></i>
                        By registering you agree to our <a href="#" style="color:var(--brand);">Terms &amp; Conditions</a> and <a href="#" style="color:var(--brand);">Privacy Policy</a>.
                    </div>

                    <button type="submit" class="btn-auth">
                        Create Account <i class="la la-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Already have an account?
                    <a href="{{ route('website.login') }}">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-javascript')
<script>
function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    let strength = 0;
    if (val.length >= 8)  strength += 25;
    if (/[A-Z]/.test(val)) strength += 25;
    if (/[0-9]/.test(val)) strength += 25;
    if (/[^a-zA-Z0-9]/.test(val)) strength += 25;
    bar.style.width = strength + '%';
    bar.style.background = strength <= 25 ? '#dc2626' : strength <= 50 ? '#f59e0b' : strength <= 75 ? '#3b82f6' : '#16a34a';
}
</script>
@endsection
