@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Daftar Produk</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.products.create') }}"
        class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md mb-4">
        Tambah Produk
    </a>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b text-left">ID</th>
                <th class="px-6 py-3 border-b text-left">Nama Produk</th>
                <th class="px-6 py-3 border-b text-left">Kategori</th>
                <th class="px-6 py-3 border-b text-left">Harga</th>
                <th class="px-6 py-3 border-b text-left">Stok</th>
                <th class="px-6 py-3 border-b text-left">Gambar</th>
                <th class="px-6 py-3 border-b text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 border-b">{{ $product->id }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->name }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 border-b">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->stock }}</td>

                    <td class="px-6 py-4 border-b w-36">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Foto Produk"
                                class="w-32 h-32 object-cover rounded-md">
                        @else
                            <span class="text-gray-500 italic">Foto tidak tersedia</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 border-b">
                        <a href="{{ route('admin.products.show', $product->id) }}"
                            class="text-blue-600 hover:text-blue-800 mr-2">Lihat</a>
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                            class="text-yellow-600 hover:text-yellow-800 mr-2">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if ($products->isEmpty())
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500 italic">Belum ada produk.</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
