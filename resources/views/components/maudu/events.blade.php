<!-- Events / Kegiatan -->
@props(['events' => []])

<div class="events-area py-120">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="site-heading">
                    <span class="sub-title">Kegiatan</span>
                    <h2 class="title">Kegiatan Terkini</h2>
                    <p class="desc">Berbagai kegiatan dan acara menarik di {{ theme_config('short_name', 'MAUDU') }}
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="events-list">
                    @if (count($events) > 0)
                        @foreach ($events->take(3) as $event)
                            <div class="event-item mb-4 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.15 . 's' }}">
                                <div class="d-flex align-items-center">
                                    <div class="event-date me-4 text-center">
                                        <span class="date-month d-block bg-primary text-white rounded px-2 py-1 small">
                                            {{ \Carbon\Carbon::parse($event->date ?? $event->created_at)->format('M') }}
                                        </span>
                                        <span class="date-day d-block h4 mb-0 mt-1">
                                            {{ \Carbon\Carbon::parse($event->date ?? $event->created_at)->format('d') }}
                                        </span>
                                    </div>
                                    <div class="event-content">
                                        <h5 class="event-title mb-1">
                                            <a href="{{ route('public.kegiatan') }}">{{ $event->title }}</a>
                                        </h5>
                                        <span class="event-category text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $event->category ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @php
                            $defaultEvents = [
                                [
                                    'title' => 'KOMPASS - Kompetisi Agama, Sains, dan Seni',
                                    'category' => 'Kompetisi',
                                    'date' => now()->subDays(5),
                                ],
                                [
                                    'title' => 'MHW - Madrasah Humanitarian Week',
                                    'category' => 'Kegiatan Sosial',
                                    'date' => now()->subDays(10),
                                ],
                                [
                                    'title' => 'MAUDUFEST - Festival Budaya dan Seni',
                                    'category' => 'Festival',
                                    'date' => now()->subDays(15),
                                ],
                            ];
                        @endphp
                        @foreach ($defaultEvents as $index => $event)
                            <div class="event-item mb-4 wow fadeInUp" data-wow-delay="{{ $index * 0.15 . 's' }}">
                                <div class="d-flex align-items-center">
                                    <div class="event-date me-4 text-center">
                                        <span class="date-month d-block bg-primary text-white rounded px-2 py-1 small">
                                            {{ \Carbon\Carbon::parse($event['date'])->format('M') }}
                                        </span>
                                        <span class="date-day d-block h4 mb-0 mt-1">
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                                        </span>
                                    </div>
                                    <div class="event-content">
                                        <h5 class="event-title mb-1">
                                            <a href="{{ route('public.kegiatan') }}">{{ $event['title'] }}</a>
                                        </h5>
                                        <span class="event-category text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $event['category'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Events End -->
