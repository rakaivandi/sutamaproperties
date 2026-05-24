<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('pembeli.bookings.index') }}" class="hover:text-blue-600">Booking Saya</a>
            <span>/</span>
            <span class="text-gray-600">Detail Booking #{{ $booking->id }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                ✅ {{ session('success') }}
            </div>
            @endif

            {{-- STATUS BANNER --}}
            @php
                $config = [
                    'pending'   => ['bg' => 'bg-yellow-50 border-yellow-200',  'text' => 'text-yellow-700', 'icon' => '⏳', 'label' => 'Menunggu Pembayaran'],
                    'confirmed' => ['bg' => 'bg-blue-50 border-blue-200',      'text' => 'text-blue-700',   'icon' => '✅', 'label' => 'Booking Dikonfirmasi'],
                    'active'    => ['bg' => 'bg-green-50 border-green-200',    'text' => 'text-green-700',  'icon' => '🏠', 'label' => 'Sedang Berjalan'],
                    'completed' => ['bg' => 'bg-gray-50 border-gray-200',      'text' => 'text-gray-600',   'icon' => '🎉', 'label' => 'Selesai'],
                    'cancelled' => ['bg' => 'bg-red-50 border-red-200',        'text' => 'text-red-600',    'icon' => '❌', 'label' => 'Dibatalkan'],
                ];
                $s = $config[$booking->status] ?? $config['pending'];
            @endphp

            <div class="border rounded-xl px-5 py-4 mb-6 flex items-center gap-3 {{ $s['bg'] }}">
                <span class="text-2xl">{{ $s['icon'] }}</span>
                <div>
                    <p class="font-semibold {{ $s['text'] }}">{{ $s['label'] }}</p>
                    <p class="text-xs {{ $s['text'] }} opacity-70 mt-0.5">
                        Booking #{{ $booking->id }} · Dibuat {{ $booking->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            {{-- INFO PROPERTI --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
                <h3 class="text-xs text-gray-400 uppercase tracking-wide mb-3">Properti</h3>
                <div class="flex gap-4">
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        @if($booking->property->cover)
                            <img src="{{ asset('storage/'.$booking->property->cover->path) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">🏠</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800">{{ $booking->property->title }}</p>
                        <p class="text-gray-400 text-sm mt-0.5">📍 {{ $booking->property->city }}</p>
                        <a href="{{ route('properties.show', $booking->property) }}"
                           class="text-xs text-blue-500 hover:underline mt-1 inline-block">
                            Lihat halaman properti →
                        </a>
                    </div>
                </div>
            </div>

            {{-- DETAIL BOOKING --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
                <h3 class="text-xs text-gray-400 uppercase tracking-wide mb-4">Detail Sewa</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $booking->start_date->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Tanggal Selesai</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $booking->end_date->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Durasi</p>
                        <p class="font-semibold text-gray-800 text-sm">
                            @if($booking->period === 'monthly')
                                {{ $booking->start_date->diffInMonths($booking->end_date) ?: 1 }} Bulan
                            @else
                                {{ $booking->start_date->diffInYears($booking->end_date) ?: 1 }} Tahun
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Periode</p>
                        <p class="font-semibold text-gray-800 text-sm">
                            {{ $booking->period === 'monthly' ? 'Per Bulan' : 'Per Tahun' }}
                        </p>
                    </div>
                </div>

                @if($booking->notes)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-400 mb-1">Catatan</p>
                    <p class="text-sm text-gray-600">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            {{-- RINGKASAN HARGA --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
                <h3 class="text-xs text-gray-400 uppercase tracking-wide mb-4">Ringkasan Harga</h3>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Total Sewa</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->deposit_paid > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Deposit Jaminan</span>
                        <span>Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-100 pt-2 mt-2 flex justify-between font-bold text-gray-800">
                        <span>Total Bayar</span>
                        <span class="text-blue-600 text-base">
                            Rp {{ number_format($booking->total_price + $booking->deposit_paid, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- AKSI --}}
            <div class="space-y-3">
                @if($booking->status === 'pending')
                    <button class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                        💳 Bayar Sekarang — aktif di Phase E
                    </button>
                    <form method="POST" action="{{ route('pembeli.bookings.cancel', $booking) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                                class="w-full py-2.5 border border-red-200 text-red-500 text-sm font-medium rounded-xl hover:bg-red-50 transition">
                            Batalkan Booking
                        </button>
                    </form>
                @endif

                <a href="{{ route('pembeli.bookings.index') }}"
                   class="block w-full text-center py-2.5 border border-gray-200 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                    ← Kembali ke Semua Booking
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
