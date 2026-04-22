@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">{{ $link->title }}</h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">Home</a></li>
                <li class="active">Testimonial</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Info Header -->
                    <div class="text-center mb-4">
                        @if ($link->description)
                            <p class="lead text-muted">{{ $link->description }}</p>
                        @endif
                        <div class="text-muted small mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Active until: {{ $link->active_until->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('testimonials.public.store', $link->token) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="card-title border-bottom pb-2 mb-3">Personal Information</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position *</label>
                                        <select class="form-select @error('position') is-invalid @enderror" id="position"
                                            name="position" required>
                                            <option value="">Select Position</option>
                                            @foreach ($link->target_audience as $audience)
                                                <option value="{{ $audience }}"
                                                    {{ old('position') == $audience ? 'selected' : '' }}>
                                                    {{ $audience }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" id="graduation_year_field" style="display: none;">
                                        <label for="graduation_year" class="form-label">Graduation Year</label>
                                        <input type="number"
                                            class="form-control @error('graduation_year') is-invalid @enderror"
                                            id="graduation_year" name="graduation_year"
                                            value="{{ old('graduation_year') }}" min="1900"
                                            max="{{ date('Y') + 10 }}">
                                        @error('graduation_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" id="class_field" style="display: none;">
                                        <label for="class" class="form-label">Class</label>
                                        <input type="text" class="form-control @error('class') is-invalid @enderror"
                                            id="class" name="class" value="{{ old('class') }}">
                                        @error('class')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo (Optional)</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                            id="photo" name="photo" accept="image/*">
                                        <div class="form-text">Max size: 2MB (JPEG, PNG, JPG, GIF)</div>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Testimonial -->
                                <div class="mb-4">
                                    <h5 class="card-title border-bottom pb-2 mb-3">Your Testimonial</h5>

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating *</label>
                                        <div class="star-rating" id="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star star" data-rating="{{ $i }}"
                                                    style="cursor: pointer; font-size: 2rem; color: #ddd;"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="rating" name="rating" value="{{ old('rating') }}"
                                            required>
                                        @error('rating')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="testimonial" class="form-label">Your Testimonial *</label>
                                        <textarea class="form-control @error('testimonial') is-invalid @enderror" id="testimonial" name="testimonial"
                                            rows="5" required>{{ old('testimonial') }}</textarea>
                                        <div class="form-text">Share your experience with us</div>
                                        @error('testimonial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Submit Testimonial
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card shadow-sm mt-4 bg-light">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="fas fa-info-circle mr-2"></i>
                                Information
                            </h6>
                            <p class="card-text small">
                                Your testimonial will be reviewed by admin before appearing on the website.
                                Thank you for your feedback!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Position field change handler
                document.getElementById('position').addEventListener('change', function() {
                    const graduationField = document.getElementById('graduation_year_field');
                    const classField = document.getElementById('class_field');

                    if (this.value === 'Alumni') {
                        graduationField.style.display = 'block';
                        classField.style.display = 'none';
                        document.getElementById('graduation_year').required = true;
                        document.getElementById('class').required = false;
                    } else if (this.value === 'Siswa') {
                        graduationField.style.display = 'none';
                        classField.style.display = 'block';
                        document.getElementById('graduation_year').required = false;
                        document.getElementById('class').required = true;
                    } else {
                        graduationField.style.display = 'none';
                        classField.style.display = 'none';
                        document.getElementById('graduation_year').required = false;
                        document.getElementById('class').required = false;
                    }
                });

                // Star rating
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('rating');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        ratingInput.value = rating;

                        // Update star colors
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.style.color = '#fbbf24'; // yellow
                            } else {
                                s.style.color = '#ddd'; // gray
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
