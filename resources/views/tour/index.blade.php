@extends('layouts.site')

@section('title', 'Guided Tour USH')

@section('content')
    @php
        $tourData = $tours->map(function ($tour) {
            return [
                'id' => $tour->id,
                'name' => $tour->name,
                'slug' => $tour->slug,
                'description' => $tour->description,
                'spots' => $tour->spots->map(function ($spot) {
                    $photo = $spot->photos->first();
                    $photoUrl = $photo
                        ? (str_starts_with($photo->photo_path, 'http')
                            ? $photo->photo_path
                            : asset('storage/' . $photo->photo_path))
                        : null;

                    return [
                        'id' => $spot->id,
                        'name' => $spot->name,
                        'slug' => $spot->slug,
                        'latitude' => (float) $spot->latitude,
                        'longitude' => (float) $spot->longitude,
                        'category' => $spot->category?->name,
                        'category_slug' => $spot->category?->slug,
                        'photo' => $photoUrl,
                        'short_description' => $spot->short_description,
                        'order_index' => $spot->pivot->order_index ?? 0,
                    ];
                })->sortBy('order_index')->values(),
            ];
        });
    @endphp

    <div class="min-h-screen">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 md:px-8 lg:flex-row">
            <div class="w-full animate-fade-up lg:w-[380px]">
                <a href="{{ route('landing') }}" class="text-sm font-semibold text-[color:var(--campus-blue)] hover:text-[color:var(--campus-blue-deep)]">&larr; Kembali ke peta</a>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Guided Tour</h1>
                <p class="mt-2 text-sm text-slate-600">Pilih tur, ikuti urutan spot, dan jelajahi kampus seperti ditemani pemandu.</p>

                <div class="mt-6 space-y-3" id="tour-list"></div>

                <div class="mt-6 rounded-3xl border border-white/60 bg-white/80 p-4 shadow-xl backdrop-blur">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Urutan Spot</h2>
                        <div class="flex gap-2">
                            <button id="prev-step" class="rounded-full border border-[color:var(--campus-blue-soft)]/40 bg-white px-3 py-1 text-xs font-semibold text-[color:var(--campus-blue)]">Prev</button>
                            <button id="next-step" class="rounded-full bg-[color:var(--campus-blue)] px-3 py-1 text-xs font-semibold text-white">Next</button>
                        </div>
                    </div>
                    <div class="mt-4 space-y-3" id="tour-steps"></div>
                </div>
            </div>

            <div class="flex-1 animate-fade-up">
                <div class="overflow-hidden rounded-3xl border border-white/60 bg-white/80 shadow-xl backdrop-blur">
                    <div id="tour-map" class="h-[60vh] w-full md:h-[70vh]"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tourData = @json($tourData);
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initTourMap"
        defer
    ></script>
    <script>
        let tourMap;
        let activeTour = null;
        let activeIndex = 0;
        let markers = [];
        let routeLine = null;
        const colors = {
            gedung: '#1f4d7a',
            fasilitas: '#3a6ea5',
            parkir: '#2e5e4e',
            akademik: '#5b8fcb',
            layanan: '#7aa1d2',
        };

        function initTourMap() {
            tourMap = new google.maps.Map(document.getElementById('tour-map'), {
                center: { lat: -7.5990389, lng: 110.8140527 },
                zoom: 17,
                mapTypeId: 'satellite',
                streetViewControl: false,
                fullscreenControl: true,
            });

            renderTourList();
            if (tourData.length) {
                setActiveTour(tourData[0]);
            }
        }

        function markerIcon(color) {
            const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                    <path fill="${color}" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                    <circle cx="12" cy="9" r="3.2" fill="#ffffff"/>
                </svg>
            `;
            return {
                url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`,
                scaledSize: new google.maps.Size(36, 36),
                anchor: new google.maps.Point(18, 34),
            };
        }

        function renderTourList() {
            const container = document.getElementById('tour-list');
            container.innerHTML = '';

            tourData.forEach((tour) => {
                const button = document.createElement('button');
                button.className = 'w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left shadow-sm hover:border-[color:var(--campus-blue-soft)]/50';
                button.innerHTML = `
                    <p class="text-sm font-semibold text-slate-900">${tour.name}</p>
                    <p class="text-xs text-slate-500">${tour.description || 'Tour kampus pilihan.'}</p>
                `;
                button.addEventListener('click', () => setActiveTour(tour));
                container.appendChild(button);
            });
        }

        function setActiveTour(tour) {
            activeTour = tour;
            activeIndex = 0;
            renderSteps();
            renderMarkers();
            focusActiveSpot();
        }

        function renderSteps() {
            const steps = document.getElementById('tour-steps');
            steps.innerHTML = '';

            if (!activeTour || !activeTour.spots.length) {
                steps.innerHTML = '<p class="text-sm text-slate-600">Belum ada spot untuk tur ini.</p>';
                return;
            }

            activeTour.spots.forEach((spot, index) => {
                const card = document.createElement('div');
                card.className = 'rounded-2xl border border-transparent bg-white/70 p-3 shadow-sm';
                if (index === activeIndex) {
                    card.classList.add('border-[color:var(--campus-blue-soft)]/60');
                }
                card.innerHTML = `
                    <p class="text-xs uppercase tracking-[0.2em] text-[color:var(--campus-blue)]">${spot.category || ''}</p>
                    <p class="text-sm font-semibold text-slate-900">${spot.name}</p>
                    <p class="text-xs text-slate-600">${spot.short_description || ''}</p>
                `;
                card.addEventListener('click', () => {
                    activeIndex = index;
                    focusActiveSpot();
                });
                steps.appendChild(card);
            });
        }

        function renderMarkers() {
            markers.forEach((marker) => marker.setMap(null));
            markers = [];
            if (routeLine) {
                routeLine.setMap(null);
                routeLine = null;
            }

            if (!activeTour || !activeTour.spots.length) {
                return;
            }

            const path = [];

            activeTour.spots.forEach((spot, index) => {
                const position = { lat: spot.latitude, lng: spot.longitude };
                path.push(position);
                const color = colors[spot.category_slug] || '#1f4d7a';
                const marker = new google.maps.Marker({
                    map: tourMap,
                    position,
                    title: spot.name,
                    label: `${index + 1}`,
                    icon: markerIcon(color),
                });
                markers.push(marker);
            });

            routeLine = new google.maps.Polyline({
                path,
                geodesic: true,
                strokeColor: '#1f4d7a',
                strokeOpacity: 0.7,
                strokeWeight: 3,
            });
            routeLine.setMap(tourMap);
        }

        function focusActiveSpot() {
            if (!activeTour || !activeTour.spots.length) return;

            const spot = activeTour.spots[activeIndex];
            tourMap.panTo({ lat: spot.latitude, lng: spot.longitude });
            tourMap.setZoom(18);

            markers.forEach((marker, index) => {
                marker.setAnimation(null);
                if (index === activeIndex) {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => marker.setAnimation(null), 1400);
                }
            });

            renderSteps();
        }

        document.getElementById('next-step').addEventListener('click', () => {
            if (!activeTour || !activeTour.spots.length) return;
            activeIndex = (activeIndex + 1) % activeTour.spots.length;
            focusActiveSpot();
        });

        document.getElementById('prev-step').addEventListener('click', () => {
            if (!activeTour || !activeTour.spots.length) return;
            activeIndex = (activeIndex - 1 + activeTour.spots.length) % activeTour.spots.length;
            focusActiveSpot();
        });
    </script>
@endpush
