@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Daftar Produk</h1>

    @if(isset($category))
        <div class="mb-2 text-gray-700">
            Menampilkan produk untuk kategori: <strong>{{ $category->name }}</strong>
        </div>
    @endif

    <a href="{{ route('admin.products.create', request()->only('category_id')) }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md mb-4 inline-block">Tambah Produk</a>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b">ID</th>
                <th class="px-6 py-3 border-b">Nama</th>
                <th class="px-6 py-3 border-b">Kategori</th>
                <th class="px-6 py-3 border-b">Harga</th>
                <th class="px-6 py-3 border-b">Stok</th>
                <th class="px-6 py-3 border-b">gambar</th>
                <th class="px-6 py-3 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 border-b">{{ $product->id }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->name }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 border-b">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->stock }}</td>
                    <td class="px-6 py-4 border-b">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="w-32 h-32 object-cover">
                        @else
                            <p>Gambar tidak tersedia</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 border-b">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-500 hover:text-yellow-600">Edit</a> |
                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-blue-500 hover:text-blue-600">Lihat</a> |
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada produk ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
