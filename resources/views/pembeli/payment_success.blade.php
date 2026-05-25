<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pembayaran Berhasil</h2>
    </x-slot>
    <div class="py-8 max-w-lg mx-auto px-4 space-y-5">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center
                        justify-center text-3xl mx-auto mb-4">✅</div>
            <h3 class="font-semibold text-gray-800 text-lg mb-1">Pembayaran Berhasil!</h3>
            <p class="text-gray-400 text-sm">{{ $transaction->invoice_number }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Properti</span>
                <span class="font-medium text-gray-700 truncate max-w-xs">
                    {{ $transaction->property->title }}
                </span>
            </div>
            @if($transaction->booking)
            <div class="flex justify-between">
                <span class="text-gray-400">Periode</span>
                <span class="font-medium text-gray-700">
                    {{ $transaction->booking->start_date->format('d M Y') }} →
                    {{ $transaction->booking->end_date->format('d M Y') }}
                </span>
            </div>
            @endif
            <div class="flex justify-between border-t border-gray-100 pt-3 font-semibold">
                <span>Total Dibayar</span>
                <span class="text-blue-600">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('pembeli.bookings.show', $transaction->booking) }}"
               class="flex-1 text-center py-2.5 bg-blue-600 text-white
                      text-sm font-semibold rounded-xl hover:bg-blue-700">
                Lihat Detail Booking
            </a>
            <a href="{{ route('properties.index') }}"
               class="flex-1 text-center py-2.5 border border-gray-200
                      text-gray-600 text-sm rounded-xl hover:bg-gray-50">
                Cari Properti Lain
            </a>
        </div>
    </div>
</x-app-layout>
