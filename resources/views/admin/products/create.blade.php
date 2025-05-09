@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Tambah Produk</h2>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-4">Kembali</a>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            @error('name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full mt-1 border-gray-300 rounded-md shadow-sm"
                required>{{ old('description') }}</textarea>
            @error('description')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            @error('price')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="baju">Baju</option>
                <option value="celana">Celana</option>
                <option value="sepatu">Sepatu</option>
                <option value="kaos_kaki">Kaos Kaki</option>
                <option value="aksesoris">Aksesoris</option>
                <option value="tas">Tas</option>
            </select>
        </div>


        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            @error('stock')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="ready">Ready</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('image')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Produk</button>
    </form>
@endsection