@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Edit Produk</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(Session::has('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ Session::get('success') }}
        </div>
    @endif

    <!-- Form untuk mengedit produk -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama Produk -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" id="name" name="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                   value="{{ old('name', $product->name) }}" required>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi Produk -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
            <textarea id="description" name="description" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>{{ old('description', $product->description) }}</textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Harga Produk -->
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Harga Produk</label>
            <input type="number" id="price" name="price" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                   value="{{ old('price', $product->price) }}" required>
            @error('price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Stok Produk -->
        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok Produk</label>
            <input type="number" id="stock" name="stock" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                   value="{{ old('stock', $product->stock) }}" required>
            @error('stock')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Kategori Produk -->
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
            <select id="category_id" name="category_id" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Foto Produk (Jika ingin mengubah foto) -->
        <div class="mb-4">
            <label for="photo" class="block text-sm font-medium text-gray-700">Foto Produk</label>
            <input type="file" id="photo" name="photo" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            @error('photo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="mb-4">
            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                Perbarui Produk
            </button>
        </div>
    </form>
@endsection
