<div class="grid gap-4 md:grid-cols-2">
    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Kategori</span>
        <select name="category_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $spot->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Nama Spot</span>
        <input type="text" name="name" value="{{ old('name', $spot->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Deskripsi Singkat</span>
        <input type="text" name="short_description" value="{{ old('short_description', $spot->short_description ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Deskripsi Lengkap</span>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">{{ old('description', $spot->description ?? '') }}</textarea>
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Latitude</span>
        <input type="text" name="latitude" value="{{ old('latitude', $spot->latitude ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Longitude</span>
        <input type="text" name="longitude" value="{{ old('longitude', $spot->longitude ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Alamat</span>
        <input type="text" name="address" value="{{ old('address', $spot->address ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Jam Buka</span>
        <input type="time" name="open_time" value="{{ old('open_time', $spot->open_time ? $spot->open_time->format('H:i') : '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Jam Tutup</span>
        <input type="time" name="close_time" value="{{ old('close_time', $spot->close_time ? $spot->close_time->format('H:i') : '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <div class="md:col-span-2 grid gap-2 md:grid-cols-3 text-sm">
        <label class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2">
            <input type="checkbox" name="has_wifi" value="1" @checked(old('has_wifi', $spot->has_wifi ?? false))>
            <span>WiFi</span>
        </label>
        <label class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2">
            <input type="checkbox" name="has_toilet" value="1" @checked(old('has_toilet', $spot->has_toilet ?? false))>
            <span>Toilet</span>
        </label>
        <label class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2">
            <input type="checkbox" name="is_wheelchair_accessible" value="1" @checked(old('is_wheelchair_accessible', $spot->is_wheelchair_accessible ?? false))>
            <span>Akses kursi roda</span>
        </label>
    </div>

    <label class="block text-sm md:col-span-2">
        <span class="text-gray-700">Upload Foto (bisa multiple)</span>
        <input type="file" name="photos[]" multiple class="mt-1 w-full text-sm">
    </label>

    <label class="flex items-center gap-2 text-sm md:col-span-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $spot->is_active ?? true))>
        <span>Aktif</span>
    </label>
</div>

@if (!empty($spot) && $spot->photos->isNotEmpty())
    <div class="mt-4">
        <p class="text-sm font-semibold text-gray-700">Foto Saat Ini (centang untuk hapus)</p>
        <div class="mt-2 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach ($spot->photos as $photo)
                @php
                    $photoUrl = str_starts_with($photo->photo_path, 'http')
                        ? $photo->photo_path
                        : asset('storage/' . $photo->photo_path);
                @endphp
                <label class="block rounded-lg border border-gray-200 p-2 text-xs text-gray-600">
                    <img src="{{ $photoUrl }}" alt="{{ $spot->name }}" class="h-24 w-full rounded-md object-cover">
                    <div class="mt-2 flex items-center gap-2">
                        <input type="checkbox" name="remove_photo_ids[]" value="{{ $photo->id }}">
                        <span>Hapus</span>
                    </div>
                </label>
            @endforeach
        </div>
    </div>
@endif
