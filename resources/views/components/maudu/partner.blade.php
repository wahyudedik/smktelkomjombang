<!-- Partner Area -->
@props(['partners' => []])

<div class="partner-area bg pt-50 pb-50">
    <div class="container">
        <div class="partner-wrapper partner-slider owl-carousel owl-theme">
            @php
                /**
                 * Partner logo mapping: nama partner → nomor file di assets_maudu/assets/img/partner/
                 * File tersedia: 01.png sampai 10.png
                 */
                $partnerAssetMap = [
                    'axioo' => '01',
                    'gamelab' => '02',
                    'gamelab indonesia' => '02',
                    'plts' => '03',
                    'lab plts' => '03',
                    'fiber optik' => '04',
                    'lab fiber optik' => '04',
                    'studio seje' => '05',
                    'telkom' => '06',
                    'bri' => '07',
                    'pemkab jombang' => '08',
                    'kemenag' => '09',
                    'nu' => '10',
                ];
            @endphp
            @if (count($partners) > 0)
                @foreach ($partners as $partner)
                    @php
                        $logoUrl = null;
                        $partnerLower = strtolower(trim($partner->name ?? ''));

                        // 1. Coba ambil dari storage jika file ada
                        if (!empty($partner->logo) && Storage::disk('public')->exists($partner->logo)) {
                            $logoUrl = Storage::url($partner->logo);
                        }
                        // 2. Coba mapping ke asset MAUDU berdasarkan nama partner
                        elseif (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $logoUrl = asset($assetPath);
                            }
                        }
                        // 3. Coba cari langsung berdasarkan nama file di assets_maudu
                        elseif (!empty($partner->logo)) {
                            $assetPath = "assets_maudu/assets/img/partner/{$partner->logo}";
                            if (file_exists(public_path($assetPath))) {
                                $logoUrl = asset($assetPath);
                            }
                        }
                    @endphp
                    <div class="partner-item text-center">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" style="max-height: 60px;">
                        @else
                            <span class="partner-name fs-5 fw-bold text-muted">{{ $partner->name }}</span>
                        @endif
                    </div>
                @endforeach
            @else
                @php
                    $defaultPartners = [
                        'Axioo',
                        'GAMELAB',
                        'PLTS',
                        'Fiber Optik',
                        'Studio Seje',
                        'Telkom',
                        'BRI',
                        'Pemkab Jombang',
                        'Kemenag',
                        'NU',
                    ];
                @endphp
                @foreach ($defaultPartners as $partnerName)
                    @php
                        $partnerLower = strtolower($partnerName);
                        $defaultLogoUrl = null;
                        if (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $defaultLogoUrl = asset($assetPath);
                            }
                        }
                    @endphp
                    <div class="partner-item text-center">
                        @if ($defaultLogoUrl)
                            <img src="{{ $defaultLogoUrl }}" alt="{{ $partnerName }}" style="max-height: 60px;">
                        @else
                            <span class="partner-name fs-5 fw-bold text-muted">{{ $partnerName }}</span>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Partner Area End -->

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.partner-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2
                    },
                    576: {
                        items: 3
                    },
                    768: {
                        items: 4
                    },
                    1024: {
                        items: 5
                    }
                }
            });
        });
    </script>
@endpush
