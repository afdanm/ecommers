@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Daftar Produk</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(Session::has('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ Session::get('success') }}
        </div>
    @endif

    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md mb-4">Tambah Produk</a>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-4 border-b">ID</th>
                <th class="px-6 py-4 border-b">Nama Produk</th>
                <th class="px-6 py-4 border-b">Kategori</th>
                <th class="px-6 py-4 border-b">Harga</th>
                <th class="px-6 py-4 border-b">Stok</th>
                <th class="px-6 py-4 border-b">Gambar</th>
                <th class="px-6 py-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4 border-b">{{ $product->id }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->name }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->category->name }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->price }}</td>
                    <td class="px-6 py-4 border-b">{{ $product->stock }}</td>
                    <td class="px-6 py-4 border-b">
                        @if($product->photo)
    <img src="{{ asset('storage/' . $product->photo) }}" alt="Foto Produk" class="w-32 h-32 object-cover">
@else
    <p>Foto tidak tersedia</p>
@endif
                    </td>
                    <td class="px-6 py-4 border-b">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-500 hover:text-yellow-600">Edit</a> |
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
