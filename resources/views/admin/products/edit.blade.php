@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Edit Produk</h2>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-4">Kembali</a>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full mt-1 border-gray-300 rounded-md shadow-sm"
                required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="baju" {{ old('category', $product->category) == 'baju' ? 'selected' : '' }}>Baju</option>
                <option value="celana" {{ old('category', $product->category) == 'celana' ? 'selected' : '' }}>Celana</option>
                <option value="sepatu" {{ old('category', $product->category) == 'sepatu' ? 'selected' : '' }}>Sepatu</option>
                <option value="kaos_kaki" {{ old('category', $product->category) == 'kaos_kaki' ? 'selected' : '' }}>Kaos Kaki
                </option>
                <option value="aksesoris" {{ old('category', $product->category) == 'aksesoris' ? 'selected' : '' }}>Aksesoris
                </option>
                <option value="tas" {{ old('category', $product->category) == 'tas' ? 'selected' : '' }}>Tas</option>
            </select>
        </div>


        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="ready" {{ old('status', $product->status) == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Out of
                    Stock</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Image" class="mt-2 w-32">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Produk</button>
    </form>
@endsection