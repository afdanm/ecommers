@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Detail Produk</h1>

    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <strong>Nama Produk:</strong> {{ $product->name }}
        </div>
        <div class="mb-4">
            <strong>Kategori:</strong> {{ $product->category->name ?? '-' }}
        </div>
        <div class="mb-4">
            <strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}
        </div>
        <div class="mb-4">
            <strong>Stok Total:</strong> {{ $product->stock }}
        </div>
        <div class="mb-4">
            <strong>Deskripsi:</strong> {{ $product->description ?? '-' }}
        </div>
        <div class="mb-4">
            <strong>Ukuran & Stok:</strong>
            <ul class="list-disc list-inside">
                @foreach ($product->sizes as $size)
                    <li>{{ $size->name }} (Stok: {{ $size->pivot->stock }})</li>
                @endforeach
            </ul>
        </div>
        <div class="mb-4">
            <strong>Gambar:</strong><br>
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-48 h-48 object-cover mt-2 rounded">
            @else
                <p class="text-gray-500 italic">Gambar tidak tersedia.</p>
            @endif
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">← Kembali ke daftar produk</a>
    </div>
@endsection
