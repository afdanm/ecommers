@extends('layouts.home')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->id) }}" class="border rounded p-4 hover:shadow">
                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="h-32 w-full object-cover mb-2">
                <h2 class="font-semibold">{{ $product->name }}</h2>
                <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                <p class="text-green-600 font-bold mt-1">Rp {{ number_format($product->price) }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
