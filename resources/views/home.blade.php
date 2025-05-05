@extends('layouts.home')

@section('content')
    <div class="container mx-auto px-4">

        <!-- Banner Promo -->
        <div class="relative bg-blue-500 text-white text-center py-16 mb-12 rounded-lg overflow-hidden shadow-lg">
            {{-- <img src="{{ asset('storage/banner-image.jpg') }}" alt="Banner Promo" class="absolute inset-0 w-full h-full object-cover opacity-30">
            <div class="relative z-10"> --}}
                <h1 class="text-5xl font-extrabold mb-4 animate__animated animate__fadeIn">Promo Spesial! Diskon hingga 50%</h1>
                <p class="text-xl mb-6 animate__animated animate__fadeIn animate__delay-1s">Jangan lewatkan penawaran menarik ini. Belanja sekarang!</p>
                <a href="{{ route('products.list') }}" class="bg-yellow-500 text-black py-3 px-8 rounded-full text-lg font-semibold hover:bg-yellow-400 transition duration-300 ease-in-out transform hover:scale-105 animate__animated animate__fadeIn animate__delay-2s">
                    Belanja Sekarang
                </a>
            </div>
        </div>

        <!-- Produk Unggulan -->
        <div class="mb-12">
            <h2 class="text-3xl font-semibold text-center mb-8 text-gray-800">Produk Unggulan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transform transition duration-300 ease-in-out hover:scale-105">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-60 object-cover transition duration-300 ease-in-out hover:opacity-75">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center mt-6">
                                <span class="text-lg font-bold text-yellow-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-300">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Testimoni -->
        <div class="bg-gray-100 py-12 mb-12 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Apa Kata Mereka?</h2>
            <div class="flex justify-center space-x-8">
                <div class="bg-white p-6 shadow-md rounded-lg w-64 transform transition duration-300 hover:scale-105">
                    <p class="text-gray-600">"Produk sangat berkualitas dan pengiriman cepat!"</p>
                    <p class="text-right text-sm mt-4 text-gray-500">- Customer A</p>
                </div>
                <div class="bg-white p-6 shadow-md rounded-lg w-64 transform transition duration-300 hover:scale-105">
                    <p class="text-gray-600">"Pelayanan pelanggan sangat memuaskan, pasti beli lagi!"</p>
                    <p class="text-right text-sm mt-4 text-gray-500">- Customer B</p>
                </div>
            </div>
        </div>

        <!-- CTA Belanja -->
        <div class="text-center">
            <a href="{{ route('admin.products.index') }}" class="bg-blue-500 text-white py-4 px-10 rounded-full text-lg font-semibold hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                Belanja Sekarang
            </a>
        </div>

    </div>
@endsection
