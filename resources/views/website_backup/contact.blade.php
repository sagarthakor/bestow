@extends('website.template.layout')
@section('title', 'Contact Us')

@section('content')
    <div class="container py-5">

        {{-- Page Title --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">Contact Us</h2>
            <p class="text-muted small">We’d love to hear from you! Reach out using the form below or visit us at our office.</p>
        </div>

        <div class="row">
            {{-- Contact Details --}}
            <div class="col-md-5 mb-4">
                <div class="p-3 border rounded shadow-sm">
                    <h5 class="fw-bold mb-3">Our Office</h5>
                    <p class="small text-muted mb-1">
                        Ground Floor, Pagedar’s Wado, <br>
                        Sardar Bhavan Ln, Near Guru Classes,<br>
                        Kadwa Sheri, Vadodara, Gujarat 390001
                    </p>
                    <p class="small mb-1"><strong>Phone:</strong> <a href="tel:02652410727" class="text-reset">0265 241 0727</a></p>
                    <p class="small mb-3"><strong>Email:</strong> info@yourcompany.com</p>

                    {{-- Google Map --}}
                    <div class="map-responsive rounded">
                        <iframe src="https://www.google.com/maps?q=Pagedar’s Wado, Sardar Bhavan Lane, Kadwa Sheri, Vadodara&output=embed"
                                width="100%" height="200" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-md-7">
                <div class="p-3 border rounded shadow-sm">
                    <h5 class="fw-bold mb-3">Send us a Message</h5>
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('website.contact') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small">Your Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="Enter your mobile number" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small">Email Address</label>
                            <input type="email" name="email"  value="email" class="form-control" placeholder="Enter email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small">Message</label>
                            <textarea name="message" rows="4" class="form-control" placeholder="Write your message..." required>{{old('message')}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .map-responsive iframe {
            width: 100%;
            border-radius: 8px;
            border: 0;
        }
    </style>
@endsection
