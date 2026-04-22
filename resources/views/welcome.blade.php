@extends('layouts.landing')

@section('content')
    {{-- Hero Carousel --}}
    @include('components.hero-carousel')

    {{-- Feature Cards --}}
    <x-landing.feature-cards />

    {{-- Campus Life (Headmaster Section) --}}
    <x-landing.campus-life />

    {{-- Video Section --}}
    <x-landing.video />

    {{-- About Section --}}
    <x-landing.about />

    {{-- Features/Stats Counter --}}
    <x-landing.features />

    {{-- Programs (3 Program Peminatan) --}}
    <x-landing.programs />

    {{-- Instagram Gallery --}}
    <x-landing.gallery :posts="$instagramPosts ?? []" />

    {{-- Testimonials --}}
    <x-landing.testimonials />
@endsection
