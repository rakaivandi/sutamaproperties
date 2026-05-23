<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard Pembeli</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                Selamat datang Admin! Role: {{ auth()->user()->getRoleNames()->first() }}
            </div>
        </div>
    </div>
</x-app-layout>