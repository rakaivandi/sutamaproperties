<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pembayaran</h2>
    </x-slot>
    <div class="py-8 max-w-lg mx-auto px-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
            <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center
                        justify-center text-2xl mx-auto mb-4">💳</div>
            <p class="text-sm text-gray-400 mb-1">{{ $transaction->invoice_number }}</p>
            <p class="font-semibold text-gray-800 mb-1">{{ $transaction->property->title }}</p>
            <p class="text-blue-600 font-bold text-2xl mb-6">
                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
            </p>
            <button id="pay-btn"
                    class="w-full py-3.5 bg-blue-600 text-white font-semibold
                           rounded-xl hover:bg-blue-700 transition">
                Bayar Sekarang
            </button>
            <p class="text-xs text-gray-400 mt-4">Didukung Midtrans · Pembayaran aman</p>
        </div>
    </div>
    <script src="{{ config('midtrans.snap_url') }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
    document.getElementById('pay-btn').onclick = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function() {
                window.location.href = '{{ route("pembeli.payment.success", $transaction) }}';
            },
            onPending: function() { alert('Pembayaran pending.'); },
            onError: function()   { alert('Pembayaran gagal. Coba lagi.'); }
        });
    };
    </script>
</x-app-layout>
