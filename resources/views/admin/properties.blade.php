<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Kelola Properti</h2>
            <span class="text-sm text-gray-400">Total: {{ $properties->total() }} properti</span>
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

            {{-- STATS --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $properties->total() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $properties->where('is_approved', true)->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Approved</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-500">{{ $properties->where('is_approved', false)->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Pending</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $properties->where('status', 'dijual')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Dijual</p>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Header tabel --}}
                <div class="grid grid-cols-12 gap-4 px-5 py-3 bg-gray-50 border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wide">
                    <div class="col-span-1">Foto</div>
                    <div class="col-span-4">Properti</div>
                    <div class="col-span-2">Agen</div>
                    <div class="col-span-2">Harga</div>
                    <div class="col-span-1">Tipe</div>
                    <div class="col-span-1">Status</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                {{-- Rows --}}
                @forelse($properties as $p)
                <div class="grid grid-cols-12 gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition items-center">

                    {{-- Foto --}}
                    <div class="col-span-1">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($p->cover)
                                <img src="{{ asset('storage/'.$p->cover->path) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">🏠</div>
                            @endif
                        </div>
                    </div>

                    {{-- Nama properti --}}
                    <div class="col-span-4">
                        <p class="font-medium text-gray-800 text-sm truncate">{{ $p->title }}</p>
                        <p class="text-gray-400 text-xs mt-0.5 truncate">📍 {{ $p->city }}{{ $p->province ? ', '.$p->province : '' }}</p>
                        <div class="flex gap-1 mt-1">
                            @if($p->bedrooms) <span class="text-xs text-gray-400">🛏 {{ $p->bedrooms }}</span> @endif
                            @if($p->bathrooms) <span class="text-xs text-gray-400">🚿 {{ $p->bathrooms }}</span> @endif
                            @if($p->land_area) <span class="text-xs text-gray-400">📐 {{ $p->land_area }}m²</span> @endif
                        </div>
                    </div>

                    {{-- Agen --}}
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 truncate">{{ $p->owner->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $p->owner->email }}</p>
                    </div>

                    {{-- Harga --}}
                    <div class="col-span-2">
                        <p class="text-sm font-semibold text-blue-600">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </p>
                        @if($p->price_monthly)
                            <p class="text-xs text-gray-400">Sewa: Rp {{ number_format($p->price_monthly, 0, ',', '.') }}/bln</p>
                        @endif
                    </div>

                    {{-- Tipe --}}
                    <div class="col-span-1">
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full">
                            {{ ucfirst($p->type) }}
                        </span>
                    </div>

                    {{-- Approval status --}}
                    <div class="col-span-1">
                        @if($p->is_approved)
                            <span class="text-xs px-2 py-1 bg-green-50 text-green-600 border border-green-200 rounded-full whitespace-nowrap">
                                ✓ Live
                            </span>
                        @else
                            <span class="text-xs px-2 py-1 bg-yellow-50 text-yellow-600 border border-yellow-200 rounded-full whitespace-nowrap">
                                ⏳ Pending
                            </span>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="col-span-1 flex justify-center">
                        <form method="POST" action="{{ route('admin.properties.approve', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium transition
                                        {{ $p->is_approved
                                            ? 'bg-red-50 text-red-500 border border-red-200 hover:bg-red-100'
                                            : 'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100' }}">
                                {{ $p->is_approved ? 'Tolak' : 'Approve' }}
                            </button>
                        </form>
                    </div>

                </div>
                @empty
                <div class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">📋</p>
                    <p class="text-gray-500 font-medium">Belum ada properti</p>
                    <p class="text-gray-400 text-sm mt-1">Properti dari agen akan muncul di sini</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-5">{{ $properties->links() }}</div>

        </div>
    </div>
</x-app-layout>
