@extends('layouts.maudu')

@section('content')

    {{-- Breadcrumb --}}
    <x-maudu.breadcrumb title="Hasil Pengecekan Kelulusan" :items="[
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'E-Lulus', 'url' => route('public.graduation.check')],
        ['label' => 'Hasil', 'url' => '#'],
    ]" />

    {{-- Result Section --}}
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">

                    {{-- Status Banner --}}
                    @if ($kelulusan->status === 'lulus')
                        <div
                            style="background: linear-gradient(135deg, #1a5632 0%, #2d8a4e 100%); border-radius: 16px; padding: 40px 32px; text-align: center; margin-bottom: 36px; box-shadow: 0 8px 32px rgba(26,86,50,0.25);">
                            <div style="font-size: 56px; margin-bottom: 12px;">🎉</div>
                            <h2
                                style="color: #fff; font-size: 1.8rem; font-weight: 700; margin-bottom: 8px; text-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                Selamat! Dinyatakan LULUS
                            </h2>
                            <p style="color: rgba(255,255,255,0.9); font-size: 15px; margin: 0;">
                                {{ $kelulusan->nama }}
                            </p>
                        </div>
                    @elseif($kelulusan->status === 'tidak_lulus')
                        <div
                            style="background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); border-radius: 16px; padding: 40px 32px; text-align: center; margin-bottom: 36px; box-shadow: 0 8px 32px rgba(192,57,43,0.25);">
                            <div style="font-size: 56px; margin-bottom: 12px;">😔</div>
                            <h2 style="color: #fff; font-size: 1.8rem; font-weight: 700; margin-bottom: 8px;">
                                Belum Lulus
                            </h2>
                            <p style="color: rgba(255,255,255,0.9); font-size: 15px; margin: 0;">
                                {{ $kelulusan->nama }} — Tetap semangat! 💪
                            </p>
                        </div>
                    @else
                        <div
                            style="background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%); border-radius: 16px; padding: 40px 32px; text-align: center; margin-bottom: 36px; box-shadow: 0 8px 32px rgba(243,156,18,0.25);">
                            <div style="font-size: 56px; margin-bottom: 12px;">⏳</div>
                            <h2 style="color: #fff; font-size: 1.8rem; font-weight: 700; margin-bottom: 8px;">
                                Sedang Diproses
                            </h2>
                            <p style="color: rgba(255,255,255,0.9); font-size: 15px; margin: 0;">
                                {{ $kelulusan->nama }} — Status masih dalam proses
                            </p>
                        </div>
                    @endif

                    {{-- Check counter --}}
                    @if ($kelulusan->check_count > 0)
                        <div
                            style="background: #e8f5e9; border-radius: 8px; padding: 12px 20px; text-align: center; margin-bottom: 28px; font-size: 13px; color: #1a5632;">
                            <i class="fa fa-bar-chart" style="margin-right: 6px;"></i>
                            Pengecekan ke-<strong>{{ $kelulusan->check_count }}</strong>
                            @if ($kelulusan->last_checked_at)
                                &nbsp;·&nbsp; Terakhir: {{ $kelulusan->last_checked_at->format('d/m/Y H:i') }}
                            @endif
                        </div>
                    @endif

                    {{-- Data Siswa --}}
                    <div
                        style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 24px; border: 1px solid #e8f5e9;">
                        <div style="background: #1a5632; padding: 18px 28px;">
                            <h4 style="color: #fff; margin: 0; font-size: 16px; font-weight: 600;">
                                <i class="fa fa-user-circle" style="margin-right: 8px; color: #a8e6cf;"></i>
                                Data Siswa
                            </h4>
                        </div>
                        <div style="padding: 28px;">
                            <div class="row align-items-center">
                                {{-- Foto --}}
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    @if ($kelulusan->foto)
                                        <img src="{{ $kelulusan->photo_url }}" alt="{{ $kelulusan->nama }}"
                                            style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #1a5632;">
                                    @else
                                        <div
                                            style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #1a5632, #2d8a4e); display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 3px solid #c8e6c9;">
                                            <span
                                                style="color: #fff; font-size: 36px; font-weight: 700;">{{ strtoupper(substr($kelulusan->nama, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Info --}}
                                <div class="col-md-9">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 7px 0; color: #888; font-size: 13px; width: 130px;">Nama
                                                Lengkap</td>
                                            <td style="padding: 7px 0; color: #1a2e1a; font-weight: 600; font-size: 15px;">
                                                {{ $kelulusan->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 7px 0; color: #888; font-size: 13px;">NISN</td>
                                            <td style="padding: 7px 0; color: #333; font-size: 14px;">{{ $kelulusan->nisn }}
                                            </td>
                                        </tr>
                                        @if ($kelulusan->nis)
                                            <tr>
                                                <td style="padding: 7px 0; color: #888; font-size: 13px;">NIS</td>
                                                <td style="padding: 7px 0; color: #333; font-size: 14px;">
                                                    {{ $kelulusan->nis }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="padding: 7px 0; color: #888; font-size: 13px;">Jurusan</td>
                                            <td style="padding: 7px 0; color: #333; font-size: 14px;">
                                                {{ $kelulusan->jurusan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 7px 0; color: #888; font-size: 13px;">Tahun Ajaran</td>
                                            <td style="padding: 7px 0; color: #333; font-size: 14px;">
                                                {{ $kelulusan->tahun_ajaran }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 7px 0; color: #888; font-size: 13px;">Status</td>
                                            <td style="padding: 7px 0;">
                                                @if ($kelulusan->status === 'lulus')
                                                    <span
                                                        style="background: #d4edda; color: #155724; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">LULUS</span>
                                                @elseif($kelulusan->status === 'tidak_lulus')
                                                    <span
                                                        style="background: #f8d7da; color: #721c24; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">TIDAK
                                                        LULUS</span>
                                                @else
                                                    <span
                                                        style="background: #fff3cd; color: #856404; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">MENGULANG</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($kelulusan->tanggal_lulus)
                                            <tr>
                                                <td style="padding: 7px 0; color: #888; font-size: 13px;">Tanggal Lulus</td>
                                                <td style="padding: 7px 0; color: #333; font-size: 14px;">
                                                    {{ $kelulusan->tanggal_lulus->format('d F Y') }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktivitas --}}
                    @if ($kelulusan->tempat_kuliah || $kelulusan->tempat_kerja)
                        <div
                            style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 24px; border: 1px solid #e8f5e9;">
                            <div style="background: #1a5632; padding: 18px 28px;">
                                <h4 style="color: #fff; margin: 0; font-size: 16px; font-weight: 600;">
                                    <i class="fa fa-briefcase" style="margin-right: 8px; color: #a8e6cf;"></i>
                                    Aktivitas Saat Ini
                                </h4>
                            </div>
                            <div style="padding: 28px;">
                                <div class="row">
                                    @if ($kelulusan->tempat_kuliah)
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div style="background: #e8f5e9; border-radius: 10px; padding: 20px;">
                                                <div style="color: #1a5632; font-size: 22px; margin-bottom: 8px;"><i
                                                        class="fa fa-graduation-cap"></i></div>
                                                <p
                                                    style="font-weight: 600; color: #1a2e1a; margin-bottom: 4px; font-size: 14px;">
                                                    Pendidikan Lanjutan</p>
                                                <p style="color: #555; font-size: 13px; margin: 0;">
                                                    {{ $kelulusan->education_path }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($kelulusan->tempat_kerja)
                                        <div class="col-md-6">
                                            <div style="background: #f0fff4; border-radius: 10px; padding: 20px;">
                                                <div style="color: #2d8a4e; font-size: 22px; margin-bottom: 8px;"><i
                                                        class="fa fa-building"></i></div>
                                                <p
                                                    style="font-weight: 600; color: #1a2e1a; margin-bottom: 4px; font-size: 14px;">
                                                    Pekerjaan</p>
                                                <p style="color: #555; font-size: 13px; margin: 0;">
                                                    {{ $kelulusan->career_path }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Prestasi --}}
                    @if ($kelulusan->prestasi)
                        <div
                            style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 24px; border: 1px solid #e8f5e9;">
                            <div style="background: #1a5632; padding: 18px 28px;">
                                <h4 style="color: #fff; margin: 0; font-size: 16px; font-weight: 600;">
                                    <i class="fa fa-trophy" style="margin-right: 8px; color: #a8e6cf;"></i>
                                    Prestasi
                                </h4>
                            </div>
                            <div style="padding: 28px;">
                                <p style="color: #555; font-size: 14px; line-height: 1.7; margin: 0;">
                                    {{ $kelulusan->prestasi }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-top: 8px;">
                        <a href="{{ route('public.graduation.check') }}"
                            style="background: linear-gradient(135deg, #1a5632 0%, #2d8a4e 100%); color: #fff; padding: 13px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa fa-search"></i> Cek Status Lain
                        </a>
                        @if ($kelulusan->status === 'lulus')
                            <button onclick="window.print()"
                                style="background: #fff; color: #1a2e1a; border: 2px solid #1a5632; padding: 13px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa fa-print"></i> Cetak Hasil
                            </button>
                        @endif
                        <a href="{{ route('landing') }}"
                            style="background: #f5f7fa; color: #555; padding: 13px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa fa-home"></i> Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- Print Styles --}}
    @push('styles')
        <style>
            @media print {

                .header,
                .footer-area,
                .breadcrumb-area {
                    display: none !important;
                }

                body {
                    background: white !important;
                }
            }
        </style>
    @endpush

@endsection
