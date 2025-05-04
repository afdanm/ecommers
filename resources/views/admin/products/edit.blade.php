<x-app-layout>

    <h2 class="text-2xl font-bold mb-4">Edit Produk</h2>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-4">Kembali</a>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="cwe" {{ old('category_id', $product->category_id) == 'cwe' ? 'selected' : '' }}>CWE</option>
                <option value="cwo" {{ old('category_id', $product->category_id) == 'cwo' ? 'selected' : '' }}>CWO</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="ready" {{ old('status', $product->status) == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="habis" {{ old('status', $product->status) == 'habis' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Produk</button>
    </form>
</x-app-layout>
