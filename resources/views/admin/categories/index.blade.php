<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Kelola kategori spot kampus.</p>
                <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-[color:var(--campus-blue)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[color:var(--campus-blue-deep)]">Tambah</a>
            </div>

            @if (session('status'))
                <div class="rounded-md bg-[color:var(--campus-sky)]/60 px-4 py-2 text-sm text-[color:var(--campus-blue-deep)]">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Slug</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Icon</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-4 py-3">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $category->slug }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $category->icon ?? '-' }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-[color:var(--campus-blue)] hover:underline">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
