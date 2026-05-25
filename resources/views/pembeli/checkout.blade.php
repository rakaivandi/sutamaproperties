<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Pembayaran</h2>
    </x-slot>
    <div class="py-8 max-w-2xl mx-auto px-4 space-y-5">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-xs text-gray-400 uppercase tracking-wide mb-3">Properti</h3>
            <div class="flex gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($booking->property->cover)
                        <img src="{{ asset('storage/'.$booking->property->cover->path) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center
                                    text-gray-300 text-xl">🏠</div>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $booking->property->title }}</p>
                    <p class="text-gray-400 text-sm mt-0.5">📍 {{ $booking->property->city }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-xs text-gray-400 uppercase tracking-wide mb-3">Ringkasan Harga</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Total Sewa</span>
                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                @if($booking->deposit_paid > 0)
                <div class="flex justify-between text-gray-600">
                    <span>Deposit</span>
                    <span>Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-semibold border-t border-gray-100 pt-2">
                    <span>Total Bayar</span>
                    <span class="text-blue-600 text-base">
                        Rp {{ number_format($booking->total_price + $booking->deposit_paid, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pembeli.pay', $booking) }}">
            @csrf
            <button type="submit"
                    class="w-full py-3.5 bg-blue-600 text-white font-semibold
                           rounded-xl hover:bg-blue-700 transition">
                Lanjut ke Pembayaran
            </button>
        </form>
        <a href="{{ route('pembeli.bookings.show', $booking) }}"
           class="block text-center text-sm text-gray-400 hover:text-gray-600">Kembali</a>
    </div>
</x-app-layout>
