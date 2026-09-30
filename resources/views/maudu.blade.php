@extends('layouts.maudu')

@section('content')
    {{-- Hero Slider --}}
    <x-maudu.hero-slider />

    {{-- Feature Area --}}
    <x-maudu.feature-area />

    {{-- Kepala Madrasah / Campus Life --}}
    <x-maudu.about-kepala />

    {{-- Video Area --}}
    <x-maudu.video />

    {{-- Counter Area --}}
    <x-maudu.counter :siswaCount="$siswaCount" />

    {{-- About Area (Program Unggulan) --}}
    <x-maudu.about-area />

    {{-- Choose Area (Program Peminatan) --}}
    <x-maudu.choose-area />

    {{-- Blog / Berita --}}
    <x-maudu.blog :blogs="$blogs" />

    {{-- Testimonial --}}
    <x-maudu.testimonial :testimonials="$testimonials" />

    {{-- Events / Kegiatan --}}
    <x-maudu.events :events="$events" />

    {{-- Partner --}}
    <x-maudu.partner :partners="$partners" />
@endsection
