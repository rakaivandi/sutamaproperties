<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Booking Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                ✅ {{ session('success') }}
            </div>
            @endif

            @if($bookings->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
                    <p class="text-5xl mb-4">📅</p>
                    <p class="text-gray-600 font-semibold">Belum ada booking</p>
                    <p class="text-gray-400 text-sm mt-1">Temukan properti dan ajukan sewa sekarang</p>
                    <a href="{{ route('properties.index') }}"
                       class="inline-block mt-5 px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700">
                        Cari Properti
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($bookings as $b)
                    @php
                        $config = [
                            'pending'   => ['bg' => 'bg-yellow-50 text-yellow-600 border-yellow-200',  'label' => '⏳ Pending'],
                            'confirmed' => ['bg' => 'bg-blue-50 text-blue-600 border-blue-200',        'label' => '✅ Confirmed'],
                            'active'    => ['bg' => 'bg-green-50 text-green-600 border-green-200',     'label' => '🏠 Aktif'],
                            'completed' => ['bg' => 'bg-gray-50 text-gray-500 border-gray-200',        'label' => '🎉 Selesai'],
                            'cancelled' => ['bg' => 'bg-red-50 text-red-500 border-red-200',           'label' => '❌ Dibatalkan'],
                        ];
                        $s = $config[$b->status] ?? $config['pending'];
                    @endphp

                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex gap-4 items-center">

                        {{-- Foto --}}
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($b->property->cover)
                                <img src="{{ asset('storage/'.$b->property->cover->path) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">🏠</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $b->property->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">
                                📅 {{ $b->start_date->format('d M Y') }} → {{ $b->end_date->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $b->period === 'monthly' ? 'Per Bulan' : 'Per Tahun' }}
                            </p>
                            <p class="text-blue-600 font-bold text-sm mt-1">
                                Rp {{ number_format($b->total_price, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Status + aksi --}}
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <span class="text-xs px-2.5 py-1 rounded-full border font-medium {{ $s['bg'] }}">
                                {{ $s['label'] }}
                            </span>
                            <a href="{{ route('pembeli.bookings.show', $b) }}"
                               class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                                Detail →
                            </a>
                        </div>

                    </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $bookings->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
