<!-- Counter Area -->
<div class="counter-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            @php
                $counters = [
                    ['number' => '24', 'label' => 'Mata Pelajaran', 'icon' => 'fas fa-book'],
                    ['number' => $siswaCount ?? '800', 'label' => 'Peserta Didik', 'icon' => 'fas fa-user-graduate'],
                    ['number' => '98', 'label' => 'Tenaga Pendidik', 'icon' => 'fas fa-chalkboard-teacher'],
                ];
            @endphp

            @foreach ($counters as $counter)
                <div class="col-lg-4 col-sm-6">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="{{ $counter['icon'] }}"></i>
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="{{ $counter['number'] }}">
                                    {{ $counter['number'] }}
                                </span>
                                @if (strpos($counter['number'], '+') === false && $counter['number'] !== ($siswaCount ?? '800'))
                                    <span class="suffix">+</span>
                                @endif
                            </div>
                            <h4 class="counter-title">{{ $counter['label'] }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Counter Area End -->

@push('scripts')
    <script>
        // Counter animation will be handled by counter-up.js
    </script>
@endpush
