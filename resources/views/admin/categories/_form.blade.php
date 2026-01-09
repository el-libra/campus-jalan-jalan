<div class="space-y-4">
    <label class="block text-sm">
        <span class="text-gray-700">Nama</span>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>

    <label class="block text-sm">
        <span class="text-gray-700">Icon (opsional)</span>
        <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--campus-blue-soft)] focus:ring focus:ring-[color:var(--campus-blue-soft)]/30">
    </label>
</div>
