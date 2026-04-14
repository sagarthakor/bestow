@extends('website.template.layout')
@section('title', 'My Account')

@section('page-css')
<style>
    .account-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    /* ── Sidebar ──────────────────────── */
    .acc-sidebar {
        width: 240px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .acc-sidebar-header {
        background: linear-gradient(135deg, #1a1f36, #2d3561);
        padding: 20px;
        text-align: center;
        color: #fff;
    }
    .acc-sidebar-header .avatar {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 700;
        margin: 0 auto 10px;
        border: 3px solid rgba(255,255,255,.3);
    }
    .acc-sidebar-header h6 { font-size: 15px; font-weight: 700; margin: 0; }
    .acc-sidebar-header small { font-size: 12px; opacity: .75; }

    .acc-nav { padding: 10px 0; }
    .acc-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        transition: background .15s, color .15s;
    }
    .acc-nav a:hover, .acc-nav a.active { background: #fef2f0; color: var(--brand); }
    .acc-nav a i { font-size: 18px; width: 20px; }
    .acc-nav .logout-link { color: #dc2626; }
    .acc-nav .logout-link:hover { background: #fef2f2; }
    .acc-nav hr { margin: 8px 0; border-color: var(--border); }

    /* ── Content ──────────────────────── */
    .acc-content { flex: 1; min-width: 0; }

    .acc-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 20px;
    }
    .acc-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .acc-card-header h6 { font-size: 16px; font-weight: 700; margin: 0; color: var(--navy); }
    .acc-card-header i  { font-size: 20px; color: var(--brand); }
    .acc-card-body { padding: 20px; }

    /* Stat cards */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; }
    .stat-card {
        border-radius: 10px;
        padding: 18px;
        text-align: center;
        border: 1px solid var(--border);
    }
    .stat-card .stat-icon { font-size: 32px; margin-bottom: 8px; }
    .stat-card .stat-val  { font-size: 24px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-card .stat-label{ font-size: 12px; color: var(--mid); }

    /* Info table */
    .info-table { width: 100%; }
    .info-table tr td { padding: 8px 0; font-size: 14px; border-bottom: 1px solid var(--border); }
    .info-table tr:last-child td { border-bottom: none; }
    .info-table .td-label { color: var(--mid); width: 150px; font-size: 13px; }
    .info-table .td-val   { font-weight: 600; }

    @media(max-width: 767px) {
        .account-layout { flex-direction: column; }
        .acc-sidebar { width: 100%; position: static; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li>My Account</li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="account-layout">

        <!-- ── SIDEBAR ──────────────────────────── -->
        <div class="acc-sidebar">
            <div class="acc-sidebar-header">
                <div class="avatar">{{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}</div>
                <h6>{{ $user['name'] ?? 'User' }}</h6>
                <small>{{ $user['email'] ?? '' }}</small>
            </div>
            <nav class="acc-nav">
                <a href="{{ route('website.account.dashboard') }}" class="active">
                    <i class="la la-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('website.orders') }}">
                    <i class="la la-shopping-bag"></i> My Orders
                </a>
                <a href="{{ route('website.track.order') }}">
                    <i class="la la-truck"></i> Track Order
                </a>
                <hr>
                <a href="{{ route('website.logout') }}" class="logout-link">
                    <i class="la la-sign-out-alt"></i> Sign Out
                </a>
            </nav>
        </div>

        <!-- ── MAIN CONTENT ─────────────────────── -->
        <div class="acc-content">

            <!-- Welcome banner -->
            <div style="background:linear-gradient(135deg,#e83e10,#c23209); color:#fff; border-radius:12px; padding:24px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div>
                    <h5 style="font-size:20px; font-weight:700; margin:0 0 4px;">Hello, {{ $user['name'] ?? 'User' }}!</h5>
                    <p style="margin:0; font-size:13px; opacity:.9;">Welcome to your account dashboard</p>
                </div>
                <a href="{{ route('website.orders') }}" style="background:#fff; color:#e83e10; padding:10px 20px; border-radius:8px; font-weight:700; font-size:13px;">
                    View My Orders
                </a>
            </div>

            <!-- Stats -->
            <div class="acc-card mb-4">
                <div class="acc-card-header">
                    <i class="la la-chart-bar"></i>
                    <h6>Account Overview</h6>
                </div>
                <div class="acc-card-body">
                    <div class="stat-grid">
                        <div class="stat-card" style="border-color:#dbeafe; background:#eff6ff;">
                            <div class="stat-icon">📦</div>
                            <div class="stat-val" style="color:#1d4ed8;">{{ $totalOrders ?? 0 }}</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                        <div class="stat-card" style="border-color:#dcfce7; background:#f0fdf4;">
                            <div class="stat-icon">✅</div>
                            <div class="stat-val" style="color:#16a34a;">{{ $deliveredOrders ?? 0 }}</div>
                            <div class="stat-label">Delivered</div>
                        </div>
                        <div class="stat-card" style="border-color:#fef3c7; background:#fffbeb;">
                            <div class="stat-icon">🚚</div>
                            <div class="stat-val" style="color:#d97706;">{{ $pendingOrders ?? 0 }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile info -->
            <div class="acc-card">
                <div class="acc-card-header">
                    <i class="la la-user-circle"></i>
                    <h6>Profile Information</h6>
                </div>
                <div class="acc-card-body">
                    <table class="info-table">
                        <tr>
                            <td class="td-label">Full Name</td>
                            <td class="td-val">{{ $user['name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Email</td>
                            <td class="td-val">{{ $user['email'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Mobile</td>
                            <td class="td-val">{{ $user['phone'] ?? $user['mobile'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Member Since</td>
                            <td class="td-val">{{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('M Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
