@extends('layouts.site')

@section('title', $spot->name)

@section('content')
    @php
        $photoUrls = $spot->photos->map(function ($photo) {
            return str_starts_with($photo->photo_path, 'http')
                ? $photo->photo_path
                : asset('storage/' . $photo->photo_path);
        });
    @endphp

    <div class="mx-auto max-w-6xl px-4 pb-16 pt-10 md:px-8">
        <a href="{{ route('landing') }}" class="text-sm font-semibold text-[color:var(--campus-blue)] hover:text-[color:var(--campus-blue-deep)]">&larr; Kembali ke peta</a>

        <div class="mt-6 grid gap-8 lg:grid-cols-[1.2fr_0.8fr] animate-fade-up">
            <div class="space-y-6">
                <div class="rounded-3xl border border-white/60 bg-white/80 p-4 shadow-xl backdrop-blur">
                    <div class="relative overflow-hidden rounded-2xl bg-[color:var(--campus-sky)]">
                        @if ($photoUrls->isNotEmpty())
                            <div id="gallery" class="relative h-72 sm:h-96">
                                @foreach ($photoUrls as $index => $url)
                                    <img src="{{ $url }}" alt="{{ $spot->name }}" class="gallery-slide absolute inset-0 h-full w-full object-cover {{ $index === 0 ? '' : 'hidden' }}">
                                @endforeach
                                <button id="prev-slide" class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 px-3 py-2 text-sm font-semibold text-slate-800 shadow">Prev</button>
                                <button id="next-slide" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 px-3 py-2 text-sm font-semibold text-slate-800 shadow">Next</button>
                            </div>
                        @else
                            <div class="flex h-72 items-center justify-center text-[color:var(--campus-blue)]">Belum ada foto</div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-[color:var(--campus-blue)]">{{ $spot->category?->name }}</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $spot->name }}</h1>
                    <p class="mt-4 text-sm text-slate-700 leading-relaxed">{{ $spot->description }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl backdrop-blur">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div class="flex items-center justify-between">
                            <span>Jam Operasional</span>
                            <span class="font-semibold">
                                {{ $spot->open_time ? $spot->open_time->format('H:i') : '--:--' }} - {{ $spot->close_time ? $spot->close_time->format('H:i') : '--:--' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Alamat</span>
                            <span class="font-semibold text-right">{{ $spot->address ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Koordinat</span>
                            <span class="font-semibold">{{ $spot->latitude }}, {{ $spot->longitude }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl backdrop-blur">
                    <h2 class="text-lg font-semibold text-slate-900">Fasilitas</h2>
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        <div class="flex items-center justify-between">
                            <span>WiFi</span>
                            <span class="font-semibold">{{ $spot->has_wifi ? 'Tersedia' : 'Tidak tersedia' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Toilet</span>
                            <span class="font-semibold">{{ $spot->has_toilet ? 'Tersedia' : 'Tidak tersedia' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Akses kursi roda</span>
                            <span class="font-semibold">{{ $spot->is_wheelchair_accessible ? 'Tersedia' : 'Tidak tersedia' }}</span>
                        </div>
                    </div>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query={{ $spot->latitude }},{{ $spot->longitude }}" target="_blank" rel="noreferrer" class="block rounded-3xl bg-[color:var(--campus-blue)] px-5 py-4 text-center text-sm font-semibold text-white shadow hover:bg-[color:var(--campus-blue-deep)]">
                    Buka di Google Maps
                </a>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-xl font-semibold text-slate-900">Spot terkait</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($relatedSpots as $related)
                    <a href="{{ route('spots.show', $related->slug) }}" class="rounded-3xl border border-white/60 bg-white/80 p-4 shadow-lg backdrop-blur transition hover:-translate-y-1">
                        <p class="text-sm font-semibold text-slate-900">{{ $related->name }}</p>
                        <p class="text-xs text-slate-500">{{ $related->short_description }}</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-600">Belum ada spot terkait.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const slides = Array.from(document.querySelectorAll('.gallery-slide'));
        let currentSlide = 0;
        const showSlide = (index) => {
            slides.forEach((slide, idx) => {
                slide.classList.toggle('hidden', idx !== index);
            });
        };

        const nextButton = document.getElementById('next-slide');
        const prevButton = document.getElementById('prev-slide');

        if (nextButton && prevButton) {
            nextButton.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            });

            prevButton.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            });
        }
    </script>
@endpush
