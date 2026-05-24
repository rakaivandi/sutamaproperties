<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('properties.index') }}" class="hover:text-blue-600">Properti</a>
            <span>/</span>
            <a href="{{ route('properties.show', $property) }}" class="hover:text-blue-600 truncate">{{ $property->title }}</a>
            <span>/</span>
            <span class="text-gray-600">Ajukan Sewa</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">

            {{-- INFO PROPERTI --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex gap-4 mb-6">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($property->cover)
                        <img src="{{ asset('storage/'.$property->cover->path) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">🏠</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $property->title }}</h3>
                    <p class="text-gray-400 text-sm mt-0.5">📍 {{ $property->city }}</p>
                    <div class="flex gap-4 mt-2">
                        <p class="text-blue-600 font-bold text-sm">
                            Rp {{ number_format($property->price_monthly ?? $property->price, 0, ',', '.') }}
                            <span class="font-normal text-gray-400">/bulan</span>
                        </p>
                        @if($property->price_yearly)
                        <p class="text-blue-600 font-bold text-sm">
                            Rp {{ number_format($property->price_yearly, 0, ',', '.') }}
                            <span class="font-normal text-gray-400">/tahun</span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TANGGAL TIDAK TERSEDIA --}}
            @if(count($bookedDates) > 0)
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6 text-sm text-red-600">
                <p class="font-medium mb-1">⚠️ Tanggal sudah dipesan:</p>
                @foreach($bookedDates as $d)
                    <p class="text-xs">{{ \Carbon\Carbon::parse($d['start'])->format('d M Y') }} → {{ \Carbon\Carbon::parse($d['end'])->format('d M Y') }}</p>
                @endforeach
            </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('pembeli.bookings.store', $property) }}"
                  class="space-y-5">
                @csrf

                {{-- ERROR --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- PILIH TANGGAL --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📅 Pilih Tanggal Sewa</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Mulai *</label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Selesai *</label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ old('end_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 month')) }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>
                    </div>

                    {{-- Preview durasi --}}
                    <div id="duration_preview" class="mt-3 text-xs text-gray-400 hidden">
                        📆 Durasi: <span id="duration_text" class="font-medium text-gray-600"></span>
                    </div>
                </div>

                {{-- PILIH PERIODE --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">💰 Periode Sewa</h3>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 border-2 border-gray-200 rounded-xl px-4 py-3.5 cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="period" value="monthly"
                                   {{ old('period','monthly')==='monthly'?'checked':'' }}
                                   class="mt-0.5 text-blue-600" required>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Per Bulan</p>
                                <p class="text-blue-600 font-bold text-sm">Rp {{ number_format($property->price_monthly ?? $property->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">Fleksibel, minimal 1 bulan</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 border-2 border-gray-200 rounded-xl px-4 py-3.5 cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="period" value="yearly"
                                   {{ old('period')==='yearly'?'checked':'' }}
                                   class="mt-0.5 text-blue-600">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Per Tahun</p>
                                <p class="text-blue-600 font-bold text-sm">
                                    Rp {{ number_format($property->price_yearly ?? ($property->price_monthly ?? $property->price) * 12, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400">Hemat lebih banyak</p>
                            </div>
                        </label>
                    </div>

                    {{-- Estimasi total --}}
                    <div id="price_preview" class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg hidden">
                        <p class="text-xs text-blue-500 mb-0.5">Estimasi Total</p>
                        <p id="price_text" class="text-blue-700 font-bold text-lg"></p>
                        <p class="text-xs text-blue-400 mt-0.5">*Belum termasuk deposit</p>
                    </div>
                </div>

                {{-- DEPOSIT --}}
                @if($property->deposit)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-sm text-yellow-700">
                    💰 Deposit jaminan: <strong>Rp {{ number_format($property->deposit, 0, ',', '.') }}</strong>
                    <p class="text-xs text-yellow-500 mt-0.5">Deposit dikembalikan setelah masa sewa selesai</p>
                </div>
                @endif

                {{-- CATATAN --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-700 mb-3">📝 Catatan (opsional)</h3>
                    <textarea name="notes" rows="3"
                              placeholder="Pertanyaan atau permintaan khusus untuk pemilik..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>

                {{-- TOMBOL --}}
                <div class="flex gap-3 pb-8">
                    <a href="{{ route('properties.show', $property) }}"
                       class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                        ← Kembali
                    </a>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                        Konfirmasi Booking →
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- JS: Kalkulasi estimasi harga --}}
    <script>
    const priceMonthly = {{ $property->price_monthly ?? $property->price }};
    const priceYearly  = {{ $property->price_yearly ?? ($property->price_monthly ?? $property->price) * 12 }};

    function updatePreview() {
        const start  = document.getElementById('start_date').value;
        const end    = document.getElementById('end_date').value;
        const period = document.querySelector('input[name="period"]:checked')?.value;

        if (!start || !end || !period) return;

        const startDate = new Date(start);
        const endDate   = new Date(end);
        if (endDate <= startDate) return;

        let total = 0;
        let durationText = '';

        if (period === 'monthly') {
            const months = Math.max(1, Math.round((endDate - startDate) / (1000 * 60 * 60 * 24 * 30)));
            total = priceMonthly * months;
            durationText = months + ' bulan';
        } else {
            const years = Math.max(1, Math.round((endDate - startDate) / (1000 * 60 * 60 * 24 * 365)));
            total = priceYearly * years;
            durationText = years + ' tahun';
        }

        document.getElementById('duration_text').textContent = durationText;
        document.getElementById('duration_preview').classList.remove('hidden');

        document.getElementById('price_text').textContent =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('price_preview').classList.remove('hidden');
    }

    document.getElementById('start_date').addEventListener('change', updatePreview);
    document.getElementById('end_date').addEventListener('change', updatePreview);
    document.querySelectorAll('input[name="period"]').forEach(r => r.addEventListener('change', updatePreview));
    </script>
</x-app-layout>
