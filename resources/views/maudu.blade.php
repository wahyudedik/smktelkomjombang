@extends('layouts.maudu')

@section('content')
    {{-- Hero Slider --}}
    <x-maudu.hero-slider />

    {{-- Feature Area --}}
    <x-maudu.feature-area />

    {{-- Kepala Madrasah --}}
    <x-maudu.about-kepala />

    {{-- Video Area --}}
    <x-maudu.video />

    {{-- Counter Area --}}
    <x-maudu.counter :siswaCount="$siswaCount" />

    {{-- About Area --}}
    <x-maudu.about-area />

    {{-- Choose Area / Program Peminatan --}}
    <x-maudu.choose-area />

    {{-- Programs / Kerjasama Industri --}}
    <x-maudu.programs :partners="$partners" />

    {{-- CTA / Pendaftaran --}}
    <x-maudu.cta />

    {{-- Events / Kegiatan --}}
    <x-maudu.events :events="$events" />

    {{-- Portfolio / Kegiatan MAUDU — Disediakan sementara karena gambar masih placeholder --}}
    {{-- <x-maudu.portfolio /> --}}

    {{-- Testimonial --}}
    <x-maudu.testimonial :testimonials="$testimonials" />

    {{-- Partner --}}
    <x-maudu.partner :partners="$partners" />

    {{-- Blog / Berita --}}
    <x-maudu.blog :blogs="$blogs" />

    {{-- Contact / Hubungi Kami --}}
    <x-maudu.contact />
@endsection
