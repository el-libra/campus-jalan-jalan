<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tur') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                <form method="POST" action="{{ route('admin.tours.update', $tour) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    @include('admin.tours._form', ['tour' => $tour, 'spots' => $spots])
                    <button type="submit" class="rounded-md bg-[color:var(--campus-blue)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[color:var(--campus-blue-deep)]">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
