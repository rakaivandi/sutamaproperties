<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('properties.index') }}" class="hover:text-blue-600">Properti</a>
            <span>/</span>
            <span class="text-gray-600 truncate">{{ $property->title }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- GALERI FOTO --}}
                    @if($property->media->count())
                    <div class="grid grid-cols-4 grid-rows-2 gap-2 h-80 rounded-xl overflow-hidden">
                        @foreach($property->media->take(5) as $i => $media)
                            <div class="{{ $i === 0 ? 'col-span-2 row-span-2' : 'col-span-1 row-span-1' }} overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/'.$media->path) }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endforeach
                        @for($j = $property->media->count(); $j < 5; $j++)
                            @if($j > 0)
                            <div class="col-span-1 row-span-1 bg-gray-100 flex items-center justify-center text-gray-300 text-3xl">🏠</div>
                            @endif
                        @endfor
                    </div>
                    @else
                    <div class="h-80 rounded-xl bg-gray-100 flex items-center justify-center text-gray-300 text-6xl">🏠</div>
                    @endif

                    {{-- JUDUL & BADGE --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $property->status === 'dijual' ? 'bg-blue-600 text-white' : 'bg-green-600 text-white' }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full">{{ ucfirst($property->type) }}</span>
                                    @if($property->certificate)
                                    <span class="text-xs px-2 py-1 bg-blue-50 text-blue-600 rounded-full">{{ $property->certificate }}</span>
                                    @endif
                                </div>
                                <h1 class="text-xl font-bold text-gray-800">{{ $property->title }}</h1>
                                <p class="text-gray-400 text-sm mt-1">📍 {{ $property->address }}, {{ $property->city }}{{ $property->province ? ', '.$property->province : '' }}</p>
                            </div>
                        </div>

                        {{-- Fasilitas --}}
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mt-5 pt-5 border-t border-gray-50">
                            @if($property->bedrooms)
                            <div class="text-center"><p class="text-xl">🛏</p><p class="font-semibold text-gray-800 text-sm">{{ $property->bedrooms }}</p><p class="text-xs text-gray-400">K. Tidur</p></div>
                            @endif
                            @if($property->bathrooms)
                            <div class="text-center"><p class="text-xl">🚿</p><p class="font-semibold text-gray-800 text-sm">{{ $property->bathrooms }}</p><p class="text-xs text-gray-400">K. Mandi</p></div>
                            @endif
                            @if($property->land_area)
                            <div class="text-center"><p class="text-xl">📐</p><p class="font-semibold text-gray-800 text-sm">{{ $property->land_area }}m²</p><p class="text-xs text-gray-400">L. Tanah</p></div>
                            @endif
                            @if($property->building_area)
                            <div class="text-center"><p class="text-xl">🏗</p><p class="font-semibold text-gray-800 text-sm">{{ $property->building_area }}m²</p><p class="text-xs text-gray-400">L. Bangunan</p></div>
                            @endif
                            @if($property->garages)
                            <div class="text-center"><p class="text-xl">🚗</p><p class="font-semibold text-gray-800 text-sm">{{ $property->garages }}</p><p class="text-xs text-gray-400">Garasi</p></div>
                            @endif
                            @if($property->electricity)
                            <div class="text-center"><p class="text-xl">⚡</p><p class="font-semibold text-gray-800 text-sm">{{ $property->electricity }}W</p><p class="text-xs text-gray-400">Listrik</p></div>
                            @endif
                        </div>
                    </div>

                    {{-- DESKRIPSI --}}
                    @if($property->description)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-700 mb-3">Deskripsi</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $property->description }}</p>
                    </div>
                    @endif

                    {{-- VIRTUAL TOUR --}}
                    @if($property->virtual_tour_url)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-700 mb-3">Virtual Tour</h3>
                        <a href="{{ $property->virtual_tour_url }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700">
                            🎥 Lihat Virtual Tour 360°
                        </a>
                    </div>
                    @endif

                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-4">

                    {{-- HARGA & CTA --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-4">
                        <p class="text-xs text-gray-400 mb-1">Harga</p>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($property->price, 0, ',', '.') }}
                        </p>
                        @if($property->status === 'disewakan' && $property->price_monthly)
                            <p class="text-sm text-gray-400 mt-0.5">Sewa: Rp {{ number_format($property->price_monthly, 0, ',', '.') }}/bulan</p>
                        @endif
                        @if($property->deposit)
                            <p class="text-sm text-gray-400">Deposit: Rp {{ number_format($property->deposit, 0, ',', '.') }}</p>
                        @endif

                        <hr class="my-4 border-gray-100">

                        {{-- Info agen --}}
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                                {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $property->owner->name }}</p>
                                <p class="text-xs text-gray-400">Agen Properti</p>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        @auth
                            @if(auth()->user()->hasRole('pembeli'))
                                @if($property->status === 'disewakan')
                                    {{-- Tombol sewa --}}
                                    <a href="{{ route('pembeli.bookings.create', $property) }}"
                                       class="block w-full text-center py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                                        🔑 Ajukan Sewa Sekarang
                                    </a>
                                @elseif($property->status === 'dijual')
                                    {{-- Tombol beli — aktif Phase E --}}
                                    <a href="#"
                                       class="block w-full text-center py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                                        🏠 Beli Sekarang
                                    </a>
                                    <p class="text-xs text-gray-400 text-center mt-2">Pembayaran aktif di Phase E</p>
                                @else
                                    {{-- Sold out / terikat kontrak --}}
                                    <div class="block w-full text-center py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed">
                                        Tidak Tersedia
                                    </div>
                                @endif

                                {{-- Link ke daftar booking --}}
                                <a href="{{ route('pembeli.bookings.index') }}"
                                   class="block w-full text-center py-2.5 border border-gray-200 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition mt-2">
                                    📋 Lihat Booking Saya
                                </a>

                            @elseif(auth()->user()->hasRole('agen') || auth()->user()->hasRole('admin'))
                                <div class="text-center py-3 bg-gray-50 text-gray-400 text-sm rounded-xl">
                                    Login sebagai pembeli untuk sewa/beli
                                </div>
                            @endif
                        @else
                            {{-- Belum login --}}
                            <a href="{{ route('login') }}"
                               class="block w-full text-center py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                                Login untuk {{ $property->status === 'dijual' ? 'Beli' : 'Sewa' }}
                            </a>
                            <p class="text-xs text-gray-400 text-center mt-2">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar gratis</a>
                            </p>
                        @endauth
                    </div>

                    {{-- SHARE --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <p class="text-xs text-gray-400 mb-2">Bagikan properti ini</p>
                        <div class="flex gap-2">
                            <a href="https://wa.me/?text={{ urlencode($property->title.' - '.request()->url()) }}"
                               target="_blank"
                               class="flex-1 text-center py-2 bg-green-50 text-green-600 text-xs rounded-lg hover:bg-green-100 border border-green-200">
                                WhatsApp
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}').then(()=>alert('Link disalin!'))"
                                    class="flex-1 text-center py-2 bg-gray-50 text-gray-500 text-xs rounded-lg hover:bg-gray-100 border border-gray-200">
                                Salin Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROPERTI SERUPA --}}
            @if($related->count())
            <div class="mt-10">
                <h3 class="font-semibold text-gray-700 mb-4">Properti Serupa di {{ $property->city }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($related as $p)
                    <a href="{{ route('properties.show', $p) }}"
                       class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden block group">
                        <div class="h-40 bg-gray-100 overflow-hidden">
                            @if($p->cover)
                                <img src="{{ asset('storage/'.$p->cover->path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-3xl">🏠</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-gray-400 mb-1">{{ ucfirst($p->type) }} · {{ $p->city }}</p>
                            <p class="font-semibold text-gray-800 text-sm line-clamp-2">{{ $p->title }}</p>
                            <p class="text-blue-600 font-bold text-sm mt-2">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
