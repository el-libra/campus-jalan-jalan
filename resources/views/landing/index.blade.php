@extends('layouts.site')

@section('title', 'Campus Tour USH')

@section('content')
    @php
        $spotData = $spots->map(function ($spot) {
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
                'category' => [
                    'name' => $spot->category?->name,
                    'slug' => $spot->category?->slug,
                    'icon' => $spot->category?->icon,
                ],
                'short_description' => $spot->short_description,
                'latitude' => (float) $spot->latitude,
                'longitude' => (float) $spot->longitude,
                'photo' => $photoUrl,
                'address' => $spot->address,
            ];
        });
    @endphp

    <div class="relative min-h-screen">
        <div id="map" class="h-screen w-full"></div>

        <div class="pointer-events-none absolute left-0 top-0 z-10 w-full p-4 md:p-6">
            <div class="pointer-events-auto mx-auto flex max-w-6xl animate-fade-up items-center justify-between gap-4 rounded-3xl border border-white/60 bg-white/80 px-5 py-4 shadow-lg backdrop-blur">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 overflow-hidden rounded-full border border-white/60 bg-white shadow-sm">
                        <img src="{{ asset('images/ush-logo.png') }}" alt="USH" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-[color:var(--campus-blue)]">Tour Campus USH</p>
                        <h1 class="text-lg font-semibold text-slate-900 md:text-2xl">Jelajahi kampus dengan suasana alami</h1>
                    </div>
                </div>
                <div class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('tour.index') }}" class="rounded-full bg-[color:var(--campus-blue)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[color:var(--campus-blue-deep)]">Mulai Tur</a>
                    <a href="https://maps.google.com" target="_blank" rel="noreferrer" class="rounded-full border border-[color:var(--campus-blue-soft)]/40 bg-white px-4 py-2 text-sm font-semibold text-[color:var(--campus-blue)] hover:bg-[color:var(--campus-sky)]/40">Buka di Google Maps</a>
                </div>
            </div>
        </div>

        <div class="pointer-events-none absolute bottom-0 left-0 z-10 w-full md:bottom-auto md:top-24 md:w-[420px] md:p-6">
            <div id="spot-sheet" data-open="true" class="pointer-events-auto sheet-panel w-full rounded-t-3xl border border-white/60 bg-white/90 p-4 shadow-2xl backdrop-blur md:rounded-3xl md:translate-y-0">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Spot Kampus</p>
                        <h2 class="text-xl font-semibold text-slate-900">Daftar Spot</h2>
                    </div>
                    <button id="toggle-sheet" class="md:hidden text-sm font-semibold text-[color:var(--campus-blue)]">Tutup</button>
                </div>

                <div class="space-y-3">
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Cari</span>
                        <input id="spot-search" type="text" placeholder="Cari nama spot..." class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:outline-none focus:ring-2 focus:ring-[color:var(--campus-blue-soft)]/30">
                    </label>

                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Kategori</span>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                                    <input type="checkbox" class="category-filter text-[color:var(--campus-blue)]" value="{{ $category->slug }}">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button id="start-tour" class="flex-1 rounded-2xl bg-[color:var(--campus-blue)] px-4 py-3 text-sm font-semibold text-white shadow transition hover:bg-[color:var(--campus-blue-deep)]">Mulai Tur</button>
                        <button id="nearby-spot" class="flex-1 rounded-2xl border border-[color:var(--campus-blue-soft)]/40 bg-white px-4 py-3 text-sm font-semibold text-[color:var(--campus-blue)] transition hover:bg-[color:var(--campus-sky)]/40">Spot Terdekat</button>
                        <a href="https://maps.google.com" target="_blank" rel="noreferrer" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Buka di Google Maps</a>
                    </div>

                    <div class="max-h-[40vh] space-y-3 overflow-y-auto pr-1 md:max-h-[55vh]" id="spot-list">
                        @foreach ($spots as $spot)
                            <button class="spot-item flex w-full items-start gap-3 rounded-2xl border border-transparent bg-white/70 p-3 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-[color:var(--campus-blue-soft)]/50" data-spot-id="{{ $spot->id }}" data-spot-slug="{{ $spot->slug }}" data-category="{{ $spot->category?->slug }}">
                                <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-2xl bg-[color:var(--campus-sky)]">
                                    @if ($spot->photos->first())
                                        @php
                                            $photoPath = $spot->photos->first()->photo_path;
                                            $photoUrl = str_starts_with($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath);
                                        @endphp
                                        <img src="{{ $photoUrl }}" alt="{{ $spot->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-xs text-[color:var(--campus-blue)]">USH</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $spot->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $spot->category?->name }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="preview-card" class="pointer-events-none absolute bottom-6 right-6 z-20 hidden w-80 rounded-3xl border border-white/60 bg-white/90 p-4 shadow-2xl backdrop-blur md:block">
            <div id="preview-image" class="h-40 w-full overflow-hidden rounded-2xl bg-[color:var(--campus-sky)]"></div>
            <div class="mt-3 space-y-2">
                <p id="preview-category" class="text-xs uppercase tracking-[0.2em] text-[color:var(--campus-blue)]"></p>
                <h3 id="preview-title" class="text-lg font-semibold text-slate-900"></h3>
                <p id="preview-description" class="text-sm text-slate-600"></p>
                <div class="flex gap-2 pt-2">
                    <a id="preview-detail" href="#" class="flex-1 rounded-2xl bg-[color:var(--campus-blue)] px-3 py-2 text-center text-sm font-semibold text-white">Lihat Detail</a>
                    <a id="preview-directions" href="#" target="_blank" rel="noreferrer" class="flex-1 rounded-2xl border border-[color:var(--campus-blue-soft)]/40 bg-white px-3 py-2 text-center text-sm font-semibold text-[color:var(--campus-blue)]">Arahkan</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const spotData = @json($spotData);
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap"
        defer
    ></script>
    <script>
        let map;
        const markers = new Map();
        let activeCategories = new Set();
        let sheetOpen = true;
        const colors = {
            gedung: '#1f4d7a',
            fasilitas: '#3a6ea5',
            parkir: '#2e5e4e',
            akademik: '#5b8fcb',
            layanan: '#7aa1d2',
        };

        function initMap() {
            const defaultCenter = { lat: -7.5990389, lng: 110.8140527 };
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultCenter,
                zoom: 18,
                mapTypeId: 'satellite',
                streetViewControl: false,
                fullscreenControl: true,
            });

            updateCategoryFilter();
            renderMarkers();

            map.addListener('idle', () => {
                renderMarkers();
            });
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

        function renderMarkers() {
            const bounds = map.getBounds();
            if (!bounds) return;

            spotData.forEach((spot) => {
                const position = { lat: spot.latitude, lng: spot.longitude };
                if (!activeCategories.has(spot.category.slug)) {
                    return;
                }
                if (!bounds.contains(position)) {
                    return;
                }

                if (markers.has(spot.id)) {
                    return;
                }

                const color = colors[spot.category.slug] || '#1f4d7a';
                const marker = new google.maps.Marker({
                    map,
                    position,
                    title: spot.name,
                    icon: markerIcon(color),
                });

                marker.addListener('click', () => {
                    focusSpot(spot.id, true);
                });

                markers.set(spot.id, marker);
            });
        }

        function focusSpot(spotId, openPreview) {
            const spot = spotData.find((item) => item.id === spotId);
            if (!spot) return;

            map.panTo({ lat: spot.latitude, lng: spot.longitude });
            map.setZoom(19);

            if (openPreview) {
                showPreview(spot);
            }
        }

        function showPreview(spot) {
            const preview = document.getElementById('preview-card');
            preview.classList.remove('hidden');
            preview.classList.remove('animate-fade-up');
            void preview.offsetWidth;
            preview.classList.add('animate-fade-up');

            document.getElementById('preview-title').textContent = spot.name;
            document.getElementById('preview-category').textContent = spot.category.name || '';
            document.getElementById('preview-description').textContent = spot.short_description;
            document.getElementById('preview-detail').href = `/spots/${spot.slug}`;
            document.getElementById('preview-directions').href = `https://www.google.com/maps/search/?api=1&query=${spot.latitude},${spot.longitude}`;

            const previewImage = document.getElementById('preview-image');
            previewImage.innerHTML = '';
            if (spot.photo) {
                const img = document.createElement('img');
                img.src = spot.photo;
                img.alt = spot.name;
                img.className = 'h-full w-full object-cover';
                previewImage.appendChild(img);
            } else {
                const fallback = document.createElement('div');
                fallback.className = 'flex h-full w-full items-center justify-center text-sm text-[color:var(--campus-blue)]';
                fallback.textContent = 'USH';
                previewImage.appendChild(fallback);
            }
        }

        document.getElementById('spot-list').addEventListener('click', (event) => {
            const button = event.target.closest('.spot-item');
            if (!button) return;
            const spotId = Number(button.dataset.spotId);
            focusSpot(spotId, true);
        });

        document.getElementById('spot-search').addEventListener('input', (event) => {
            const query = event.target.value.toLowerCase();
            document.querySelectorAll('.spot-item').forEach((item) => {
                const matchesText = item.textContent.toLowerCase().includes(query);
                item.classList.toggle('hidden', !matchesText);
            });
        });

        function updateCategoryFilter() {
            activeCategories = new Set(
                Array.from(document.querySelectorAll('.category-filter'))
                    .filter((input) => input.checked)
                    .map((input) => input.value)
            );

            document.querySelectorAll('.spot-item').forEach((item) => {
                const category = item.dataset.category;
                item.classList.toggle('hidden', !activeCategories.has(category));
            });

            markers.forEach((marker, spotId) => {
                const spot = spotData.find((item) => item.id === spotId);
                if (!spot) return;
                marker.setMap(activeCategories.has(spot.category.slug) ? map : null);
            });
        }

        document.querySelectorAll('.category-filter').forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                updateCategoryFilter();
            });
        });

        document.getElementById('start-tour').addEventListener('click', () => {
            window.location.href = '{{ route('tour.index') }}';
        });

        document.getElementById('nearby-spot').addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Geolocation tidak tersedia di browser ini.');
                return;
            }

            navigator.geolocation.getCurrentPosition((position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                let nearest = null;
                let nearestDistance = Number.POSITIVE_INFINITY;

                spotData.forEach((spot) => {
                    const distance = Math.hypot(userLat - spot.latitude, userLng - spot.longitude);
                    if (distance < nearestDistance) {
                        nearestDistance = distance;
                        nearest = spot;
                    }
                });

                if (nearest) {
                    focusSpot(nearest.id, true);
                }
            });
        });

        const toggleButton = document.getElementById('toggle-sheet');
        const sheetPanel = document.getElementById('spot-sheet');
        if (toggleButton && sheetPanel) {
            toggleButton.addEventListener('click', () => {
                sheetOpen = sheetPanel.dataset.open === 'true';
                sheetPanel.dataset.open = sheetOpen ? 'false' : 'true';
                toggleButton.textContent = sheetOpen ? 'Buka' : 'Tutup';
            });
        }
    </script>
@endpush
