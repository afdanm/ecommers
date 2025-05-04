<x-app-layout>

    <h2 class="text-2xl font-bold mb-4">Tambah Produk</h2>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-4">Kembali</a>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" id="price" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            <option value="ready">cwe</option>
            <option value="habis">cwo</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" id="stock" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                <option value="ready">Ready</option>
                <option value="habis">Habis</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Produk</button>
    </form>
</x-app-layout>
