@extends('layouts.telkom')

@section('content')
    {{-- Breadcrumb --}}
    <x-telkom.breadcrumb title="E-Lulus" :items="[
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Layanan', 'url' => '#'],
        ['label' => 'E-Lulus', 'url' => route('public.graduation.check')],
    ]" />

    <style>
        @media (max-width: 575.98px) {
            .elulus-card {
                padding: 24px 20px !important;
            }
        }
    </style>
    {{-- Main Section --}}
    <div class="rs-contact style1 pt-94 pb-100 md-pt-64 md-pb-70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">

                    {{-- Section Title --}}
                    <div class="sec-title text-center mb-50">
                        <div class="sub-title"
                            style="color: #f4821f; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;">
                            Layanan Digital
                        </div>
                        <h2 class="title" style="font-size: 2rem; font-weight: 700; color: #1c2b4a; margin-bottom: 15px;">
                            Cek Status Kelulusan
                        </h2>
                        <p class="desc" style="color: #666; font-size: 15px; line-height: 1.7;">
                            Masukkan NISN atau NIS untuk mengecek status kelulusan kamu secara online.
                        </p>
                    </div>

                    {{-- Alert Error --}}
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center mb-30" role="alert"
                            style="border-radius: 8px; border-left: 4px solid #dc3545; background: #fff5f5; color: #721c24; padding: 16px 20px;">
                            <i class="fa fa-exclamation-circle me-2" style="font-size: 18px; margin-right: 10px;"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    {{-- Card Form --}}
                    <div class="contact-wrap elulus-card"
                        style="background: #fff; border-radius: 12px; box-shadow: 0 8px 40px rgba(0,0,0,0.10); padding: 48px 44px;">

                        {{-- Info Box --}}
                        <div
                            style="background: #eef4ff; border-left: 4px solid #3d5ee1; border-radius: 8px; padding: 16px 20px; margin-bottom: 32px;">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <i class="fa fa-info-circle" style="color: #3d5ee1; font-size: 18px; margin-top: 2px;"></i>
                                <div>
                                    <p style="font-weight: 600; color: #1c2b4a; margin-bottom: 6px; font-size: 14px;">
                                        Informasi Penting</p>
                                    <ul
                                        style="margin: 0; padding-left: 16px; color: #555; font-size: 13px; line-height: 1.8;">
                                        <li>Masukkan NISN <em>atau</em> NIS — cukup salah satu</li>
                                        <li>Pastikan nomor yang dimasukkan sudah benar</li>
                                        <li>Jika data tidak ditemukan, hubungi admin sekolah</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('public.graduation.check.process') }}">
                            @csrf

                            {{-- NISN --}}
                            <div class="mb-4">
                                <label for="nisn"
                                    style="font-weight: 600; color: #1c2b4a; font-size: 14px; margin-bottom: 8px; display: block;">
                                    NISN <span style="color: #999; font-weight: 400;">(Nomor Induk Siswa Nasional)</span>
                                </label>
                                <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                    placeholder="Contoh: 0075823566"
                                    style="width: 100%; padding: 13px 16px; border: 1.5px solid #dde3f0; border-radius: 8px; font-size: 15px; color: #333; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#3d5ee1'" onblur="this.style.borderColor='#dde3f0'">
                                @error('nisn')
                                    <p style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Divider --}}
                            <div style="display: flex; align-items: center; gap: 12px; margin: 20px 0;">
                                <div style="flex: 1; height: 1px; background: #e5e9f0;"></div>
                                <span style="color: #999; font-size: 13px; font-weight: 500;">atau</span>
                                <div style="flex: 1; height: 1px; background: #e5e9f0;"></div>
                            </div>

                            {{-- NIS --}}
                            <div class="mb-4">
                                <label for="nis"
                                    style="font-weight: 600; color: #1c2b4a; font-size: 14px; margin-bottom: 8px; display: block;">
                                    NIS <span style="color: #999; font-weight: 400;">(Nomor Induk Siswa)</span>
                                </label>
                                <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                    placeholder="Contoh: 4009/353.067"
                                    style="width: 100%; padding: 13px 16px; border: 1.5px solid #dde3f0; border-radius: 8px; font-size: 15px; color: #333; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#3d5ee1'" onblur="this.style.borderColor='#dde3f0'">
                                @error('nis')
                                    <p style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="mt-4 text-center">
                                <button type="submit"
                                    style="background: linear-gradient(135deg, #3d5ee1 0%, #764ba2 100%); color: #fff; border: none; padding: 14px 48px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; letter-spacing: 0.5px; transition: opacity 0.2s; width: 100%;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                    <i class="fa fa-search" style="margin-right: 8px;"></i>
                                    Cek Status Kelulusan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Back link --}}
                    <div class="text-center mt-30">
                        <a href="{{ route('landing') }}" style="color: #3d5ee1; font-size: 14px; text-decoration: none;">
                            <i class="fa fa-arrow-left" style="margin-right: 6px;"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
