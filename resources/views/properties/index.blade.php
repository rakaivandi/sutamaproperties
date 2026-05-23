<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Cari Properti</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">

            {{-- HERO SEARCH --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl p-8 mb-8 text-white">
                <h1 class="text-2xl font-bold mb-1">Temukan Properti Impianmu</h1>
                <p class="text-blue-100 text-sm mb-6">Jual, beli, dan sewa properti terpercaya di seluruh Indonesia</p>

                <form method="GET" action="{{ route('properties.index') }}">
                    <div class="flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari judul, alamat, atau kota..."
                               class="flex-1 px-4 py-2.5 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white">
                        <button type="submit"
                                class="px-5 py-2.5 bg-white text-blue-600 font-semibold rounded-lg text-sm hover:bg-blue-50">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex gap-6">

                {{-- SIDEBAR FILTER --}}
                <aside class="w-56 flex-shrink-0">
                    <form method="GET" action="{{ route('properties.index') }}" id="filterForm">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 space-y-5">
                            <h3 class="font-semibold text-gray-700 text-sm">Filter</h3>

                            {{-- Tipe --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Tipe</p>
                                @foreach(['rumah','apartemen','tanah','ruko','villa'] as $t)
                                <label class="flex items-center gap-2 text-sm text-gray-600 mb-1.5 cursor-pointer">
                                    <input type="radio" name="type" value="{{ $t }}"
                                           {{ request('type')===$t ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           class="text-blue-600">
                                    {{ ucfirst($t) }}
                                </label>
                                @endforeach
                                @if(request('type'))
                                <a href="{{ request()->fullUrlWithoutQuery('type') }}"
                                   class="text-xs text-blue-500 hover:underline">Hapus filter</a>
                                @endif
                            </div>

                            {{-- Status --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Status</p>
                                @foreach(['dijual' => 'Dijual', 'disewakan' => 'Disewakan'] as $val => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-600 mb-1.5 cursor-pointer">
                                    <input type="radio" name="status" value="{{ $val }}"
                                           {{ request('status')===$val ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           class="text-blue-600">
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>

                            {{-- Kota --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Kota</p>
                                <select name="city"
                                        onchange="document.getElementById('filterForm').submit()"
                                        class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-gray-600 focus:outline-none">
                                    <option value="">Semua Kota</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city')===$city?'selected':'' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kamar tidur --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Min. Kamar Tidur</p>
                                <select name="bedrooms"
                                        onchange="document.getElementById('filterForm').submit()"
                                        class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-gray-600 focus:outline-none">
                                    <option value="">Semua</option>
                                    @foreach([1,2,3,4,5] as $b)
                                        <option value="{{ $b }}" {{ request('bedrooms')==$b?'selected':'' }}>
                                            {{ $b }}+ Kamar
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Harga --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Rentang Harga</p>
                                <input type="number" name="price_min" value="{{ request('price_min') }}"
                                       placeholder="Min (Rp)"
                                       class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm mb-2 focus:outline-none">
                                <input type="number" name="price_max" value="{{ request('price_max') }}"
                                       placeholder="Max (Rp)"
                                       class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none">
                                <button type="submit"
                                        class="mt-2 w-full py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">
                                    Terapkan
                                </button>
                            </div>

                            {{-- Reset --}}
                            @if(request()->hasAny(['q','type','status','city','bedrooms','price_min','price_max']))
                            <a href="{{ route('properties.index') }}"
                               class="block text-center text-xs text-red-500 hover:underline pt-1">
                                🗑 Reset semua filter
                            </a>
                            @endif
                        </div>
                    </form>
                </aside>

                {{-- KONTEN UTAMA --}}
                <div class="flex-1 min-w-0">

                    {{-- Toolbar --}}
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-800">{{ $properties->total() }}</span> properti ditemukan
                            @if(request('q')) untuk "<span class="font-medium">{{ request('q') }}</span>" @endif
                        </p>
                        <select name="sort" form="filterForm"
                                onchange="document.getElementById('filterForm').submit()"
                                class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-600 focus:outline-none">
                            <option value="latest" {{ request('sort','latest')==='latest'?'selected':'' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>Harga Tertinggi</option>
                        </select>
                    </div>

                    {{-- Grid properti --}}
                    @if($properties->isEmpty())
                        <div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
                            <p class="text-4xl mb-3">🔍</p>
                            <p class="text-gray-500 font-medium">Tidak ada properti ditemukan</p>
                            <p class="text-gray-400 text-sm mt-1">Coba ubah atau reset filter pencarian</p>
                            <a href="{{ route('properties.index') }}"
                               class="inline-block mt-4 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                                Reset Filter
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($properties as $p)
                            <a href="{{ route('properties.show', $p) }}"
                               class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden block group">

                                {{-- Foto --}}
                                <div class="relative h-44 bg-gray-100 overflow-hidden">
                                    @if($p->cover)
                                        <img src="{{ asset('storage/'.$p->cover->path) }}"
                                             alt="{{ $p->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">🏠</div>
                                    @endif

                                    {{-- Badge status --}}
                                    <span class="absolute top-3 left-3 text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $p->status === 'dijual' ? 'bg-blue-600 text-white' : 'bg-green-600 text-white' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </div>

                                {{-- Info --}}
                                <div class="p-4">
                                    <p class="text-xs text-gray-400 mb-1">{{ ucfirst($p->type) }} · {{ $p->city }}</p>
                                    <h3 class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 mb-2">
                                        {{ $p->title }}
                                    </h3>

                                    {{-- Fasilitas --}}
                                    <div class="flex gap-3 text-xs text-gray-400 mb-3">
                                        @if($p->bedrooms)  <span>🛏 {{ $p->bedrooms }}</span> @endif
                                        @if($p->bathrooms) <span>🚿 {{ $p->bathrooms }}</span> @endif
                                        @if($p->land_area) <span>📐 {{ $p->land_area }}m²</span> @endif
                                    </div>

                                    <p class="text-blue-600 font-bold text-sm">
                                        Rp {{ number_format($p->price, 0, ',', '.') }}
                                        @if($p->status === 'disewakan')
                                            <span class="font-normal text-gray-400 text-xs">/bulan</span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">{{ $properties->links() }}</div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
