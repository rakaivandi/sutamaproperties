<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Properti Saya</h2>
            <a href="{{ route('agen.properties.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                + Tambah Properti
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- STATS RINGKAS --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $properties->total() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Properti</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $properties->where('is_approved', true)->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Sudah Approved</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-500">{{ $properties->where('is_approved', false)->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu Approval</p>
                </div>
            </div>

            {{-- LIST PROPERTI --}}
            @if($properties->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
                    <p class="text-4xl mb-3">🏠</p>
                    <p class="text-gray-500 font-medium">Belum ada properti</p>
                    <p class="text-gray-400 text-sm mt-1">Mulai tambahkan properti pertamamu</p>
                    <a href="{{ route('agen.properties.create') }}"
                       class="inline-block mt-4 px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        + Tambah Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($properties as $p)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">

                        {{-- Cover foto --}}
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                            @if($p->cover)
                                <img src="{{ asset('storage/'.$p->cover->path) }}"
                                     alt="{{ $p->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">🏠</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $p->title }}</h3>
                                {{-- Badge status listing --}}
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $p->status === 'dijual' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                                {{-- Badge tipe --}}
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                    {{ ucfirst($p->type) }}
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mt-0.5">📍 {{ $p->city }} · {{ $p->address }}</p>
                            <p class="text-blue-600 font-bold text-sm mt-1">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                                @if($p->status === 'disewakan')<span class="font-normal text-gray-400">/bulan</span>@endif
                            </p>
                        </div>

                        {{-- Approval badge + aksi --}}
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            @if($p->is_approved)
                                <span class="text-xs px-2 py-1 bg-green-50 text-green-600 border border-green-200 rounded-full">
                                    ✓ Approved
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 bg-yellow-50 text-yellow-600 border border-yellow-200 rounded-full">
                                    ⏳ Pending
                                </span>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('agen.properties.edit', $p) }}"
                                   class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('agen.properties.destroy', $p) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus properti ini?')"
                                            class="text-xs px-3 py-1.5 border border-red-200 rounded-lg text-red-500 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">{{ $properties->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
