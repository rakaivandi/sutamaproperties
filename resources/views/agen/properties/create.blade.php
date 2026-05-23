<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Properti Baru</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">
            <form method="POST" action="{{ route('agen.properties.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- INFORMASI DASAR --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📋 Informasi Dasar</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Judul Properti *</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   placeholder="Contoh: Rumah Minimalis 2 Lantai di Denpasar"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tipe *</label>
                                <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['rumah','apartemen','tanah','ruko','villa'] as $t)
                                        <option value="{{ $t }}" {{ old('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Status *</label>
                                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="dijual" {{ old('status')==='dijual'?'selected':'' }}>Dijual</option>
                                    <option value="disewakan" {{ old('status')==='disewakan'?'selected':'' }}>Disewakan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                      placeholder="Jelaskan keunggulan properti ini..."
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- HARGA --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">💰 Harga</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Harga Jual (Rp) *</label>
                            <input type="number" name="price" value="{{ old('price') }}"
                                   placeholder="500000000"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Harga Sewa/Bulan (Rp)</label>
                            <input type="number" name="price_monthly" value="{{ old('price_monthly') }}"
                                   placeholder="5000000"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- LOKASI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📍 Lokasi</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Kota *</label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                       placeholder="Denpasar"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Provinsi</label>
                                <input type="text" name="province" value="{{ old('province') }}"
                                       placeholder="Bali"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Lengkap *</label>
                            <textarea name="address" rows="2"
                                      placeholder="Jl. Sunset Road No. 88, Kuta"
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
                            @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- FASILITAS --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">🏠 Fasilitas</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">🛏 Kamar Tidur</label>
                            <input type="number" name="bedrooms" value="{{ old('bedrooms', 0) }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">🚿 Kamar Mandi</label>
                            <input type="number" name="bathrooms" value="{{ old('bathrooms', 0) }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">🚗 Garasi</label>
                            <input type="number" name="garages" value="{{ old('garages', 0) }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">📐 Luas Tanah (m²)</label>
                            <input type="number" name="land_area" value="{{ old('land_area') }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">🏗 Luas Bangunan (m²)</label>
                            <input type="number" name="building_area" value="{{ old('building_area') }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">⚡ Listrik (Watt)</label>
                            <input type="number" name="electricity" value="{{ old('electricity') }}"
                                   min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Sertifikat</label>
                        <select name="certificate" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih sertifikat</option>
                            @foreach(['SHM','HGB','SHGB','Girik','Strata Title','Lainnya'] as $c)
                                <option value="{{ $c }}" {{ old('certificate')===$c?'selected':'' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- FOTO --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📸 Foto Properti</h3>
                    <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-400 text-sm mb-3">Foto pertama akan jadi cover utama</p>
                        <input type="file" name="photos[]" multiple accept="image/*"
                               class="text-sm text-gray-500">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG · Maks 2MB per foto</p>
                    </div>
                </div>

                {{-- TOMBOL --}}
                <div class="flex justify-end gap-3 pb-8">
                    <a href="{{ route('agen.properties.index') }}"
                       class="px-5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                        Simpan Properti
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
