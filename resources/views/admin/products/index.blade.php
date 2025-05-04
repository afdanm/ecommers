<x-app-layout>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Manajemen Produk</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah Produk</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <p class="text-gray-500">Belum ada produk yang ditambahkan.</p>
    @else
    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Deskripsi</th>
                <th class="border px-4 py-2">Harga</th>
                <th class="border px-4 py-2">Kategori</th>
                <th class="border px-4 py-2">Stok</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Gambar</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td class="border px-4 py-2">{{ $product->name }}</td>
                    <td class="border px-4 py-2">{{ $product->description }}</td>
                    <td class="border px-4 py-2">{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($product->category) }}</td>
                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($product->status) }}</td>
                    <td class="border px-4 py-2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-400">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2 space-x-1">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</x-app-layout>
