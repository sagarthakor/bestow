@extends('website.template.layout')
@section('title', 'Contact Us')

@section('page-css')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #1a1f36, #2d3561);
        color: #fff;
        padding: 48px 0;
        text-align: center;
        margin-bottom: 0;
    }
    .contact-hero h2 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
    .contact-hero p  { font-size: 15px; opacity: .8; margin: 0; }

    .contact-info-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        padding: 24px;
        height: 100%;
    }

    .contact-detail-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .contact-detail-item:last-child { margin-bottom: 0; }
    .contact-detail-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: var(--brand-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: var(--brand);
        flex-shrink: 0;
    }
    .contact-detail-label { font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: var(--mid); margin-bottom: 3px; }
    .contact-detail-value { font-size: 14px; font-weight: 600; color: var(--text); }
    .contact-detail-value a { color: var(--text); }
    .contact-detail-value a:hover { color: var(--brand); }

    .contact-form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        padding: 28px;
    }

    .btn-contact {
        display: inline-block;
        background: var(--brand); color: #fff;
        border: none; border-radius: 8px;
        padding: 13px 32px; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: background .2s;
        font-family: 'Poppins', sans-serif;
    }
    .btn-contact:hover { background: var(--brand-dark); }
</style>
@endsection

@section('content')

<!-- Hero -->
<div class="contact-hero">
    <div class="container">
        <h2>Get in Touch</h2>
        <p>Have a question or need help? We'd love to hear from you!</p>
    </div>
</div>

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li>Contact Us</li>
        </ol>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        <!-- Left: Info + Map -->
        <div class="col-lg-5">
            <div class="contact-info-card mb-4">
                <h5 class="fw-700" style="margin-bottom:20px; padding-bottom:12px; border-bottom:2px solid var(--brand-light);">
                    <i class="la la-store" style="color:var(--brand);"></i> Our Office
                </h5>

                <div class="contact-detail-item">
                    <div class="contact-detail-icon"><i class="la la-map-marker"></i></div>
                    <div>
                        <div class="contact-detail-label">Address</div>
                        <div class="contact-detail-value">
                            Ground Floor, Pagedar's Wado,<br>
                            Sardar Bhavan Ln, Near Guru Classes,<br>
                            Kadwa Sheri, Vadodara, Gujarat 390001
                        </div>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <div class="contact-detail-icon"><i class="la la-phone"></i></div>
                    <div>
                        <div class="contact-detail-label">Phone</div>
                        <div class="contact-detail-value">
                            <a href="tel:+918154876897">+91 81548 76897</a><br>
                            <a href="tel:+919377794101">+91 93777 94101</a>
                        </div>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <div class="contact-detail-icon"><i class="la la-envelope"></i></div>
                    <div>
                        <div class="contact-detail-label">Email</div>
                        <div class="contact-detail-value">
                            <a href="mailto:bestowsales1@gmail.com">bestowsales1@gmail.com</a>
                        </div>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <div class="contact-detail-icon"><i class="la la-clock"></i></div>
                    <div>
                        <div class="contact-detail-label">Working Hours</div>
                        <div class="contact-detail-value">
                            Mon – Sat: 9:00 AM – 7:00 PM<br>
                            <span style="color:var(--mid); font-size:13px;">Sunday: Closed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div style="border-radius:12px; overflow:hidden; border:1px solid var(--border);">
                <iframe
                    src="https://www.google.com/maps?q=Pagedar's+Wado,+Sardar+Bhavan+Lane,+Kadwa+Sheri,+Vadodara&output=embed"
                    width="100%" height="220" frameborder="0" allowfullscreen loading="lazy">
                </iframe>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="col-lg-7">
            <div class="contact-form-card">
                <h5 class="fw-700" style="margin-bottom:20px; padding-bottom:12px; border-bottom:2px solid var(--brand-light);">
                    <i class="la la-paper-plane" style="color:var(--brand);"></i> Send Us a Message
                </h5>

                @if(session('success'))
                    <div class="alert alert-success" style="font-size:13px; border-radius:8px;">
                        <i class="la la-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" style="font-size:13px; border-radius:8px;">
                        <i class="la la-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('website.contact') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Your Name *</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Full name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control"
                                   placeholder="Phone number" value="{{ old('mobile') }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label>Email Address *</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="your@email.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control"
                                   placeholder="What is this about?" value="{{ old('subject') }}">
                        </div>
                        <div class="col-12 mb-4">
                            <label>Message *</label>
                            <textarea name="message" rows="5" class="form-control"
                                      placeholder="How can we help you?" required>{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-contact">
                        <i class="la la-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
