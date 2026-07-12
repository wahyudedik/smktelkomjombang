<!-- Programs / Kerjasama Industri -->
@props(['partners' => []])

<div class="program-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="site-heading">
                    <span class="sub-title">Kerjasama</span>
                    <h2 class="title">Kerjasama & Program Unggulan</h2>
                    <p class="desc">Kolaborasi dengan berbagai institusi untuk meningkatkan kualitas pendidikan</p>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
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
                    <div class="col-md-4 col-lg-3">
                        <div class="program-card text-center p-4 rounded shadow-sm h-100">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="mb-3"
                                    style="max-height: 80px;">
                            @else
                                <div class="program-icon mb-3">
                                    <i class="fas fa-handshake fa-3x text-primary"></i>
                                </div>
                            @endif
                            <h5>{{ $partner->name }}</h5>
                            @if (!empty($partner->description))
                                <p class="text-muted small">{{ Str::limit($partner->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                @php
                    $defaultPartners = [
                        ['name' => 'Axioo', 'icon' => 'fas fa-laptop-code'],
                        ['name' => 'GAMELAB', 'icon' => 'fas fa-gamepad'],
                        ['name' => 'Lab PLTS', 'icon' => 'fas fa-solar-panel'],
                        ['name' => 'Fiber Optik', 'icon' => 'fas fa-network-wired'],
                        ['name' => 'Studio Seje', 'icon' => 'fas fa-film'],
                    ];
                @endphp
                @foreach ($defaultPartners as $partner)
                    @php
                        $partnerLower = strtolower($partner['name']);
                        $defaultLogoUrl = null;
                        if (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $defaultLogoUrl = asset($assetPath);
                            }
                        }
                    @endphp
                    <div class="col-md-4 col-lg-3">
                        <div class="program-card text-center p-4 rounded shadow-sm h-100">
                            @if ($defaultLogoUrl)
                                <img src="{{ $defaultLogoUrl }}" alt="{{ $partner['name'] }}" class="mb-3"
                                    style="max-height: 80px;">
                            @else
                                <div class="program-icon mb-3">
                                    <i class="{{ $partner['icon'] }} fa-3x text-primary"></i>
                                </div>
                            @endif
                            <h5>{{ $partner['name'] }}</h5>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Programs End -->
