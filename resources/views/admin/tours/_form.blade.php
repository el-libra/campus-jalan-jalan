<div class="space-y-4">
    <label class="block text-sm">
        <span class="text-gray-700">Nama Tur</span>
        <input type="text" name="name" value="{{ old('name', $tour->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Deskripsi</span>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">{{ old('description', $tour->description ?? '') }}</textarea>
    </label>

    <div>
        <p class="text-sm font-semibold text-gray-700">Urutan Spot</p>
        <div class="mt-2 space-y-2">
            @foreach ($spots as $spot)
                @php
                    $checked = in_array($spot->id, old('spot_ids', $tour->spots->pluck('id')->all() ?? []), true);
                    $orderValue = old('order_index.' . $spot->id, $tour->spots->firstWhere('id', $spot->id)?->pivot?->order_index ?? 0);
                @endphp
                <label class="flex items-center justify-between gap-4 rounded-md border border-gray-200 px-3 py-2 text-sm">
                    <span class="flex items-center gap-2">
                        <input type="checkbox" name="spot_ids[]" value="{{ $spot->id }}" @checked($checked)>
                        {{ $spot->name }}
                    </span>
                    <input type="number" name="order_index[{{ $spot->id }}]" value="{{ $orderValue }}" class="w-20 rounded-md border-gray-300 text-sm shadow-sm">
                </label>
            @endforeach
        </div>
    </div>
</div>
