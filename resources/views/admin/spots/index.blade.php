<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spot Kampus') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Kelola spot kampus dan foto.</p>
                <a href="{{ route('admin.spots.create') }}" class="rounded-md bg-[color:var(--campus-blue)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[color:var(--campus-blue-deep)]">Tambah</a>
            </div>

            @if (session('status'))
                <div class="rounded-md bg-[color:var(--campus-sky)]/60 px-4 py-2 text-sm text-[color:var(--campus-blue-deep)]">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($spots as $spot)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $spot->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $spot->short_description }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $spot->category?->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs {{ $spot->is_active ? 'bg-[color:var(--campus-sky)] text-[color:var(--campus-blue-deep)]' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $spot->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.spots.edit', $spot) }}" class="text-[color:var(--campus-blue)] hover:underline">Edit</a>
                                    <form action="{{ route('admin.spots.destroy', $spot) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus spot ini?')">Hapus</button>
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
