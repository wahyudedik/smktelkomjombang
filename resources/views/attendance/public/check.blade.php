@extends('layouts.telkom')

@section('content')
    {{-- Breadcrumb --}}
    <x-telkom.breadcrumb title="Cek Absensi Saya" :items="[
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Cek Absensi', 'url' => route('public.attendance.index')],
    ]" />

    <style>
        @media (max-width: 575.98px) {
            .absensi-card {
                padding: 24px 20px !important;
            }
            .absensi-table {
                font-size: 13px;
            }
            .absensi-table th,
            .absensi-table td {
                padding: 8px 6px !important;
            }
        }
    </style>

    {{-- Main Section --}}
    <div class="rs-contact style1 pt-94 pb-100 md-pt-64 md-pb-70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">

                    {{-- Section Title --}}
                    <div class="sec-title text-center mb-50">
                        <div class="sub-title"
                            style="color: #f4821f; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;">
                            Layanan Digital
                        </div>
                        <h2 class="title" style="font-size: 2rem; font-weight: 700; color: #1c2b4a; margin-bottom: 15px;">
                            Cek Absensi Saya
                        </h2>
                        <p class="desc" style="color: #666; font-size: 15px; line-height: 1.7;">
                            Masukkan PIN absensi kamu untuk melihat rekap kehadiran 30 hari terakhir.
                        </p>
                    </div>

                    {{-- Alert Error --}}
                    @if (!empty($error))
                        <div class="alert alert-danger d-flex align-items-center mb-30" role="alert"
                            style="border-radius: 8px; border-left: 4px solid #dc3545; background: #fff5f5; color: #721c24; padding: 16px 20px;">
                            <i class="fa fa-exclamation-circle me-2" style="font-size: 18px; margin-right: 10px;"></i>
                            <div>{{ $error }}</div>
                        </div>
                    @endif

                    {{-- Card Form --}}
                    <div class="contact-wrap absensi-card"
                        style="background: #fff; border-radius: 12px; box-shadow: 0 8px 40px rgba(0,0,0,0.10); padding: 48px 44px;">

                        @if (empty($attendances))
                            {{-- PIN Form --}}
                            <form method="POST" action="{{ route('public.attendance.check') }}">
                                @csrf
                                <div class="mb-30">
                                    <label for="pin" style="font-weight: 600; color: #1c2b4a; font-size: 15px; margin-bottom: 8px; display: block;">
                                        PIN Absensi
                                    </label>
                                    <input type="text" id="pin" name="pin"
                                        class="form-control"
                                        placeholder="Masukkan PIN absensi kamu"
                                        value="{{ $pin ?? '' }}"
                                        required autofocus
                                        style="border-radius: 8px; border: 2px solid #e2e8f0; padding: 14px 16px; font-size: 16px; transition: border-color 0.3s;"
                                        onfocus="this.style.borderColor='#f4821f'"
                                        onblur="this.style.borderColor='#e2e8f0'">
                                </div>
                                <button type="submit"
                                    class="btn btn-primary w-100"
                                    style="border-radius: 8px; padding: 14px; font-size: 16px; font-weight: 600; background: linear-gradient(135deg, #f4821f 0%, #e06b10 100%); border: none; color: #fff; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='translateY(-1px)'"
                                    onmouseout="this.style.transform='translateY(0)'">
                                    <i class="fa fa-search" style="margin-right: 8px;"></i> Cek Absensi
                                </button>
                            </form>
                        @else
                            {{-- Attendance Results --}}
                            <div class="mb-30 text-center">
                                <div style="display: inline-block; background: linear-gradient(135deg, #1c2b4a 0%, #2d4a7a 100%); color: #fff; border-radius: 10px; padding: 16px 32px; margin-bottom: 20px;">
                                    <i class="fa fa-user" style="margin-right: 8px;"></i>
                                    <strong>{{ $name }}</strong>
                                    <span style="opacity: 0.7; margin-left: 8px;">PIN: {{ $pin }}</span>
                                </div>
                            </div>

                            {{-- Summary Cards --}}
                            @php
                                $totalDays = $attendances->count();
                                $hadirCount = $attendances->where('status', 'Hadir')->count();
                                $terlambatCount = $attendances->where('status', 'Terlambat')->count();
                                $tidakHadirCount = $attendances->where('status', 'Tidak Hadir')->count();
                            @endphp
                            <div class="row mb-30">
                                <div class="col-6 col-md-3 mb-2">
                                    <div style="background: #e8f5e9; border-radius: 8px; padding: 14px; text-align: center;">
                                        <p style="margin: 0; font-size: 12px; color: #2e7d32; font-weight: 600;">Hadir</p>
                                        <p style="margin: 4px 0 0; font-size: 24px; font-weight: 700; color: #2e7d32;">{{ $hadirCount }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div style="background: #fff3e0; border-radius: 8px; padding: 14px; text-align: center;">
                                        <p style="margin: 0; font-size: 12px; color: #e65100; font-weight: 600;">Terlambat</p>
                                        <p style="margin: 4px 0 0; font-size: 24px; font-weight: 700; color: #e65100;">{{ $terlambatCount }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div style="background: #ffebee; border-radius: 8px; padding: 14px; text-align: center;">
                                        <p style="margin: 0; font-size: 12px; color: #c62828; font-weight: 600;">Tidak Hadir</p>
                                        <p style="margin: 4px 0 0; font-size: 24px; font-weight: 700; color: #c62828;">{{ $tidakHadirCount }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div style="background: #e3f2fd; border-radius: 8px; padding: 14px; text-align: center;">
                                        <p style="margin: 0; font-size: 12px; color: #1565c0; font-weight: 600;">Total Hari</p>
                                        <p style="margin: 4px 0 0; font-size: 24px; font-weight: 700; color: #1565c0;">{{ $totalDays }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Attendance Table --}}
                            <div class="table-responsive">
                                <table class="table absensi-table" style="border-radius: 8px; overflow: hidden;">
                                    <thead style="background: #1c2b4a; color: #fff;">
                                        <tr>
                                            <th style="border: none; padding: 12px 16px; font-weight: 600;">#</th>
                                            <th style="border: none; padding: 12px 16px; font-weight: 600;">Tanggal</th>
                                            <th style="border: none; padding: 12px 16px; font-weight: 600;">Masuk</th>
                                            <th style="border: none; padding: 12px 16px; font-weight: 600;">Pulang</th>
                                            <th style="border: none; padding: 12px 16px; font-weight: 600;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($attendances as $i => $att)
                                            @php
                                                $statusColor = match ($att->status) {
                                                    'Hadir' => '#2e7d32',
                                                    'Terlambat' => '#e65100',
                                                    'Tidak Hadir' => '#c62828',
                                                    'Izin' => '#1565c0',
                                                    'Sakit' => '#6a1b9a',
                                                    default => '#666',
                                                };
                                                $statusBg = match ($att->status) {
                                                    'Hadir' => '#e8f5e9',
                                                    'Terlambat' => '#fff3e0',
                                                    'Tidak Hadir' => '#ffebee',
                                                    'Izin' => '#e3f2fd',
                                                    'Sakit' => '#f3e5f5',
                                                    default => '#f5f5f5',
                                                };
                                            @endphp
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 12px 16px; color: #666;">{{ $i + 1 }}</td>
                                                <td style="padding: 12px 16px; font-weight: 500; color: #1c2b4a;">
                                                    {{ $att->date->translatedFormat('d M Y') }}
                                                </td>
                                                <td style="padding: 12px 16px; color: #333;">
                                                    {{ $att->first_in_at?->format('H:i') ?? '-' }}
                                                </td>
                                                <td style="padding: 12px 16px; color: #333;">
                                                    {{ $att->last_out_at?->format('H:i') ?? '-' }}
                                                </td>
                                                <td style="padding: 12px 16px;">
                                                    <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        {{ $att->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center" style="padding: 24px; color: #999;">
                                                    Belum ada data absensi dalam 30 hari terakhir.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Back Button --}}
                            <div class="text-center mt-30">
                                <a href="{{ route('public.attendance.index') }}"
                                    style="display: inline-block; background: #e2e8f0; color: #1c2b4a; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.3s;"
                                    onmouseover="this.style.background='#cbd5e1'"
                                    onmouseout="this.style.background='#e2e8f0'">
                                    <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Cek PIN Lain
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
