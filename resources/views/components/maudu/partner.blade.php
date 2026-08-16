<!-- Partner Area -->
@props(['partners' => []])

<div class="partner-area bg pt-50 pb-50">
    <div class="container">
        <div class="partner-wrapper partner-slider owl-carousel owl-theme">
            @php
                // Filter DB partners — only show those with valid uploaded logos
                $validPartners = $partners->filter(fn($p) => !empty($p->logo) && Storage::disk('public')->exists($p->logo));
            @endphp
            @if ($validPartners->count() > 0)
                @foreach ($validPartners as $partner)
                    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}">
                @endforeach
            @else
                {{-- Default: show partner images 01-10 matching template exactly --}}
                @for ($i = 1; $i <= 10; $i++)
                    @php
                        $num = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                    @endphp
                    @if (file_exists(public_path($assetPath)))
                        <img src="{{ asset($assetPath) }}" alt="Partner {{ $num }}">
                    @endif
                @endfor
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
