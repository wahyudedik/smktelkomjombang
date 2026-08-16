@extends('layouts.telkom')

@section('content')
    <!-- Hero Slider Section -->
    <x-telkom.hero-slider />

    <!-- Services/Jurusan Section -->
    <x-telkom.services />

    <!-- About Section -->
    <x-telkom.about :siswaCount="$siswaCount" :kelulusanPercentage="$kelulusanPercentage" />

    <!-- Programs/Kerjasama Section -->
    <x-telkom.programs :partners="$partners" />

    <!-- CTA Section -->
    <x-telkom.cta />

    <!-- Events Section -->
    <x-telkom.events :events="$events" />

    <!-- Partners Section -->
    <x-telkom.partners :partners="$partners" />

    <!-- Testimonials Section -->
    <x-telkom.testimonials :testimonials="$testimonials" />

    <!-- Blog Section -->
    <x-telkom.blog :blogs="$blogs" />
@endsection
