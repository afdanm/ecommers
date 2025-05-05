<!-- resources/views/products/index.blade.php -->
@extends('layouts.home') <!-- Layout umum kamu -->

@section('content')
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
                <div class="border rounded p-4">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-40 object-cover mb-2">
                    <h2 class="font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <!-- Tombol Aksi -->
                    @auth
                        <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded mt-2 inline-block">Checkout</a>
                        <a href="#" class="bg-yellow-500 text-white px-3 py-1 rounded mt-2 inline-block">Kasih Rating</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-gray-400 text-white px-3 py-1 rounded mt-2 inline-block cursor-not-allowed opacity-70">Login
                            untuk Checkout</a>
                        <a href="{{ route('login') }}"
                            class="bg-gray-400 text-white px-3 py-1 rounded mt-2 inline-block cursor-not-allowed opacity-70">Login
                            untuk Rating</a>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
@endsection