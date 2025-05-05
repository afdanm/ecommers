<!-- produk/show.blade.php -->

@extends('layouts.home')

@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-2xl font-semibold">{{ $product->name }}</h3>
                <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                <p class="text-lg font-semibold mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
@endsection